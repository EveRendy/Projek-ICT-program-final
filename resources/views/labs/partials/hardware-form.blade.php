@php
    $hardware = config('lab_hardware');
    $selectedSpecs = $selectedSpecs ?? old('spesifikasi_hardware', []);

    $selectedBrand = null;
    if (in_array('CPU Intel', $selectedSpecs) || collect($selectedSpecs)->contains(fn ($s) => str_starts_with($s, 'Intel '))) {
        $selectedBrand = 'intel';
    } elseif (in_array('CPU AMD', $selectedSpecs) || collect($selectedSpecs)->contains(fn ($s) => str_starts_with($s, 'AMD '))) {
        $selectedBrand = 'amd';
    }

    $savedVgaModel = '';
    foreach ($hardware['vga_options'] as $vgaName => $lvl) {
        if (in_array($vgaName, $selectedSpecs)) {
            $savedVgaModel = $vgaName;
            break;
        }
    }
    $hasVga = in_array('VGA Tambahan', $selectedSpecs) || in_array('VGA Dedicated', $selectedSpecs) || $savedVgaModel !== '';

    $selectedBrand = old('cpu_brand', $selectedBrand);
    $selectedCpuTier = old('cpu_tier');
    $selectedCpuDetail = old('cpu_detail');
    $selectedRam = old('ram_size');

    if (!$selectedCpuTier) {
        foreach ($selectedSpecs as $spec) {
            if (isset($hardware['intel_tiers'][$spec]) || isset($hardware['amd_tiers'][$spec])) {
                $selectedCpuTier = $spec;
            }
        }
        // Kompatibilitas data lama
        if (!$selectedCpuTier) {
            $legacyMap = [
                'Core i3 / Ryzen 3' => ['intel' => 'Intel Core i3', 'amd' => 'AMD Ryzen 3'],
                'Core i5 / Ryzen 5' => ['intel' => 'Intel Core i5', 'amd' => 'AMD Ryzen 5'],
                'Core i7 / Ryzen 7' => ['intel' => 'Intel Core i7', 'amd' => 'AMD Ryzen 7'],
                'Core i9 / Ryzen 9' => ['intel' => 'Intel Core i9', 'amd' => 'AMD Ryzen 9'],
            ];
            foreach ($selectedSpecs as $spec) {
                if (isset($legacyMap[$spec])) {
                    $selectedCpuTier = $selectedBrand
                        ? ($legacyMap[$spec][$selectedBrand] ?? null)
                        : null;
                    if ($selectedCpuTier) break;
                }
            }
        }
    }

    if (!$selectedCpuDetail) {
        foreach ($selectedSpecs as $spec) {
            if (isset($hardware['intel_generations'][$spec]) || isset($hardware['amd_series'][$spec])) {
                $selectedCpuDetail = $spec;
            }
        }
    }
    if (!$selectedRam) {
        foreach ($selectedSpecs as $spec) {
            if (isset($hardware['ram_options'][$spec])) {
                $selectedRam = $spec;
            }
        }
    }
@endphp

{{-- INDIKATOR LEVEL OTOMATIS --}}
<div class="rounded-xl bg-slate-50 p-4 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
        <label class="block text-sm font-semibold text-slate-700">Kalkulasi Level Spesifikasi</label>
        <p class="text-xs text-slate-500 mt-0.5">Level dihitung otomatis setelah merk, tipe prosesor, generasi/seri, dan RAM terisi.</p>
    </div>
    <input type="hidden" id="level_input" name="level" value="{{ old('level', $labLevel ?? '') }}">
    <div id="level_display" class="inline-flex shrink-0 items-center rounded-lg bg-slate-100 border border-slate-200 px-4 py-2 text-sm font-bold text-slate-400 transition-colors duration-300">
        Belum Lengkap
    </div>
</div>

<hr class="border-slate-100">

{{-- 1. MERK PROSESOR --}}
<div class="space-y-3">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        1. Merk Prosesor <span class="text-xs text-slate-400 font-normal lowercase">(wajib pilih satu)</span>
    </label>
    <div class="grid grid-cols-2 gap-3 max-w-md">
        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
            <input type="radio" name="cpu_brand_radio" value="intel" id="brand_intel"
                {{ $selectedBrand === 'intel' ? 'checked' : '' }}
                class="h-5 w-5 border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
            <div>
                <span class="text-sm font-bold text-slate-800">Intel</span>
                <span class="block text-xs text-slate-500">Core / Core Ultra</span>
            </div>
        </label>
        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50/50">
            <input type="radio" name="cpu_brand_radio" value="amd" id="brand_amd"
                {{ $selectedBrand === 'amd' ? 'checked' : '' }}
                class="h-5 w-5 border-slate-300 text-red-600 focus:ring-2 focus:ring-red-500/20">
            <div>
                <span class="text-sm font-bold text-slate-800">AMD</span>
                <span class="block text-xs text-slate-500">Ryzen Series</span>
            </div>
        </label>
    </div>
    <input type="hidden" id="cpu_brand_hidden" name="cpu_brand" value="" disabled>
</div>

{{-- 2a. TIPE INTEL --}}
<div id="section_intel_tier" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        2. Tipe Prosesor Intel <span class="text-xs text-slate-400 font-normal lowercase">(wajib pilih satu)</span>
    </label>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2">
        @foreach($hardware['intel_tiers'] as $tierLabel => $tierLevel)
            <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-3 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50/40">
                <input type="radio" name="cpu_tier" value="{{ $tierLabel }}"
                    data-level="{{ $tierLevel }}" data-group="cpu_tier" data-brand="intel"
                    {{ $selectedCpuTier === $tierLabel ? 'checked' : '' }}
                    class="spec-radio h-4 w-4 border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
                <span class="text-xs font-bold text-slate-700">{{ str_replace('Intel ', '', $tierLabel) }}</span>
            </label>
        @endforeach
    </div>
</div>

{{-- 2b. TIPE AMD --}}
<div id="section_amd_tier" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        2. Tipe Prosesor AMD <span class="text-xs text-slate-400 font-normal lowercase">(wajib pilih satu)</span>
    </label>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 max-w-2xl">
        @foreach($hardware['amd_tiers'] as $tierLabel => $tierLevel)
            <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-3 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-red-400 has-[:checked]:bg-red-50/40">
                <input type="radio" name="cpu_tier" value="{{ $tierLabel }}"
                    data-level="{{ $tierLevel }}" data-group="cpu_tier" data-brand="amd"
                    {{ $selectedCpuTier === $tierLabel ? 'checked' : '' }}
                    class="spec-radio h-4 w-4 border-slate-300 text-red-600 focus:ring-2 focus:ring-red-500/20">
                <span class="text-xs font-bold text-slate-700">{{ str_replace('AMD ', '', $tierLabel) }}</span>
            </label>
        @endforeach
    </div>
</div>

{{-- 3a. GENERASI INTEL --}}
<div id="section_intel_gen" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        3. Generasi Intel <span class="text-xs text-slate-400 font-normal lowercase">(wajib pilih satu)</span>
    </label>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
        @foreach($hardware['intel_generations'] as $genLabel => $genLevel)
            <label class="flex items-center gap-2 rounded-xl border border-slate-200 p-2.5 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50/40">
                <input type="radio" name="cpu_detail" value="{{ $genLabel }}"
                    data-level="{{ $genLevel }}" data-group="cpu_detail" data-brand="intel"
                    {{ $selectedCpuDetail === $genLabel ? 'checked' : '' }}
                    class="spec-radio h-4 w-4 border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
                <span class="text-xs font-bold text-slate-700">{{ str_replace('Intel ', '', $genLabel) }}</span>
            </label>
        @endforeach
    </div>
</div>

{{-- 3b. SERI AMD --}}
<div id="section_amd_series" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        3. Seri Generasi AMD Ryzen <span class="text-xs text-slate-400 font-normal lowercase">(wajib pilih satu)</span>
    </label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @foreach($hardware['amd_series'] as $seriesLabel => $seriesLevel)
            <label class="flex items-center gap-2.5 rounded-xl border border-slate-200 p-3 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-red-400 has-[:checked]:bg-red-50/40">
                <input type="radio" name="cpu_detail" value="{{ $seriesLabel }}"
                    data-level="{{ $seriesLevel }}" data-group="cpu_detail" data-brand="amd"
                    {{ $selectedCpuDetail === $seriesLabel ? 'checked' : '' }}
                    class="spec-radio h-4 w-4 border-slate-300 text-red-600 focus:ring-2 focus:ring-red-500/20">
                <span class="text-xs font-bold text-slate-700">{{ $seriesLabel }}</span>
            </label>
        @endforeach
    </div>
</div>

{{-- 4. RAM --}}
<div id="section_ram" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        4. Kapasitas RAM <span class="text-xs text-slate-400 font-normal lowercase">(wajib pilih satu)</span>
    </label>
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        @foreach($hardware['ram_options'] as $ramLabel => $ramLevel)
            <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50/40">
                <input type="radio" name="ram_size" value="{{ $ramLabel }}"
                    data-level="{{ $ramLevel }}" data-group="ram_size"
                    {{ $selectedRam === $ramLabel ? 'checked' : '' }}
                    class="spec-radio h-5 w-5 border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500/20">
                <span class="text-sm font-medium text-slate-700">{{ str_replace('RAM ', '', $ramLabel) }}</span>
            </label>
        @endforeach
    </div>
</div>

{{-- 5. VGA TAMBAHAN --}}
<div id="section_vga" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        5. VGA Tambahan <span class="text-xs text-slate-400 font-normal lowercase">(opsional)</span>
    </label>
    <div class="max-w-md">
        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 cursor-pointer hover:bg-blue-50 transition bg-blue-50/30 has-[:checked]:border-blue-400">
            <input type="checkbox" id="vga_tambahan_checkbox" name="spesifikasi_hardware[]" value="VGA Tambahan"
                {{ $hasVga ? 'checked' : '' }}
                class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
            <span class="text-sm font-bold text-blue-700">Terdapat VGA Tambahan (Dedicated GPU)</span>
        </label>
    </div>

    <div id="sub_vga_container" class="hidden space-y-3 rounded-2xl border border-blue-100 bg-blue-50/50 p-5 transition-all duration-300">
        <label class="block text-sm font-bold text-blue-900">Pilih Model VGA (NVIDIA / AMD)</label>
        
        <div class="space-y-4 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
            <!-- NVIDIA Section -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-green-700 mb-2">NVIDIA GeForce</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach($hardware['vga_options'] as $vgaName => $vgaLevel)
                        @if(str_contains(strtolower($vgaName), 'geforce'))
                            <label class="flex items-center gap-2 rounded-xl border border-white bg-white/60 p-2.5 cursor-pointer hover:bg-white transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50/80 shadow-sm">
                                <input type="radio" name="spesifikasi_hardware[]" value="{{ $vgaName }}" data-level="{{ $vgaLevel }}"
                                    {{ $savedVgaModel === $vgaName ? 'checked' : '' }}
                                    class="sub-vga-radio h-4 w-4 border-slate-300 text-green-600 focus:ring-2 focus:ring-green-500/20" disabled>
                                <span class="text-xs font-semibold text-slate-700">{{ $vgaName }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- AMD Section -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-red-700 mb-2">AMD Radeon</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach($hardware['vga_options'] as $vgaName => $vgaLevel)
                        @if(str_contains(strtolower($vgaName), 'radeon'))
                            <label class="flex items-center gap-2 rounded-xl border border-white bg-white/60 p-2.5 cursor-pointer hover:bg-white transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50/80 shadow-sm">
                                <input type="radio" name="spesifikasi_hardware[]" value="{{ $vgaName }}" data-level="{{ $vgaLevel }}"
                                    {{ $savedVgaModel === $vgaName ? 'checked' : '' }}
                                    class="sub-vga-radio h-4 w-4 border-slate-300 text-red-600 focus:ring-2 focus:ring-red-500/20" disabled>
                                <span class="text-xs font-semibold text-slate-700">{{ $vgaName }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <p class="text-xs text-blue-700/70 pt-2 border-t border-blue-200/50 mt-3">Daftar kartu grafis dari NVIDIA (GeForce) dan AMD (Radeon).</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const brandIntel = document.getElementById('brand_intel');
    const brandAmd = document.getElementById('brand_amd');
    const brandHidden = document.getElementById('cpu_brand_hidden');
    const sectionIntelTier = document.getElementById('section_intel_tier');
    const sectionAmdTier = document.getElementById('section_amd_tier');
    const sectionIntel = document.getElementById('section_intel_gen');
    const sectionAmd = document.getElementById('section_amd_series');
    const sectionRam = document.getElementById('section_ram');
    const sectionVga = document.getElementById('section_vga');
    const vgaCheckbox = document.getElementById('vga_tambahan_checkbox');
    const subVgaContainer = document.getElementById('sub_vga_container');
    const subVgaRadios = document.querySelectorAll('.sub-vga-radio');
    const levelInput = document.getElementById('level_input');
    const levelDisplay = document.getElementById('level_display');

    const specRadios = document.querySelectorAll('.spec-radio');

    function getSelectedBrand() {
        if (brandIntel.checked) return 'intel';
        if (brandAmd.checked) return 'amd';
        return null;
    }

    function getCpuTierRadio() {
        const brand = getSelectedBrand();
        if (!brand) return null;
        return document.querySelector(`input[data-group="cpu_tier"][data-brand="${brand}"]:checked`);
    }

    function getCpuDetailRadio() {
        const brand = getSelectedBrand();
        if (!brand) return null;
        return document.querySelector(`input[data-group="cpu_detail"][data-brand="${brand}"]:checked`);
    }

    function syncBrandHidden() {
        const brand = getSelectedBrand();
        if (brand) {
            brandHidden.value = brand;
            brandHidden.disabled = false;
        } else {
            brandHidden.value = '';
            brandHidden.disabled = true;
        }
    }

    function resetAfterBrandChange() {
        document.querySelectorAll('input[data-group="cpu_tier"], input[data-group="cpu_detail"], input[data-group="ram_size"]').forEach(r => {
            r.checked = false;
        });
    }

    function resetAfterTierChange() {
        document.querySelectorAll(`input[data-group="cpu_detail"][data-brand="${getSelectedBrand()}"]`).forEach(r => r.checked = false);
        document.querySelectorAll('input[data-group="ram_size"]').forEach(r => r.checked = false);
    }

    function updateSections() {
        const brand = getSelectedBrand();
        const cpuTier = getCpuTierRadio();
        const cpuDetail = getCpuDetailRadio();
        const ramSelected = document.querySelector('input[data-group="ram_size"]:checked');

        sectionIntelTier.classList.toggle('hidden', brand !== 'intel');
        sectionAmdTier.classList.toggle('hidden', brand !== 'amd');
        sectionIntel.classList.toggle('hidden', brand !== 'intel' || !cpuTier);
        sectionAmd.classList.toggle('hidden', brand !== 'amd' || !cpuTier);
        sectionRam.classList.toggle('hidden', !cpuDetail);
        sectionVga.classList.toggle('hidden', !ramSelected);

        syncBrandHidden();
        handleVgaKondisional();
        hitungLevelOtomatis();
    }

    function handleVgaKondisional() {
        if (vgaCheckbox.checked) {
            subVgaContainer.classList.remove('hidden');
            subVgaRadios.forEach(r => r.disabled = false);
        } else {
            subVgaContainer.classList.add('hidden');
            subVgaRadios.forEach(r => {
                r.disabled = true;
                r.checked = false;
            });
        }
    }

    function hitungLevelOtomatis() {
        const brand     = getSelectedBrand();
        const cpuTier   = getCpuTierRadio();
        const cpuDetail = getCpuDetailRadio();
        const ramSelected = document.querySelector('input[data-group="ram_size"]:checked');
        const selectedVga = document.querySelector('.sub-vga-radio:checked');

        // VGA wajib dipilih jika checkbox VGA dicentang
        const isVgaValid = !vgaCheckbox.checked || selectedVga;

        if (!brand || !cpuTier || !cpuDetail || !ramSelected || !isVgaValid) {
            levelInput.value = '';
            levelDisplay.textContent = 'Belum Lengkap';
            levelDisplay.className = 'inline-flex shrink-0 items-center rounded-lg bg-slate-100 border border-slate-200 px-4 py-2 text-sm font-bold text-slate-400 transition-colors duration-300';
            return;
        }

        /**
         * SCORING MATRIX — realistis berdasarkan kombinasi komponen
         *
         * Skor per komponen (dari data-level di HTML, sesuai config lab_hardware.php):
         *   CPU Tier  : i3/Ryzen3=0 | i5/Ryzen5=1 | i7/Ryzen7=2 | i9/Ryzen9=3 | Ultra7=3 | Ultra9=4
         *   Generasi  : Gen 3-4=0 | Gen 5-7=1 | Gen 8-10=2 | Gen 11=3 | Gen 12-14=4 | Core Ultra=5
         *   RAM       : 4GB=0 | 8GB=1 | 16GB=2 | 32GB=3 | 64GB=4
         *   VGA       : entry/GT=0 | mid GTX 950-1650=1 | upper-mid GTX 1660=2 | RTX=3
         *
         * Threshold:
         *   Skor  0–3  → Level 1 (Rendah)
         *   Skor  4–8  → Level 2 (Menengah)
         *   Skor  9+   → Level 3 (Tinggi)
         *
         * Contoh:
         *   i7 Gen 3 RAM 4GB       = 2+0+0 =  2 → Level 1 ✓
         *   i5 Gen 8 RAM 8GB       = 1+2+1 =  4 → Level 2 ✓
         *   i7 Gen 12 RAM 16GB     = 2+4+2 =  8 → Level 2 ✓
         *   i9 Gen 13 RAM 32GB     = 3+4+3 = 10 → Level 3 ✓
         *   i9 Gen 13 RAM 32GB+RTX = 3+4+3+3=13 → Level 3 ✓
         */
        const scoreCpuTier   = parseInt(cpuTier.dataset.level)   || 0;
        const scoreGen       = parseInt(cpuDetail.dataset.level)  || 0;
        const scoreRam       = parseInt(ramSelected.dataset.level) || 0;
        const scoreVga       = (vgaCheckbox.checked && selectedVga)
                               ? (parseInt(selectedVga.dataset.level) || 0)
                               : 0;

        const totalScore = scoreCpuTier + scoreGen + scoreRam + scoreVga;

        // Tentukan level dari total skor
        let finalLevel;
        if (totalScore <= 3) {
            finalLevel = 1;
        } else if (totalScore <= 8) {
            finalLevel = 2;
        } else {
            finalLevel = 3;
        }

        const levels = {
            1: {
                text: 'Level 1 — Spesifikasi Rendah (Office / Browsing)',
                cls : 'inline-flex shrink-0 items-center rounded-lg bg-red-50 border border-red-200 px-4 py-2 text-sm font-bold text-red-700 transition-colors duration-300',
            },
            2: {
                text: 'Level 2 — Spesifikasi Menengah (Programming / Desain)',
                cls : 'inline-flex shrink-0 items-center rounded-lg bg-amber-50 border border-amber-200 px-4 py-2 text-sm font-bold text-amber-700 transition-colors duration-300',
            },
            3: {
                text: 'Level 3 — Spesifikasi Tinggi (Multimedia / Engineering)',
                cls : 'inline-flex shrink-0 items-center rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-2 text-sm font-bold text-emerald-700 transition-colors duration-300',
            },
        };

        // Debug info di tooltip (opsional, membantu saat QA)
        levelDisplay.title = `Skor: CPU(${scoreCpuTier}) + Gen(${scoreGen}) + RAM(${scoreRam}) + VGA(${scoreVga}) = ${totalScore}`;

        levelInput.value        = String(finalLevel);
        levelDisplay.textContent = levels[finalLevel].text;
        levelDisplay.className   = levels[finalLevel].cls;
    }

    [brandIntel, brandAmd].forEach(el => {
        el.addEventListener('change', function () {
            resetAfterBrandChange();
            updateSections();
        });
    });

    specRadios.forEach(r => {
        r.addEventListener('change', function () {
            if (this.dataset.group === 'cpu_tier') {
                resetAfterTierChange();
            }
            if (this.dataset.group === 'cpu_detail') {
                document.querySelectorAll('input[data-group="ram_size"]').forEach(x => x.checked = false);
            }
            updateSections();
        });
    });

    vgaCheckbox.addEventListener('change', function () {
        handleVgaKondisional();
        hitungLevelOtomatis();
    });

    subVgaRadios.forEach(r => {
        r.addEventListener('change', hitungLevelOtomatis);
    });

    document.querySelector('form').addEventListener('submit', function (e) {
        syncBrandHidden();
        const brand = getSelectedBrand();
        const cpuTier = getCpuTierRadio();
        const cpuDetail = getCpuDetailRadio();
        const ramSelected = document.querySelector('input[data-group="ram_size"]:checked');

        if (!brand || !cpuTier || !cpuDetail || !ramSelected || !levelInput.value) {
            e.preventDefault();
            alert('Lengkapi merk prosesor, tipe (Core i/Ryzen), generasi/seri, dan kapasitas RAM terlebih dahulu.');
            return;
        }

        if (vgaCheckbox.checked && !document.querySelector('.sub-vga-radio:checked')) {
            e.preventDefault();
            alert('Pilih model VGA tambahan atau hapus centang VGA Tambahan.');
        }
    });

    updateSections();
});
</script>
