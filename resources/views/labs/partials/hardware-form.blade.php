@php
    // In the new simplified spec array format:
    // 0 => CPU Brand, 1 => Gen, 2 => Model Full (e.g. 'i7-8700 K'), 3 => RAM Size
    $cpuBrand = old('cpu_brand', $selectedSpecs[0] ?? '');
    $cpuBrand = str_replace('CPU ', '', $cpuBrand);
    $cpuGen = old('cpu_gen', $selectedSpecs[1] ?? '');
    
    // Extract Model and Suffix
    $cpuModelFull = old('cpu_model_full', $selectedSpecs[2] ?? '');
    $cpuModelParts = explode(' ', $cpuModelFull);
    $cpuSuffix = 'Polos';
    $cpuModel = $cpuModelFull;
    if (count($cpuModelParts) > 1) {
        $lastPart = end($cpuModelParts);
        $possibleSuffixes = ['K', 'F', 'KF', 'KS', 'X', 'XT', 'X3D', 'G', 'GE', 'HX', 'H', 'U', 'P'];
        if (in_array(strtoupper($lastPart), $possibleSuffixes)) {
            $cpuSuffix = strtoupper($lastPart);
            array_pop($cpuModelParts);
            $cpuModel = implode(' ', $cpuModelParts);
        }
    }
    // Check old form inputs
    $cpuModel = old('cpu_model', $cpuModel);
    $cpuSuffix = old('cpu_suffix', $cpuSuffix);

    $ramSize = old('ram_size', $selectedSpecs[3] ?? '');
    if (empty($ramSize) && isset($selectedSpecs[5])) {
        // Fallback for old data structure
        $ramSize = $selectedSpecs[5];
    }
    $hasVga = old('has_vga', in_array('VGA Tambahan', $selectedSpecs ?? []));
    
    // VGA is at the end of the array. Find 'VGA Tambahan'
    $vgaIndex = array_search('VGA Tambahan', $selectedSpecs ?? []);
    $vgaBrand = '';
    $vgaSeriesFull = '';
    if ($vgaIndex !== false) {
        $vgaBrand = $selectedSpecs[$vgaIndex + 1] ?? '';
        $vgaSeriesFull = $selectedSpecs[$vgaIndex + 2] ?? '';
    }
    
    $vgaBrand = old('vga_brand', $vgaBrand);
    
    // Extract Series, Suffix, and VRAM from $vgaSeriesFull
    // Example: 'RTX 4060 Ti 8GB'
    $vgaSeriesParts = explode(' ', $vgaSeriesFull);
    $vgaVram = '';
    $vgaSuffix = 'Polos';
    $vgaSeries = $vgaSeriesFull;
    
    if (count($vgaSeriesParts) > 1) {
        $lastPart = end($vgaSeriesParts);
        if (preg_match('/^(\d+)GB$/i', $lastPart, $matches)) {
            $vgaVram = $matches[1];
            array_pop($vgaSeriesParts); // Remove VRAM part
        }
        
        if (count($vgaSeriesParts) > 1) {
            $lastPart = end($vgaSeriesParts);
            $possibleVgaSuffixes = ['Ti', 'Super', 'Ti Super', 'XT', 'XTX', 'GRE'];
            
            // Check 'Ti Super'
            if (count($vgaSeriesParts) > 2) {
                $lastTwo = $vgaSeriesParts[count($vgaSeriesParts)-2] . ' ' . $lastPart;
                if (in_array($lastTwo, $possibleVgaSuffixes)) {
                    $vgaSuffix = $lastTwo;
                    array_pop($vgaSeriesParts);
                    array_pop($vgaSeriesParts);
                }
            }
            
            if ($vgaSuffix === 'Polos' && in_array($lastPart, $possibleVgaSuffixes)) {
                $vgaSuffix = $lastPart;
                array_pop($vgaSeriesParts);
            }
        }
        $vgaSeries = implode(' ', $vgaSeriesParts);
    }
    
    $vgaSeries = old('vga_series', $vgaSeries);
    $vgaSuffix = old('vga_suffix', $vgaSuffix);
    $vgaVram = old('vga_vram', $vgaVram);

    $ramNumber = (int) filter_var($ramSize, FILTER_SANITIZE_NUMBER_INT);
    
    // Prepare FULL hierarchical data for JS
    $cpuData = [];
    foreach ($hardware['cpu_brands'] as $brand) {
        $cpuData[$brand->name] = $brand->children->map(function($child) {
            return ['name' => $child->name, 'score' => $child->base_score ?? 0];
        })->toArray();
    }
    
    $vgaData = [];
    foreach ($hardware['vga_brands'] as $brand) {
        $vgaData[$brand->name] = $brand->children->map(function($child) {
            return ['name' => $child->name, 'score' => $child->base_score ?? 0];
        })->toArray();
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
    <div class="grid grid-cols-{{ count($hardware['cpu_brands']) > 2 ? 3 : 2 }} gap-3 max-w-md">
        @foreach ($hardware['cpu_brands'] as $brand)
            <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                <input type="radio" name="cpu_brand" value="{{ $brand->name }}" id="cpu_{{ $brand->name }}"
                       {{ $cpuBrand === $brand->name ? 'checked' : '' }}
                       class="cpu-brand-radio h-5 w-5 border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
                <div>
                    <span class="text-sm font-bold text-slate-800">{{ $brand->name }}</span>
                </div>
            </label>
        @endforeach
    </div>
</div>

{{-- 2. GENERASI PROSESOR --}}
<div id="section_cpu_gen" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        2. Generasi Prosesor <span class="text-xs text-slate-400 font-normal lowercase">(wajib pilih satu)</span>
    </label>
    <div id="cpu_gen_container" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
        {{-- Generations will be populated by JS --}}
    </div>
</div>

{{-- 3. MODEL PROSESOR --}}
<div id="section_cpu_model" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        3. Model Prosesor <span class="text-xs text-slate-400 font-normal lowercase">(ketik model & pilih suffix)</span>
    </label>
    <div class="flex flex-col gap-2 max-w-lg">
        <div class="flex gap-2 w-full">
            <input type="text" id="cpu_model" name="cpu_model" value="{{ $cpuModel }}" autocomplete="off"
                   placeholder="Contoh: i7-8700 atau Ryzen 5 3600"
                   class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
            
            <select id="cpu_suffix" name="cpu_suffix"
                class="w-1/3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 bg-slate-50">
            <option value="Polos" {{ $cpuSuffix === 'Polos' ? 'selected' : '' }}>Polos (Tanpa Suffix)</option>
            <optgroup label="Intel" id="optgroup_intel">
                <option value="K" {{ $cpuSuffix === 'K' ? 'selected' : '' }}>K</option>
                <option value="F" {{ $cpuSuffix === 'F' ? 'selected' : '' }}>F</option>
                <option value="KF" {{ $cpuSuffix === 'KF' ? 'selected' : '' }}>KF</option>
                <option value="KS" {{ $cpuSuffix === 'KS' ? 'selected' : '' }}>KS</option>
                <option value="T" {{ $cpuSuffix === 'T' ? 'selected' : '' }}>T</option>
            </optgroup>
            <optgroup label="AMD" id="optgroup_amd">
                <option value="X" {{ $cpuSuffix === 'X' ? 'selected' : '' }}>X</option>
                <option value="XT" {{ $cpuSuffix === 'XT' ? 'selected' : '' }}>XT</option>
                <option value="X3D" {{ $cpuSuffix === 'X3D' ? 'selected' : '' }}>X3D</option>
                <option value="G" {{ $cpuSuffix === 'G' ? 'selected' : '' }}>G</option>
                <option value="GE" {{ $cpuSuffix === 'GE' ? 'selected' : '' }}>GE</option>
            </optgroup>
        </select>
        </div>
        <div id="cpu_typo_suggestion" class="hidden mt-1 ml-1">
            <div class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 border border-amber-200 px-3 py-2 text-xs text-amber-700 font-medium">
                <span>💡 Mungkin maksud Anda:</span>
                <button type="button" id="cpu_typo_btn" class="font-bold text-amber-900 underline hover:text-amber-600 transition focus:outline-none"></button>?
            </div>
        </div>
    </div>
</div>

{{-- 6. RAM (Number Input) --}}
<div id="section_ram" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        6. Kapasitas RAM <span class="text-xs text-slate-400 font-normal lowercase">(wajib pilih satu)</span>
    </label>
    <div class="flex items-center gap-2 max-w-xs">
        <input type="number" name="ram_size_number" id="ram_size_number"
               value="{{ $ramNumber ?: 8 }}"
               min="4" step="4"
               class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
        <span class="text-sm font-bold text-slate-700">GB</span>
        <input type="hidden" name="ram_size" id="ram_size" value="{{ $ramSize ?: 'RAM 8GB' }}">
    </div>
</div>

{{-- 7. VGA TAMBAHAN --}}
<div id="section_vga" class="space-y-3 hidden">
    <label class="block text-sm font-bold text-slate-800 uppercase tracking-wide">
        7. VGA Tambahan <span class="text-xs text-slate-400 font-normal lowercase">(opsional)</span>
    </label>
    <div class="max-w-md">
        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 cursor-pointer hover:bg-blue-50 transition has-[:checked]:border-blue-400 bg-blue-50/30">
            <input type="checkbox" id="vga_tambahan_checkbox" name="has_vga" value="1"
                   {{ $hasVga ? 'checked' : '' }}
                   class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
            <span class="text-sm font-bold text-blue-700">Terdapat VGA Tambahan (Dedicated GPU)</span>
        </label>
    </div>

    {{-- SUB SECTION VGA: Merek, Seri, Model --}}
    <div id="sub_vga_container" class="hidden space-y-3 rounded-2xl border border-blue-100 bg-blue-50/50 p-5 transition-all duration-300">
        {{-- Merek VGA --}}
        <div class="space-y-2">
            <label class="text-sm font-bold text-blue-900">Merek VGA</label>
            <div class="grid grid-cols-3 gap-2">
                @foreach ($hardware['vga_brands'] as $brand)
                    <label class="flex items-center gap-2 rounded-xl border bg-white border-white p-2 cursor-pointer hover:bg-blue-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="radio" name="vga_brand" value="{{ $brand->name }}"
                               {{ $vgaBrand === $brand->name ? 'checked' : '' }}
                               class="vga-brand-radio h-4 w-4 border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        <span class="text-xs font-bold text-slate-700">{{ $brand->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Seri VGA --}}
        <div id="vga_series_section" class="space-y-2 hidden">
            <label class="text-sm font-bold text-blue-900">Seri VGA</label>
            <div id="vga_series_container" class="grid grid-cols-2 md:grid-cols-3 gap-2">
                {{-- Series will be populated by JS --}}
            </div>
        </div>

        {{-- Suffix & VRAM VGA --}}
        <div id="vga_model_section" class="space-y-2 hidden">
            <label class="text-sm font-bold text-blue-900">VRAM dan Suffix</label>
            <div class="flex flex-col sm:flex-row gap-2 max-w-lg">
                <select id="vga_suffix" name="vga_suffix"
                        class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 bg-white">
                    <option value="Polos" {{ $vgaSuffix === 'Polos' ? 'selected' : '' }}>Polos (Tanpa Suffix)</option>
                    <optgroup label="NVIDIA" id="vga_optgroup_nvidia">
                        <option value="Ti" {{ $vgaSuffix === 'Ti' ? 'selected' : '' }}>Ti</option>
                        <option value="Super" {{ $vgaSuffix === 'Super' ? 'selected' : '' }}>Super</option>
                        <option value="Ti Super" {{ $vgaSuffix === 'Ti Super' ? 'selected' : '' }}>Ti Super</option>
                    </optgroup>
                    <optgroup label="AMD" id="vga_optgroup_amd">
                        <option value="XT" {{ $vgaSuffix === 'XT' ? 'selected' : '' }}>XT</option>
                        <option value="XTX" {{ $vgaSuffix === 'XTX' ? 'selected' : '' }}>XTX</option>
                        <option value="GRE" {{ $vgaSuffix === 'GRE' ? 'selected' : '' }}>GRE</option>
                    </optgroup>
                </select>
                
                <div class="flex-1 flex items-center gap-2">
                    <input type="number" id="vga_vram" name="vga_vram" value="{{ $vgaVram }}"
                           placeholder="VRAM" min="1" step="1"
                           class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    <span class="text-sm font-bold text-slate-700">GB</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const cpuBrandRadios = document.querySelectorAll('.cpu-brand-radio');
    const sectionCpuGen = document.getElementById('section_cpu_gen');
    const cpuGenContainer = document.getElementById('cpu_gen_container');
    const sectionCpuModel = document.getElementById('section_cpu_model');
    const cpuModelInput = document.getElementById('cpu_model');
    const cpuSuffixSelect = document.getElementById('cpu_suffix');
    const sectionRam = document.getElementById('section_ram');
    const sectionVga = document.getElementById('section_vga');
    const vgaCheckbox = document.getElementById('vga_tambahan_checkbox');
    const subVgaContainer = document.getElementById('sub_vga_container');
    const vgaBrandRadios = document.querySelectorAll('.vga-brand-radio');
    const vgaSeriesSection = document.getElementById('vga_series_section');
    const vgaSeriesContainer = document.getElementById('vga_series_container');
    const vgaModelSection = document.getElementById('vga_model_section');
    const vgaSuffixSelect = document.getElementById('vga_suffix');
    const vgaVramInput = document.getElementById('vga_vram');
    const levelInput = document.getElementById('level_input');
    const levelDisplay = document.getElementById('level_display');
    const ramSizeNumber = document.getElementById('ram_size_number');
    const ramSizeHidden = document.getElementById('ram_size');

    // Hierarchical data from backend
    const cpuData = @json($cpuData);
    const vgaData = @json($vgaData);

    // State
    let selectedBrand = {!! $cpuBrand ? "'$cpuBrand'" : 'null' !!};
    let selectedGen = {!! $cpuGen ? "'$cpuGen'" : 'null' !!};
    let selectedVgaBrand = {!! $vgaBrand ? "'$vgaBrand'" : 'null' !!};
    let selectedVgaSeries = {!! $vgaSeries ? "'$vgaSeries'" : 'null' !!};

    // Update RAM hidden input
    function updateRamSize() {
        ramSizeHidden.value = 'RAM ' + ramSizeNumber.value + 'GB';
        hitungLevelOtomatis();
    }

    ramSizeNumber.addEventListener('input', updateRamSize);

    // Render CPU generations
    function renderCpuGens(brand) {
        const gens = cpuData[brand] || [];
        cpuGenContainer.innerHTML = gens.map(gen => `
            <label class="cpu-gen-option flex items-center gap-2 rounded-xl border border-slate-200 p-2.5 cursor-pointer hover:bg-slate-50 transition has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50/40">
                <input type="radio" name="cpu_gen" value="${gen.name}" data-score="${gen.score}"
                       ${selectedGen === gen.name ? 'checked' : ''}
                       class="cpu-gen-radio h-4 w-4 border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
                <span class="text-xs font-bold text-slate-700">${gen.name}</span>
            </label>
        `).join('');

        // Reattach event listeners
        document.querySelectorAll('.cpu-gen-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                selectedGen = this.value;
                updateSections();
            });
        });
    }

    // Listener for CPU model text inputs
    // Event listener for input is now combined with fuzzy matching logic below
    cpuSuffixSelect.addEventListener('change', hitungLevelOtomatis);
    vgaVramInput.addEventListener('input', hitungLevelOtomatis);
    vgaSuffixSelect.addEventListener('change', hitungLevelOtomatis);

    // Render VGA series
    function renderVgaSeries(brand) {
        const seriesList = vgaData[brand] || [];
        vgaSeriesContainer.innerHTML = seriesList.map(series => `
            <label class="vga-series-option flex items-center gap-2 rounded-xl bg-white border border-white p-2 cursor-pointer hover:bg-blue-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                <input type="radio" name="vga_series" value="${series.name}" data-score="${series.score}"
                       ${selectedVgaSeries === series.name ? 'checked' : ''}
                       class="vga-series-radio h-4 w-4 border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/20">
                <span class="text-xs font-bold text-slate-700">${series.name}</span>
            </label>
        `).join('');

        // Reattach event listeners
        document.querySelectorAll('.vga-series-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                selectedVgaSeries = this.value;
                vgaModelSection.classList.remove('hidden');
                hitungLevelOtomatis();
            });
        });
    }

    // Update Sections
    function updateSections() {
        const selectedBrandEl = document.querySelector('.cpu-brand-radio:checked');
        if (selectedBrandEl) {
            selectedBrand = selectedBrandEl.value;
            sectionCpuGen.classList.remove('hidden');
            renderCpuGens(selectedBrand);
            
            // Show/Hide Suffix Optgroups
            const optgroupIntel = document.getElementById('optgroup_intel');
            const optgroupAmd = document.getElementById('optgroup_amd');
            if (selectedBrand.toLowerCase() === 'intel') {
                if(optgroupIntel) { optgroupIntel.style.display = ''; optgroupIntel.hidden = false; Array.from(optgroupIntel.children).forEach(o => o.hidden = false); optgroupIntel.disabled = false; }
                if(optgroupAmd) { optgroupAmd.style.display = 'none'; optgroupAmd.hidden = true; Array.from(optgroupAmd.children).forEach(o => o.hidden = true); optgroupAmd.disabled = true; }
            } else {
                if(optgroupIntel) { optgroupIntel.style.display = 'none'; optgroupIntel.hidden = true; Array.from(optgroupIntel.children).forEach(o => o.hidden = true); optgroupIntel.disabled = true; }
                if(optgroupAmd) { optgroupAmd.style.display = ''; optgroupAmd.hidden = false; Array.from(optgroupAmd.children).forEach(o => o.hidden = false); optgroupAmd.disabled = false; }
            }
            
            // Reset suffix if currently selected option is hidden
            const selectedOption = cpuSuffixSelect.options[cpuSuffixSelect.selectedIndex];
            if (selectedOption && (selectedOption.hidden || (selectedOption.parentElement && selectedOption.parentElement.disabled))) {
                cpuSuffixSelect.value = 'Polos';
            }

            const selectedGenEl = document.querySelector('.cpu-gen-radio:checked');
            if (selectedGenEl) {
                selectedGen = selectedGenEl.value;
                sectionCpuModel.classList.remove('hidden');
                sectionRam.classList.remove('hidden');
                sectionVga.classList.remove('hidden');
            } else {
                sectionCpuModel.classList.add('hidden');
                sectionRam.classList.add('hidden');
                sectionVga.classList.add('hidden');
            }
        } else {
            sectionCpuGen.classList.add('hidden');
            sectionCpuModel.classList.add('hidden');
            sectionRam.classList.add('hidden');
            sectionVga.classList.add('hidden');
        }

        handleVgaKondisional();
        hitungLevelOtomatis();
    }

    function handleVgaKondisional() {
        if (vgaCheckbox.checked) {
            subVgaContainer.classList.remove('hidden');
        } else {
            subVgaContainer.classList.add('hidden');
            vgaBrandRadios.forEach(r => r.checked = false);
            document.querySelectorAll('.vga-series-radio').forEach(r => r.checked = false);
            vgaSeriesSection.classList.add('hidden');
            vgaModelSection.classList.add('hidden');
            selectedVgaBrand = null;
            selectedVgaSeries = null;
            vgaVramInput.value = '';
            vgaSuffixSelect.value = 'Polos';
            updateVgaSuffixOptgroups();
        }
    }

    function updateVgaSuffixOptgroups() {
        const optNvidia = document.getElementById('vga_optgroup_nvidia');
        const optAmd = document.getElementById('vga_optgroup_amd');
        if (!selectedVgaBrand) {
            if(optNvidia) { optNvidia.style.display = 'none'; optNvidia.hidden = true; Array.from(optNvidia.children).forEach(o => o.hidden = true); optNvidia.disabled = true; }
            if(optAmd) { optAmd.style.display = 'none'; optAmd.hidden = true; Array.from(optAmd.children).forEach(o => o.hidden = true); optAmd.disabled = true; }
            return;
        }
        
        const brandLow = selectedVgaBrand.toLowerCase();
        if (brandLow === 'nvidia') {
            if(optNvidia) { optNvidia.style.display = ''; optNvidia.hidden = false; Array.from(optNvidia.children).forEach(o => o.hidden = false); optNvidia.disabled = false; }
            if(optAmd) { optAmd.style.display = 'none'; optAmd.hidden = true; Array.from(optAmd.children).forEach(o => o.hidden = true); optAmd.disabled = true; }
        } else if (brandLow === 'amd') {
            if(optNvidia) { optNvidia.style.display = 'none'; optNvidia.hidden = true; Array.from(optNvidia.children).forEach(o => o.hidden = true); optNvidia.disabled = true; }
            if(optAmd) { optAmd.style.display = ''; optAmd.hidden = false; Array.from(optAmd.children).forEach(o => o.hidden = false); optAmd.disabled = false; }
        } else {
            // Intel VGA or other
            if(optNvidia) { optNvidia.style.display = 'none'; optNvidia.hidden = true; Array.from(optNvidia.children).forEach(o => o.hidden = true); optNvidia.disabled = true; }
            if(optAmd) { optAmd.style.display = 'none'; optAmd.hidden = true; Array.from(optAmd.children).forEach(o => o.hidden = true); optAmd.disabled = true; }
        }

        const selectedOption = vgaSuffixSelect.options[vgaSuffixSelect.selectedIndex];
        if (selectedOption && (selectedOption.hidden || (selectedOption.parentElement && selectedOption.parentElement.disabled))) {
            vgaSuffixSelect.value = 'Polos';
        }
    }

    function hitungLevelOtomatis() {
        if (!selectedBrand || !selectedGen || !cpuModelInput.value || !ramSizeNumber.value) {
            levelInput.value = '';
            levelDisplay.textContent = 'Belum Lengkap';
            levelDisplay.className = 'inline-flex shrink-0 items-center rounded-lg bg-slate-100 border border-slate-200 px-4 py-2 text-sm font-bold text-slate-400 transition-colors duration-300';
            return;
        }

        let finalLevel = 1;

        // 1. Get CPU Score
        let cpuScore = 0;
        const selectedCpuRadio = document.querySelector('input[name="cpu_gen"]:checked');
        if (selectedCpuRadio) {
            cpuScore = parseInt(selectedCpuRadio.getAttribute('data-score')) || 0;
        }

        // 2. Get VGA Score
        let vgaScore = 0;
        if (vgaCheckbox.checked && selectedVgaSeries) {
            const selectedVgaRadio = document.querySelector('input[name="vga_series"]:checked');
            if (selectedVgaRadio) {
                vgaScore = parseInt(selectedVgaRadio.getAttribute('data-score')) || 0;
                let vgaVramNum = parseInt(vgaVramInput.value) || 0;
                vgaScore += vgaVramNum;
            }
        }

        // 3. Get RAM Score
        let ramScore = parseInt(ramSizeNumber.value) || 0;

        let totalScore = cpuScore + vgaScore + ramScore;

        if (totalScore < 40) {
            finalLevel = 1;
        } else if (totalScore <= 80) {
            finalLevel = 2;
        } else {
            finalLevel = 3;
        }

        const levels = {
            1: { text: 'Level 1 — Spesifikasi Rendah (Office / Browsing)', cls: 'inline-flex shrink-0 items-center rounded-lg bg-red-50 border border-red-200 px-4 py-2 text-sm font-bold text-red-700 transition-colors duration-300' },
            2: { text: 'Level 2 — Spesifikasi Menengah (Programming / Desain)', cls: 'inline-flex shrink-0 items-center rounded-lg bg-amber-50 border border-amber-200 px-4 py-2 text-sm font-bold text-amber-700 transition-colors duration-300' },
            3: { text: 'Level 3 — Spesifikasi Tinggi (Multimedia / Engineering)', cls: 'inline-flex shrink-0 items-center rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-2 text-sm font-bold text-emerald-700 transition-colors duration-300' },
        };

        levelInput.value = String(finalLevel);
        levelDisplay.textContent = levels[finalLevel].text;
        levelDisplay.className = levels[finalLevel].cls;
    }

    // Event Listeners
    
    // Fuzzy Matching for CPU Typo Recommendation
    const typoSuggestion = document.getElementById('cpu_typo_suggestion');
    const typoBtn = document.getElementById('cpu_typo_btn');
    let cpuModelsDict = [];

    // Load Dictionary
    fetch('/data/cpu_models.json')
        .then(res => res.json())
        .then(data => { cpuModelsDict = data; })
        .catch(err => console.error("Could not load CPU models dictionary", err));

    function levenshtein(a, b) {
        const matrix = [];
        for (let i = 0; i <= b.length; i++) matrix[i] = [i];
        for (let j = 0; j <= a.length; j++) matrix[0][j] = j;
        for (let i = 1; i <= b.length; i++) {
            for (let j = 1; j <= a.length; j++) {
                if (b.charAt(i-1) == a.charAt(j-1)) {
                    matrix[i][j] = matrix[i-1][j-1];
                } else {
                    matrix[i][j] = Math.min(matrix[i-1][j-1] + 1, Math.min(matrix[i][j-1] + 1, matrix[i-1][j] + 1));
                }
            }
        }
        return matrix[b.length][a.length];
    }

    function isModelInGeneration(modelName, generationName) {
        if (!generationName) return true;
        let genNameLow = generationName.toLowerCase();
        let modelLow = modelName.toLowerCase();
        
        let match = generationName.match(/(\d+)th Gen/i);
        if (match) {
            let genNum = match[1];
            let regex = new RegExp(`[\\-\\s]?${genNum}\\d{2,3}`, 'i');
            return regex.test(modelName);
        }
        if (genNameLow.includes('core ultra')) return modelLow.includes('ultra');

        match = generationName.match(/Ryzen\s+(\d)000/i);
        if (match) {
            let genNum = match[1];
            let regex = new RegExp(`[\\-\\s]?${genNum}\\d{3}`, 'i');
            return regex.test(modelName);
        }
        
        if (genNameLow.includes('threadripper')) return modelLow.includes('threadripper');
        if (genNameLow.includes('athlon')) return modelLow.includes('athlon');
        if (genNameLow.includes('pentium')) return modelLow.includes('pentium');
        if (genNameLow.includes('celeron')) return modelLow.includes('celeron');

        return true;
    }

    let typoTimeout = null;
    cpuModelInput.addEventListener('input', function() {
        hitungLevelOtomatis(); // Normal function
        
        clearTimeout(typoTimeout);
        const val = this.value.trim();
        typoSuggestion.classList.add('hidden'); // Hide immediately while typing

        if (val.length < 4 || cpuModelsDict.length === 0) return;

        typoTimeout = setTimeout(() => {
            let bestMatch = '';
            let minDistance = 999;
            const valLower = val.toLowerCase();
            
            // Filter dictionary based on selected generation and brand
            const filteredDict = cpuModelsDict.filter(m => {
                let modelLow = m.toLowerCase();
                if (selectedBrand) {
                    if (selectedBrand.toLowerCase() === 'intel' && (modelLow.includes('ryzen') || modelLow.includes('athlon') || modelLow.includes('threadripper'))) return false;
                    if (selectedBrand.toLowerCase() === 'amd' && (modelLow.includes('i3') || modelLow.includes('i5') || modelLow.includes('i7') || modelLow.includes('i9') || modelLow.includes('core') || modelLow.includes('pentium') || modelLow.includes('celeron'))) return false;
                }
                return isModelInGeneration(m, selectedGen);
            });
            
            for(let model of filteredDict) {
                let modelLow = model.toLowerCase();
                if(modelLow === valLower) {
                    return; // Exact match found, no suggestion needed
                }
                
                let currentMinDist = 999;
                
                // 1. Full string Levenshtein
                let distFull = levenshtein(valLower, modelLow);
                if (distFull < currentMinDist) currentMinDist = distFull;

                // 2. Substring match (e.g. user typed "5600x", model is "ryzen 5 5600x")
                if(modelLow.includes(valLower) && valLower.length >= 3) {
                    // Give priority to substring match
                    currentMinDist = 0.5; // Very close
                }
                
                // 3. Match against the last part only (e.g. "5600X" from "Ryzen 5 5600X" or "13900K" from "i9-13900K")
                let parts = modelLow.split(/[\s\-]+/);
                let lastPart = parts[parts.length - 1];
                let distLastPart = levenshtein(valLower, lastPart);
                if (distLastPart < currentMinDist) currentMinDist = distLastPart;

                // Update global min distance
                if(currentMinDist < minDistance) {
                    minDistance = currentMinDist;
                    bestMatch = model;
                }
            }

            if(minDistance > 0 && minDistance <= 3) {
                typoBtn.textContent = bestMatch;
                typoSuggestion.classList.remove('hidden');
            }
        }, 600); // 600ms debounce
    });

    typoBtn.addEventListener('click', function() {
        cpuModelInput.value = this.textContent;
        typoSuggestion.classList.add('hidden');
        hitungLevelOtomatis();
    });
    cpuBrandRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            selectedBrand = this.value;
            selectedGen = null;
            updateSections();
        });
    });
    vgaCheckbox.addEventListener('change', () => {
        updateSections();
    });

    vgaBrandRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            selectedVgaBrand = this.value;
            vgaSeriesSection.classList.remove('hidden');
            renderVgaSeries(selectedVgaBrand);
            vgaModelSection.classList.add('hidden');
            selectedVgaSeries = null;
            vgaVramInput.value = '';
            vgaSuffixSelect.value = 'Polos';
            updateVgaSuffixOptgroups();
            hitungLevelOtomatis();
        });
    });

    // Initial update
    updateSections();

    // Restore VGA state if editing
    if (vgaBrandRadios.length > 0 && selectedVgaBrand) {
        const selectedVgaBrandEl = Array.from(vgaBrandRadios).find(r => r.value === selectedVgaBrand);
        if (selectedVgaBrandEl) {
            selectedVgaBrandEl.checked = true;
            vgaSeriesSection.classList.remove('hidden');
            renderVgaSeries(selectedVgaBrand);

            if (selectedVgaSeries) {
                vgaModelSection.classList.remove('hidden');
            }
            updateVgaSuffixOptgroups();
        }
    }
});
</script>
