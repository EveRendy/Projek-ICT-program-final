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

            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
                    <strong>Pengajuan gagal dikirim karena:</strong>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <div id="instalasi-warning" class="mb-5 hidden rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-800">
            </div>

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
                <x-custom-select
                    id="laboratorium_id"
                    name="laboratorium_id"
                    label="-- Pilih Ruang Lab --"
                    :selected="old('laboratorium_id', '')"
                    :required="true"
                    :options="array_merge(
                        [['value' => '', 'label' => '-- Pilih Ruang Lab --']],
                        $laboratoriums->map(fn($lab) => [
                            'value' => (string) $lab->id,
                            'label' => $lab->no_lab . ($lab->nama_lab ? ' : ' . $lab->nama_lab : '') . ' (Spesifikasi Level ' . $lab->level . ')',
                        ])->toArray()
                    )" />
            </label>

            <div class="rounded-3xl border border-slate-200 bg-slate-50/80 p-5">
                <div class="mb-4 flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-blue-700">A</span>
                    <h3 class="text-base font-black text-slate-950">Pilih Kebutuhan Software</h3>
                </div>
                
                <div class="grid gap-5 md:grid-cols-2">
                    <div class="flex flex-col gap-4">
                        <label class="block">
                            <span class="mb-1.5 block text-sm font-bold text-slate-700">Software</span>
                            <x-custom-select
                                id="software_id"
                                name="software_id"
                                label="-- Pilih Software Terdaftar --"
                                :selected="old('software_id', '')"
                                :required="true"
                                :options="array_merge(
                                    [['value' => '', 'label' => '-- Pilih Software Terdaftar --']],
                                    $softwares->map(fn($soft) => [
                                        'value' => (string) $soft->id,
                                        'label' => $soft->nama_software . ' (Butuh Spek Level ' . $soft->keterangan . ')',
                                    ])->toArray(),
                                    [['value' => 'lainnya', 'label' => 'Lainnya (Tidak ada di daftar)']]
                                )" />
                        </label>

                        <div id="container_software_lain" class="hidden transition-all duration-300">
                            <label class="block">
                                <span class="mb-1.5 block text-sm font-bold text-slate-700">Nama Software Lain</span>
                                <input type="text" name="software_lain" id="software_lain"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                    placeholder="Contoh: CorelDraw X8">
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <label class="block">
                            <span class="mb-1.5 block text-sm font-bold text-slate-700">Versi Software</span>
                            <x-custom-select
                                id="versi_requested"
                                name="versi_requested"
                                label="-- Pilih Versi --"
                                :selected="old('versi_requested', '')"
                                :required="true"
                                :options="[['value' => '', 'label' => '-- Pilih Versi --']]" />
                        </label>

                        <div id="container_versi_lain" class="hidden transition-all duration-300">
                            <label class="block">
                                <span class="mb-1.5 block text-sm font-bold text-slate-700">Masukkan Versi Baru</span>
                                <input type="text" name="versi_lain" id="versi_lain"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                    placeholder="Contoh: v24.0">
                            </label>
                            <p id="hint_versi_baru" class="hidden mt-2 text-xs text-blue-600 bg-blue-50 border border-blue-100 rounded-xl px-3 py-2">
                                <svg class="inline h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Versi baru ini akan otomatis ditambahkan ke daftar versi software master setelah pengajuan disetujui supervisor.
                            </p>
                        </div>
                    </div>
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
    const labLevels = @json($laboratoriums->pluck('level', 'id'));
    const softwareData = @json($softwares->map(function($s) {
        return ['id' => (string) $s->id, 'level' => (int) $s->keterangan, 'versi' => $s->versi ?? []];
    })->toArray());
    const softwareMeta = {};
    softwareData.forEach(function(item) {
        softwareMeta[item.id] = {'level': item.level, 'versi': item.versi};
    });
    softwareMeta['lainnya'] = {'level': 0, 'versi': []};

    function getFieldValue(name) {
        const input = document.querySelector('[name="' + name + '"]');
        return input ? input.value : '';
    }

    function escapeJsString(str) {
        return String(str).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    }

    function setCustomSelectOptions(uid, options, selectedValue = '') {
        const input = document.getElementById('val_' + uid);
        const labelEl = document.getElementById('lbl_' + uid);
        const menu = document.getElementById('menu_' + uid);
        if (!input || !labelEl || !menu) return;

        const selected = options.find(o => String(o.value) === String(selectedValue)) ?? options[0] ?? { value: '', label: '' };

        input.value = selected.value;
        labelEl.textContent = selected.label;

        menu.innerHTML = options.map(opt => {
            const isActive = String(opt.value) === String(selected.value);
            const value = escapeJsString(opt.value);
            const text = escapeJsString(opt.label);
            const activeClass = isActive
                ? 'bg-blue-50 text-blue-700 font-bold'
                : 'text-slate-700 font-semibold hover:bg-slate-50 hover:text-slate-950';
            const icon = isActive
                ? '<svg class="h-3.5 w-3.5 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>'
                : '<span class="h-3.5 w-3.5 shrink-0"></span>';

            return '<div onclick="pickCustomSelect(\'' + uid + '\', \'' + value + '\', \'' + text + '\', false)"'
                + ' class="flex items-center gap-2.5 cursor-pointer rounded-xl px-3 py-2 text-sm transition ' + activeClass + '">'
                + icon + opt.label + '</div>';
        }).join('');
    }

    function updateVersiDanCek(selectedVersiOverride = null) {
        // Jika tidak ada parameter, gunakan nilai dari input
        if (selectedVersiOverride === null) {
            selectedVersiOverride = getFieldValue('versi_requested');
        }

        const softId = getFieldValue('software_id');
        const containerSoftLain = document.getElementById('container_software_lain');
        const inputSoftLain = document.getElementById('software_lain');
        let versiOptions = [{ value: '', label: '-- Pilih Versi --' }];
        let selectedVersi = selectedVersiOverride;

        if (softId === 'lainnya') {
            containerSoftLain.classList.remove('hidden');
            inputSoftLain.required = true;
            versiOptions.push({ value: 'lainnya', label: 'Lainnya (Isi manual)' });
            if (selectedVersiOverride === null) selectedVersi = 'lainnya';
        } else {
            containerSoftLain.classList.add('hidden');
            inputSoftLain.required = false;
            inputSoftLain.value = '';

            if (softId && softwareMeta[softId]) {
                (softwareMeta[softId].versi || []).forEach(versi => {
                    versiOptions.push({ value: versi, label: versi });
                });
                versiOptions.push({ value: 'lainnya', label: 'Lainnya (Isi manual)' });
            }
        }

        setCustomSelectOptions('versi_requested', versiOptions, selectedVersi);
        cekVersiLain();
        cekKompatibilitas();
    }

    function cekVersiLain() {
        const versi = getFieldValue('versi_requested');
        const softId = getFieldValue('software_id');
        const containerVersiLain = document.getElementById('container_versi_lain');
        const inputVersiLain = document.getElementById('versi_lain');
        const hintVersiBaru = document.getElementById('hint_versi_baru');

        if (versi === 'lainnya') {
            containerVersiLain.classList.remove('hidden');
            inputVersiLain.required = true;

            if (softId && softId !== 'lainnya') {
                hintVersiBaru.classList.remove('hidden');
            } else {
                hintVersiBaru.classList.add('hidden');
            }
        } else {
            containerVersiLain.classList.add('hidden');
            hintVersiBaru.classList.add('hidden');
            inputVersiLain.required = false;
            inputVersiLain.value = '';
        }
    }

    function cekKompatibilitas() {
        const labId = getFieldValue('laboratorium_id');
        const softId = getFieldValue('software_id');
        const warningBox = document.getElementById('compatibility-warning');
        const submitBtn = document.getElementById('submit_btn');

        warningBox.classList.add('hidden');
        if (submitBtn) submitBtn.disabled = false;

        if (!labId || !softId) {
            return;
        }

        const labLevel = parseInt(labLevels[labId]) || 0;
        const softLevel = softId === 'lainnya' ? 0 : (parseInt(softwareMeta[softId]?.level) || 0);

        if (labLevel > 0 && softLevel > 0 && softLevel > labLevel) {
            warningBox.classList.remove('hidden');

            if (labLevel === 1 && softLevel >= 3) {
                warningBox.className = 'rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800';
                warningBox.innerHTML = '⚠️ <strong>Peringatan Kritis:</strong> Spesifikasi laboratorium tujuan (Level ' + labLevel + ') sangat jauh di bawah syarat minimum software (Level ' + softLevel + '). Instalasi tidak dapat diajukan di ruangan ini.';
                if (submitBtn) submitBtn.disabled = true;
            } else {
                warningBox.className = 'rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800';
                warningBox.innerHTML = '⚠️ <strong>Peringatan Kompatibilitas:</strong> Spesifikasi laboratorium tujuan (Level ' + labLevel + ') lebih rendah dibandingkan level beban software (Level ' + softLevel + '). Instalasi tetap dapat diajukan, namun kinerja software mungkin lambat.';
                if (submitBtn) submitBtn.disabled = false;
            }
        }

        cekInstalasiYangSudahAda();
    }

    function cekInstalasiYangSudahAda() {
        const labId = getFieldValue('laboratorium_id');
        const softId = getFieldValue('software_id');
        const versi = getFieldValue('versi_requested');
        const instalasiWarning = document.getElementById('instalasi-warning');

        if (!labId || !softId || !versi || softId === 'lainnya') {
            if (instalasiWarning) instalasiWarning.classList.add('hidden');
            return;
        }

        fetch('/cek-instalasi?lab_id=' + labId + '&software_id=' + softId + '&versi=' + encodeURIComponent(versi))
            .then(response => response.json())
            .then(data => {
                if (data.sudah_ada) {
                    if (instalasiWarning) {
                        instalasiWarning.classList.remove('hidden');
                        instalasiWarning.innerHTML = '⚠️ <strong>Software Sudah Terinstal:</strong> ' + data.pesan;
                    }
                } else if (instalasiWarning) {
                    instalasiWarning.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error cek instalasi:', error));
    }

    document.addEventListener('DOMContentLoaded', function () {
        const origPick = window.pickCustomSelect;
        window.pickCustomSelect = function (uid, value, label, autosubmit) {
            origPick(uid, value, label, autosubmit);

            if (uid === 'laboratorium_id') {
                cekKompatibilitas();
                cekInstalasiYangSudahAda();
            } else if (uid === 'software_id') {
                updateVersiDanCek();
            } else if (uid === 'versi_requested') {
                cekVersiLain();
                cekInstalasiYangSudahAda();
            }
        };

        if (getFieldValue('software_id')) {
            updateVersiDanCek(@json(old('versi_requested')) || null);
        }
    });
</script>
@endsection