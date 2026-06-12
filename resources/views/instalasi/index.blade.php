@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <svg class="h-5 w-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-950">License Tracker</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">License Tracker</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Kelola data lisensi dan riwayat instalasi software di seluruh laboratorium komputer.
                </p>
            </div>
            <div>
                <button type="button" onclick="openModal()" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-950/20">
                    Tambah Lisensi Baru
                </button>
            </div>
        </div>
    </section>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
        
        <form action="{{ route('instalasi.index') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchLicense" name="search" value="{{ request('search') }}"
                           class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10" 
                           placeholder="Cari teks instan...">
                </div>

                <div>
                    <select name="lab" onchange="this.form.submit()" 
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                        <option value="">-- Semua Laboratorium --</option>
                        @foreach($laboratoriums as $lab)
                            <option value="{{ $lab->no_lab }}" {{ request('lab') == $lab->no_lab ? 'selected' : '' }}>
                                {{ $lab->nama_lab }} ({{ $lab->no_lab }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="software" onchange="this.form.submit()" 
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                        <option value="">-- Semua Software --</option>
                        @foreach($softwares as $sw)
                            <option value="{{ $sw->id_software }}" {{ request('software') == $sw->id_software ? 'selected' : '' }}>
                                {{ $sw->nama_software }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center">
                    @if(request('lab') || request('software') || request('search'))
                        <a href="{{ route('instalasi.index') }}" 
                           class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50 shadow-sm"
                           title="Reset Filter">
                             Reset Filter
                        </a>
                    @else
                        <span class="text-xs text-slate-400 font-medium italic">Silakan pilih opsi filter di atas</span>
                    @endif
                </div>

            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4 text-center w-12">No.</th>
                        <th class="px-6 py-4">Software</th>
                        <th class="px-6 py-4">Laboratorium</th>
                        <th class="px-6 py-4">Penginstal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Mulai</th>
                        <th class="px-6 py-4">Berakhir</th>
                        <th class="px-6 py-4 text-center w-20">Edit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                    @forelse($instalasis as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-center text-slate-400 font-normal">{{ $index + 1 }}.</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-950 font-black text-xs text-white shadow-sm uppercase tracking-tighter">
                                        {{ substr($item->software->nama_software ?? 'SW', 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-950">{{ $item->software->nama_software ?? 'Unknown Software' }}</div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="inline-flex items-center rounded-md bg-slate-100 px-1.5 py-0.5 text-[11px] font-bold text-slate-700 ring-1 ring-inset ring-slate-600/10">
                                                v{{ $item->versi_terinstall }}
                                            </span>
                                            <span class="text-xs font-medium text-slate-400">ID: {{ $item->id_software }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $item->laboratorium->nama_lab ?? '' . $item->no_lab }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-medium">{{ $item->teknisi->name ?? 'Admin' }}</div>
                                <div class="text-[11px] font-normal text-slate-400">No Induk: {{ $item->diinstal_oleh }}</div>
                            </td>
                            
                            <td class="px-6 py-4">
                                @if($item->tgl_expired)
                                    @if(\Carbon\Carbon::parse($item->tgl_expired)->isPast())
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Kedaluwarsa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-0.5 text-xs font-bold text-blue-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Aktif (Tanpa Batas)
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $item->tgl_aktif ? \Carbon\Carbon::parse($item->tgl_aktif)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $item->tgl_expired ? \Carbon\Carbon::parse($item->tgl_expired)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('instalasi.edit', $item->id ?? $item->id_instalasi) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20" title="Edit Data">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm font-medium text-slate-400">
                                Tidak ada data lisensi instalasi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> 

    <div class="flex items-start gap-3.5 rounded-2xl border border-blue-100 bg-blue-50/60 p-4 shadow-sm">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </div>
        <div class="space-y-0.5">
            <h4 class="text-sm font-bold text-slate-950">Kelola Lisensi dengan Baik</h4>
            <p class="text-xs font-medium text-slate-600 leading-relaxed">Pastikan semua lisensi software selalu aktif dan sesuai dengan ketentuan yang berlaku untuk menjaga legalitas penggunaan software di laboratorium.</p>
        </div>
    </div>

    <div id="modalTambahLisensi" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl border border-slate-100 transform transition-all space-y-4">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Form Tambah Lisensi</h3>
                <button type="button" onclick="closeModal()" class="rounded-lg p-1 text-slate-400 hover:bg-slate-50 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('instalasi.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih Software *</label>
                    <select name="id_software" id="modal_id_software" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10" required>
                        <option value="">-- Pilih Software --</option>
                        @foreach($softwares as $sw)
                            <option value="{{ $sw->id_software }}" data-versi="{{ $sw->versi ?? $sw->versi_software ?? '' }}">
                                {{ $sw->nama_software }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Versi Terinstall *</label>
                    <select name="versi_terinstall" id="modal_versi_terinstall" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10" required>
                        <option value="">-- Pilih Versi --</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Laboratorium *</label>
                    <select name="no_lab" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10" required>
                        <option value="">-- Pilih Laboratorium --</option>
                        @foreach($laboratoriums as $lab)
                            <option value="{{ $lab->no_lab }}">{{ $lab->nama_lab }} ({{ $lab->no_lab }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Lisensi *</label>
                    <select name="status_lisensi" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10" required>
                        <option value="license_active">License Active (Berbayar/Subs)</option>
                        <option value="free_license">Free License (Gratis/Open Source)</option>
                        <option value="license_expired">License Expired</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Aktif</label>
                        <input type="date" name="tgl_aktif" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Expired</label>
                        <input type="date" name="tgl_expired" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4 mt-2">
                    <button type="button" onclick="closeModal()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="rounded-xl bg-blue-950 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-900 shadow-sm">
                        Simpan Lisensi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function openModal() {
        document.getElementById('modalTambahLisensi').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalTambahLisensi').classList.add('hidden');
        // Reset dropdown versi jika modal ditutup agar kembali bersih
        document.getElementById('modal_versi_terinstall').innerHTML = '<option value="">-- Pilih Versi --</option>';
        document.getElementById('modal_id_software').selectedIndex = 0;
    }

    // LOGIKA CHAINED DROPDOWN (PILIHAN VERSI OTOMATIS)
    document.getElementById('modal_id_software').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const versiData = selectedOption.getAttribute('data-versi');
        const versiSelect = document.getElementById('modal_versi_terinstall');
        
        // Bersihkan data dropdown versi lama
        versiSelect.innerHTML = '<option value="">-- Pilih Versi --</option>';
        
        if (versiData && versiData.trim() !== '') {
            // Memisahkan data versi jika di DB menggunakan pemisah koma (contoh: v1, v2, v3)
            const daftarVersi = versiData.split(',');
            
            daftarVersi.forEach(versi => {
                const cleanVersi = versi.trim();
                if (cleanVersi) {
                    const opt = document.createElement('option');
                    opt.value = cleanVersi;
                    opt.textContent = cleanVersi;
                    versiSelect.appendChild(opt);
                }
            });
        } else {
            // Jika kolom versi kosong di database software, sediakan opsi default agar form tidak error saat disubmit
            const opt = document.createElement('option');
            opt.value = "Default";
            opt.textContent = "Default / Tidak Ada Informasi Versi";
            versiSelect.appendChild(opt);
        }
    });

    // Fungsi live search lokal pencarian instan pada tabel utama
    document.getElementById('searchLicense').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
</script>
@endsection