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
    
    // Extract Series, Model, Suffix, and VRAM from $vgaSeriesFull
    // Example: 'RTX 4060 Ti 8GB'
    $vgaSeriesParts = explode(' ', $vgaSeriesFull);
    $vgaVram = '';
    $vgaSuffix = 'Polos';
    $vgaSeries = $vgaSeriesFull;
    $vgaModel = ''; // New variable for VGA model
    
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
        
        // Now, extract VGA model: take the rest after the series? Wait, series is like "RTX 4000 Series", but let's look at how CPU does it. Wait the series for VGA is like "RTX 4000 Series (Ada Lovelace)", so model would be like "RTX 4060". Let's just take the remaining parts as model, or separate them? Wait, for CPU, we have gen, model, suffix. For VGA, let's make it series, model, suffix, vram! Let's modify: let's make vgaSeries the series (from radio button), and vgaModel the model input!
        $vgaModel = implode(' ', $vgaSeriesParts);
        $vgaSeries = ''; // Since series comes from radio buttons, not from the full string
    }
    
    $vgaSeries = old('vga_series', $vgaSeries);
    $vgaModel = old('vga_model', $vgaModel); // Get old vga_model or from parsed specs
    $vgaSuffix = old('vga_suffix', $vgaSuffix);
    $vgaVram = old('vga_vram', $vgaVram);

    $ramNumber = (int) filter_var($ramSize, FILTER_SANITIZE_NUMBER_INT);
    
    // Prepare FULL hierarchical data for JS
    $cpuData = [];
    foreach ($hardware['cpu_brands'] as $brand) {
        $cpuData[$brand->name] = $brand->children->map(function($child) {
            return ['name' => $child->name];
        })->toArray();
    }

    $vgaData = [];
    foreach ($hardware['vga_brands'] as $brand) {
        $vgaData[$brand->name] = $brand->children->map(function($child) {
            return ['name' => $child->name];
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

        {{-- Model, Seri, Suffix & VRAM VGA --}}
        <div id="vga_model_section" class="space-y-2 hidden">
            <label class="text-sm font-bold text-blue-900">Model, Seri, Suffix dan VRAM</label>
            <div class="flex flex-col gap-2 max-w-2xl">
                {{-- Row with 3 columns: Seri, Model, Suffix --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    {{-- Kolom 1: Seri VGA (Dropdown) --}}
                    <div>
                        <label class="text-xs font-bold text-blue-900 mb-1 block">Seri VGA</label>
                        <select id="vga_series" name="vga_series"
                                class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 bg-white">
                            <option value="">Pilih Seri</option>
                        </select>
                    </div>
                    {{-- Kolom 2: Model VGA --}}
                    <div>
                        <label class="text-xs font-bold text-blue-900 mb-1 block">Model VGA</label>
                        <input type="text" id="vga_model" name="vga_model" value="{{ $vgaModel }}"
                               placeholder="Contoh: 4060 atau 6700"
                               class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    {{-- Kolom 3: Suffix VGA --}}
                    <div>
                        <label class="text-xs font-bold text-blue-900 mb-1 block">Suffix VGA</label>
                        <select id="vga_suffix" name="vga_suffix"
                                class="w-full rounded-xl border border-slate-200 px-3 py-3 text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 bg-white">
                            <option value="Polos" {{ $vgaSuffix === 'Polos' ? 'selected' : '' }}>Polos</option>
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
                    </div>
                </div>
                {{-- VRAM VGA --}}
                <div class="mt-2">
                    <label class="text-xs font-bold text-blue-900 mb-1 block">VRAM VGA (GB)</label>
                    <div class="flex items-center gap-2 max-w-xs">
                        <input type="number" id="vga_vram" name="vga_vram" value="{{ $vgaVram }}"
                               min="2" step="1"
                               class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 bg-white">
                        <span class="text-sm font-bold text-slate-700">GB</span>
                    </div>
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
    const vgaModelSection = document.getElementById('vga_model_section');
    const vgaModelInput = document.getElementById('vga_model'); // New VGA model input
    const vgaSuffixSelect = document.getElementById('vga_suffix');
    const vgaVramInput = document.getElementById('vga_vram');
    const levelInput = document.getElementById('level_input');
    const levelDisplay = document.getElementById('level_display');
    const ramSizeNumber = document.getElementById('ram_size_number');
    const ramSizeHidden = document.getElementById('ram_size');
    const vgaSeriesSelect = document.getElementById('vga_series');

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
                <input type="radio" name="cpu_gen" value="${gen.name}"
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
    vgaModelInput.addEventListener('input', hitungLevelOtomatis);
    vgaVramInput.addEventListener('input', hitungLevelOtomatis);
    vgaSuffixSelect.addEventListener('change', hitungLevelOtomatis);

    // Render VGA series into dropdown
    function renderVgaSeries(brand) {
        const seriesList = vgaData[brand] || [];
        
        // Clear and rebuild options
        vgaSeriesSelect.innerHTML = '<option value="">Pilih Seri</option>' + 
            seriesList.map(series => `
                <option value="${series.name}" ${selectedVgaSeries === series.name ? 'selected' : ''}>${series.name}</option>
            `).join('');
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
            vgaModelSection.classList.add('hidden');
            selectedVgaBrand = null;
            selectedVgaSeries = null;
            vgaSuffixSelect.value = 'Polos';
            vgaSeriesSelect.value = '';
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
        const ramNum = parseInt(ramSizeNumber.value) || 0;

        // 1. First check RAM: if less than 8GB → ALWAYS Level 1
        if (ramNum < 8) {
            finalLevel = 1;
        } else {
            // 2. Determine CPU level
            let cpuLevel = 1;

            // Parse Intel generation: "4th Gen", "1st Gen", etc.
            const intelGenMatch = selectedGen.match(/(\d+)(st|nd|rd|th) Gen/i);
            if (intelGenMatch) {
                const gen = parseInt(intelGenMatch[1]);
                cpuLevel = gen <= 5 ? 1 : 2;
            } else if (selectedGen.includes('Ryzen')) {
                // For AMD Ryzen: check series
                if (selectedGen.match(/Ryzen (1000|2000) Series/i)) {
                    // Ryzen 1000 & 2000 Series → Level 1
                    cpuLevel = 1;
                } else {
                    // Ryzen 3000+ → Level 2
                    cpuLevel = 2;
                }
            } else {
                // For other CPU types: default to level 1 (Core 2 Duo, Athlon, Phenom, FX, A-Series)
                cpuLevel = 1;
            }

            const hasVga = vgaCheckbox.checked;

            if (!hasVga) {
                finalLevel = Math.min(cpuLevel, 2);
            } else {
                // 3. Determine VGA level based on VRAM
                let vgaLevel = 1;
                let useCpuOnly = false;
                const vgaVram = parseInt(vgaVramInput?.value) || 0;
                
                if (vgaVram > 0) {
                    if (vgaVram <= 2) {
                        // VRAM ≤ 2GB: follow CPU level only
                        useCpuOnly = true;
                    } else if (vgaVram <= 4) {
                        vgaLevel = 2;
                    } else if (vgaVram >= 6) {
                        vgaLevel = 3;
                    } else {
                        // 5GB VRAM: default to level 2
                        vgaLevel = 2;
                    }
                } else {
                    // If no VRAM specified: default to level 2
                    vgaLevel = 2;
                }

                // 4. Final level
                let tempLevel;
                if (useCpuOnly) {
                    tempLevel = cpuLevel;
                } else {
                    tempLevel = Math.max(cpuLevel, vgaLevel);
                }
                
                // 5. If CPU level is 1, max overall level is 2
                const maxLevel = cpuLevel === 1 ? 2 : 3;
                finalLevel = Math.min(tempLevel, maxLevel);
            }
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
    
    cpuModelInput.addEventListener('input', function() {
        hitungLevelOtomatis(); // Normal function
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
            renderVgaSeries(selectedVgaBrand);
            vgaModelSection.classList.remove('hidden');
            selectedVgaSeries = null;
            vgaVramInput.value = '';
            vgaSuffixSelect.value = 'Polos';
            vgaSeriesSelect.value = '';
            updateVgaSuffixOptgroups();
            hitungLevelOtomatis();
        });
    });

    // Add listener for vga series dropdown change
    vgaSeriesSelect.addEventListener('change', function() {
        selectedVgaSeries = this.value;
        hitungLevelOtomatis();
    });

    // Initial update
    updateSections();

    // Restore VGA state if editing
    if (vgaBrandRadios.length > 0 && selectedVgaBrand) {
        const selectedVgaBrandEl = Array.from(vgaBrandRadios).find(r => r.value === selectedVgaBrand);
        if (selectedVgaBrandEl) {
            selectedVgaBrandEl.checked = true;
            renderVgaSeries(selectedVgaBrand);
            vgaModelSection.classList.remove('hidden');
            
            if (selectedVgaSeries) {
                vgaSeriesSelect.value = selectedVgaSeries;
            }
            
            updateVgaSuffixOptgroups();
        }
    }
});
</script>
