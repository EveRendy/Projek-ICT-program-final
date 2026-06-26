@props([
    'name',           // nama field untuk form (hidden input)
    'id'    => null,  // id unik, default dari name
    'label' => null,  // label teks yang tampil di tombol saat belum ada pilihan
    'options' => [],  // array of ['value' => ..., 'label' => ..., 'data' => [...]]
    'selected' => '', // nilai yang sedang terpilih
    'autosubmit' => false, // true = langsung submit form saat pilih
    'required' => false, // true = hidden input wajib diisi
    'class' => '',    // class tambahan untuk wrapper
    'onchange' => null, // custom onchange handler
])

@php
    $uid      = $id ?? $name . '_' . uniqid();
    $btnId    = 'btn_' . $uid;
    $menuId   = 'menu_' . $uid;
    $inputId  = 'val_' . $uid;
    $labelId  = 'lbl_' . $uid;

    // Cari label dari nilai terpilih
    $activeLabel = $label;
    foreach ($options as $opt) {
        if ((string)($opt['value'] ?? '') === (string)$selected) {
            $activeLabel = $opt['label'];
            break;
        }
    }
@endphp

<div class="relative dropdown-wrapper {{ $class }}" data-dropdown="{{ $uid }}">
    {{-- Hidden input untuk submit form --}}
    <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" value="{{ $selected }}" @if($required) required @endif>

    {{-- Tombol trigger --}}
    <button type="button" id="{{ $btnId }}"
        onclick="toggleCustomSelect('{{ $uid }}')"
        class="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer text-left">
        <span id="{{ $labelId }}" class="truncate pr-2">{{ $activeLabel ?? $label }}</span>
        <svg id="chevron_{{ $uid }}" class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-200"
             fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </button>

    {{-- Dropdown menu --}}
    <div id="{{ $menuId }}"
         class="absolute left-0 z-40 mt-1.5 hidden w-full min-w-[160px] rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl max-h-60 overflow-y-auto">
        @foreach ($options as $opt)
            @php
                $isActive = (string)($opt['value'] ?? '') === (string)$selected;
                $dataAttributes = '';
                if (isset($opt['data']) && is_array($opt['data'])) {
                    foreach ($opt['data'] as $key => $value) {
                        $dataAttributes .= " data-{$key}='{$value}'";
                    }
                }
            @endphp
            <div
                {!! $dataAttributes !!}
                onclick="pickCustomSelect('{{ $uid }}', '{{ addslashes($opt['value'] ?? '') }}', '{{ addslashes($opt['label'] ?? '') }}', {{ $autosubmit ? 'true' : 'false' }}{{ $onchange ? ", '{$onchange}'" : '' }})"
                class="flex items-center gap-2.5 cursor-pointer rounded-xl px-3 py-2 text-sm transition
                       {{ $isActive ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 font-semibold hover:bg-slate-50 hover:text-slate-950' }}">
                @if($isActive)
                    <svg class="h-3.5 w-3.5 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                @else
                    <span class="h-3.5 w-3.5 shrink-0"></span>
                @endif
                {{ $opt['label'] }}
            </div>
        @endforeach
    </div>
</div>
