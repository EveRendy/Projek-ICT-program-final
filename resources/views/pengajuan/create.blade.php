@extends('layouts.app')

@section('content')
<div class="mx-auto w-full max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-6 flex flex-col gap-2 border-b border-slate-100 pb-5">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-700">Form Pengajuan</p>
            <h2 class="text-2xl font-black text-slate-950">Tambah Pengajuan Instalasi Software</h2>
            <p class="text-sm text-slate-500">Isi data kebutuhan software, laboratorium tujuan, dan versi yang diinginkan.</p>
        </div>

        @if($errors->has('software_error'))
            <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
                {{ $errors->first('software_error') }}
            </div>
        @endif

        <form action="{{ route('pengajuan.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <label class="block">
                    <span class="mb-1.5 block text-sm font-bold text-slate-700">Nama Mata Kuliah</span>
                    <input type="text" name="mata_kuliah" required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Contoh: Web Programming">
                </label>

                <label class="block">
                    <span class="mb-1.5 block text-sm font-bold text-slate-700">Kelompok / Kelas</span>
                    <input type="text" name="kelompok_matkul" required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Contoh: LAB-A">
                </label>
            </div>

            <label class="block">
                <span class="mb-1.5 block text-sm font-bold text-slate-700">Pilih Laboratorium Tujuan</span>
                <select name="laboratorium_id" id="laboratorium_id" required onchange="cekKompatibilitas()"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">-- Pilih Ruang Lab --</option>
                    @foreach($laboratoriums as $lab)
                        <option value="{{ $lab->id }}" data-level="{{ $lab->level }}">
                            {{ $lab->no_lab }} (Spesifikasi Level {{ $lab->level }})
                        </option>
                    @endforeach
                </select>
            </label>

            <div class="rounded-3xl border border-slate-200 bg-slate-50/80 p-5">
                <div class="mb-4 flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-blue-700">A</span>
                    <h3 class="text-base font-black text-slate-950">Pilih dari Daftar Software Master</h3>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-bold text-slate-700">Software</span>
                        <select name="software_id" id="software_id" onchange="updateVersiDanCek()"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">-- Pilih Software Terdaftar --</option>
                            @foreach($softwares as $soft)
                                <option value="{{ $soft->id }}" data-level="{{ $soft->keterangan }}" data-versi="{{ json_encode($soft->versi) }}">
                                    {{ $soft->nama_software }} (Butuh Spek Level {{ $soft->keterangan }})
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-1.5 block text-sm font-bold text-slate-700">Versi Software</span>
                        <select name="versi_requested" id="versi_requested"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">-- Pilih Versi --</option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="rounded-3xl border border-amber-200 bg-amber-50/80 p-5">
                <div class="mb-4 flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-amber-100 text-amber-700">B</span>
                    <h3 class="text-base font-black text-slate-950">Pengajuan Khusus (Jika tidak ada di daftar atas)</h3>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-bold text-slate-700">Nama Software Lain</span>
                        <input type="text" name="software_lain"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            placeholder="Contoh: CorelDraw X8">
                    </label>

                    <label class="block">
                        <span class="mb-1.5 block text-sm font-bold text-slate-700">Versi Software Lain</span>
                        <input type="text" name="versi_lain"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            placeholder="Contoh: v24.0">
                    </label>
                </div>
            </div>

            <div id="compatibility-warning" class="hidden"></div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500/30">Batal
                </a>
                <button type="submit" id="submit_btn" class="inline-flex items-center justify-center rounded-2xl bg-blue-950 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 disabled:cursor-not-allowed disabled:border-slate-300 disabled:bg-slate-300 disabled:text-slate-500 disabled:shadow-none">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateVersiDanCek() {
        const softSelect = document.getElementById('software_id');
        const versiSelect = document.getElementById('versi_requested');
        const selectedOption = softSelect.options[softSelect.selectedIndex];

        versiSelect.innerHTML = '<option value="">-- Pilih Versi --</option>';

        if (selectedOption.value !== "") {
            const daftarVersi = JSON.parse(selectedOption.getAttribute('data-versi'));
            daftarVersi.forEach(versi => {
                const opt = document.createElement('option');
                opt.value = versi;
                opt.textContent = versi;
                versiSelect.appendChild(opt);
            });
        }

        cekKompatibilitas();
    }

    function cekKompatibilitas() {
        const labSelect = document.getElementById('laboratorium_id');
        const softSelect = document.getElementById('software_id');
        const warningBox = document.getElementById('compatibility-warning');
        const submitBtn = document.getElementById('submit_btn');

        // Setel ulang (reset) ke kondisi normal setiap kali dropdown diganti
        warningBox.classList.add('hidden');
        if (submitBtn) submitBtn.disabled = false;

        // Pastikan Lab dan Software sudah dipilih sebelum melakukan pengecekan
        if (!labSelect || !softSelect || labSelect.value === "" || softSelect.value === "") {
            return;
        }

        const labLevel = parseInt(labSelect.options[labSelect.selectedIndex].getAttribute('data-level')) || 0;
        const softLevel = parseInt(softSelect.options[softSelect.selectedIndex].getAttribute('data-level')) || 0;

        // Proses pengecekan
        if (labLevel > 0 && softLevel > 0) {
            if (softLevel > labLevel) {
                // Tampilkan kotak peringatan
                warningBox.classList.remove('hidden');

                if (labLevel === 1 && softLevel >= 3) {
                    // KONDISI KRITIS (Lab 1 vs Soft 3): Warna Merah & Tombol Mati
                    warningBox.className = "rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800";
                    warningBox.innerHTML = `⚠️ <strong>Peringatan Kritis:</strong> Spesifikasi laboratorium tujuan (Level ${labLevel}) sangat jauh di bawah syarat minimum software (Level ${softLevel}). Instalasi tidak dapat diajukan di ruangan ini.`;
                    
                    if (submitBtn) submitBtn.disabled = true; // Matikan tombol
                } else {
                    // KONDISI BIASA (Lab 2 vs Soft 3, dst): Warna Kuning & Tombol Tetap Nyala
                    warningBox.className = "rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800";
                    warningBox.innerHTML = `⚠️ <strong>Peringatan Kompatibilitas:</strong> Spesifikasi laboratorium tujuan (Level ${labLevel}) lebih rendah dibandingkan level beban software (Level ${softLevel}). Instalasi tetap dapat diajukan, namun kinerja software mungkin lambat.`;
                    
                    if (submitBtn) submitBtn.disabled = false; // Biarkan tombol menyala
                }
            }
        }
    }
</script>
@endsection