@extends('layouts.app')

@section('content')
@php
    // Helper untuk warna badge status progress instalasi di tabel
    $statusBadge = function ($status) {
        return match (strtolower($status)) {
            'pending', 'menunggu' => 'bg-amber-50 text-amber-700 ring-amber-200 border-amber-200',
            'progress', 'on progress' => 'bg-blue-50 text-blue-700 ring-blue-200 border-blue-200',
            'terinstal', 'installed', 'selesai' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 border-emerald-200',
            'terkendala', 'gagal_terinstal', 'gagal' => 'bg-red-50 text-red-700 ring-red-200 border-red-200',
            default => 'bg-slate-50 text-slate-700 ring-slate-200 border-slate-200',
        };
    };

    // Helper untuk icon status
    $statusIcon = function ($status) {
        return match (strtolower($status)) {
            'pending', 'menunggu' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            'progress', 'on progress' => '<svg class="w-4 h-4 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>',
            'terinstal', 'installed', 'selesai' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
            'terkendala', 'gagal_terinstal', 'gagal' => '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            default => '',
        };
    };
@endphp

<div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    
    @if(session('success'))
        <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700 text-sm font-bold">
            {{ $errors->first() }}
        </div>
    @endif
    
    <div class="mb-8 w-full rounded-2xl bg-blue-950 py-4 text-center shadow-sm">
        <h1 class="text-2xl font-black tracking-widest text-white uppercase">Update Pengerjaan Tugas</h1>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="rounded-xl bg-slate-100 p-3 text-slate-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-950">{{ $summary['total'] ?? 0 }}</p>
                <p class="text-sm font-bold text-slate-950">Total Tugas Saya</p>
                <p class="text-xs text-slate-500">Semua Laboratorium</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-rose-200 bg-white p-4 shadow-sm">
            <div class="rounded-xl bg-rose-50 p-3 text-rose-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-rose-600">{{ $summary['terkendala'] ?? 0 }}</p>
                <p class="text-sm font-bold text-slate-950">Gagal Terinstal</p>
                <p class="text-xs text-slate-500">Terkendala saat instalasi</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-blue-200 bg-white p-4 shadow-sm">
            <div class="rounded-xl bg-blue-50 p-3 text-blue-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-blue-600">{{ $summary['progress'] ?? 0 }}</p>
                <p class="text-sm font-bold text-slate-950">Sedang Diproses</p>
                <p class="text-xs text-slate-500">On Progress</p>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-emerald-200 bg-white p-4 shadow-sm">
            <div class="rounded-xl bg-emerald-50 p-3 text-emerald-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-emerald-600">{{ $summary['selesai'] ?? 0 }}</p>
                <p class="text-sm font-bold text-slate-950">Selesai Terinstal</p>
                <p class="text-xs text-slate-500">Instalasi Sukses</p>
            </div>
        </div>
    </div>

    @php
        $taskList = $tugas ?? collect();
    @endphp

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="hidden border-b border-slate-200 pb-3 sm:grid sm:grid-cols-12 sm:gap-4 px-4 text-sm font-bold text-slate-500">
            <div class="col-span-1">No.</div>
            <div class="col-span-3">Software</div>
            <div class="col-span-2 text-center">Laboratorium</div>
            <div class="col-span-2 text-center">Status Progress</div>
            <div class="col-span-2 text-center">Tgl Penugasan</div>
            <div class="col-span-2 text-right">Aksi</div>
        </div>

        <div class="mt-4 space-y-3">
            @forelse($taskList as $index => $item)
                @php
                    $namaSoftware = $item->software?->nama_software ?? $item->software_lain ?? 'Unknown Software';
                    $versiSoftware = $item->versi_requested ?? $item->versi_lain ?? '-';
                    $status = $item->status_progress ?? 'menunggu';
                    $labName = $item->laboratorium?->no_lab ?? 'Lab -';

                    $rowNumber = is_object($taskList) && method_exists($taskList, 'currentPage')
                        ? ($taskList->currentPage() - 1) * $taskList->perPage() + ($index + 1)
                        : $index + 1;
                @endphp
                <div class="flex flex-col items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 transition-all hover:border-blue-300 hover:shadow-md sm:grid sm:grid-cols-12 sm:items-center">
                    
                    <div class="col-span-4 flex items-center gap-4">
                        <span class="text-lg font-black text-slate-950 sm:w-8">{{ $rowNumber }}</span>
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-50 border border-slate-100 text-blue-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <p class="text-base font-black text-slate-950">{{ $namaSoftware }}</p>
                            <p class="text-xs font-semibold text-slate-500">v.{{ $versiSoftware }}</p>
                        </div>
                    </div>

                    <div class="col-span-2 text-left sm:text-center">
                        <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-sm font-bold text-slate-700">
                            {{ $labName }}
                        </span>
                    </div>

                    <div class="col-span-2 text-left sm:text-center">
                        <span class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-bold ring-1 ring-inset {{ $statusBadge($status) }}">
                            {!! $statusIcon($status) !!}
                            @if($status === 'gagal_terinstal') Gagal Terinstal @else {{ ucfirst($status) }} @endif
                        </span>
                    </div>

                    <div class="col-span-2 text-left sm:text-center">
                        <p class="text-sm font-bold text-slate-700">
                            {{ $item->tgl_penugasan ? \Carbon\Carbon::parse($item->tgl_penugasan)->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>

                    <div class="col-span-2 flex flex-wrap gap-2 justify-start sm:justify-end">
                        <button onclick="openDetailModal({{ json_encode([
                            'software' => $namaSoftware,
                            'versi' => $versiSoftware,
                            'dosen' => $item->dosen?->nama ?? $item->user?->nama ?? 'Dosen Tidak Diketahui',
                            'lab' => $labName,
                            'waktu' => $item->tgl_pengajuan ? \Carbon\Carbon::parse($item->tgl_pengajuan)->translatedFormat('d F Y H:i').' WIB' : '-',
                            'matkul' => $item->mata_kuliah ?? '-',
                            'kelompok' => $item->kelompok_matkul ?? '-',
                            'status' => strtolower($status),
                            'dokumentasi' => $item->dokumentasi ?? '-',
                            'catatan_admin' => $item->catatan_admin ?? '-'
                        ]) }})" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-blue-950 shadow-sm transition hover:bg-slate-50">
                            Detail
                        </button>
                        
                        <button onclick="openUpdateModal('{{ $item->id }}', '{{ $status }}', '{{ $item->dokumentasi ?? '' }}', '{{ $item->catatan_admin ?? '' }}')" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-blue-900">
                            Update
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                    <p class="text-sm font-bold text-slate-500">Belum ada tugas pengerjaan instalasi yang dialokasikan ke Anda.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 flex justify-end">
            @if(is_object($taskList) && method_exists($taskList, 'links'))
                {{ $taskList->links() }}
            @endif
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div id="modal_header_container" class="relative flex items-center justify-center p-6">
            <div class="flex items-center gap-3">
                <div id="modal_icon_container"></div>
                <h3 id="modal_title_text" class="text-xl font-black">Detail Tugas Instalasi</h3>
            </div>
            <button onclick="closeDetailModal()" class="absolute right-6 text-slate-600 hover:text-slate-900">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6 max-h-[70vh] overflow-y-auto">
            <div class="rounded-2xl border border-slate-200">
                <dl class="divide-y divide-slate-100">
                    <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 text-sm">
                        <dt class="font-bold text-slate-700">Nama Software</dt>
                        <dd id="modal_software" class="font-medium text-slate-950 sm:col-span-2">-</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 text-sm">
                        <dt class="font-bold text-slate-700">Versi</dt>
                        <dd id="modal_versi" class="font-medium text-slate-950 sm:col-span-2">-</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 text-sm">
                        <dt class="font-bold text-slate-700">Diajukan oleh</dt>
                        <dd id="modal_dosen" class="font-medium text-slate-950 sm:col-span-2">-</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 text-sm">
                        <dt class="font-bold text-slate-700">Laboratorium</dt>
                        <dd id="modal_lab" class="font-medium text-slate-950 sm:col-span-2">-</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 text-sm">
                        <dt class="font-bold text-slate-700">Waktu Pengajuan</dt>
                        <dd id="modal_waktu" class="font-medium text-slate-950 sm:col-span-2">-</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 text-sm">
                        <dt class="font-bold text-slate-700">Mata Kuliah / Kelompok</dt>
                        <dd id="modal_matkul_kelompok" class="font-medium text-slate-950 sm:col-span-2">-</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 text-sm border-t-2 border-slate-100 bg-slate-50">
                        <dt class="font-bold text-blue-950">Link Dokumentasi</dt>
                        <dd id="modal_dokumentasi" class="font-medium text-slate-950 sm:col-span-2 break-all">-</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-3 text-sm bg-slate-50">
                        <dt class="font-bold text-blue-950">Catatan Pengerjaan</dt>
                        <dd id="modal_catatan_admin" class="font-medium text-slate-950 sm:col-span-2">-</dd>
                    </div>
                </dl>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeDetailModal()" class="rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800 shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div id="updateModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="bg-blue-950 p-6 text-white flex justify-between items-center">
            <h3 class="text-xl font-black">Update Progress Instalasi</h3>
            <button onclick="closeUpdateModal()" class="text-white hover:text-slate-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="updateForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Status Progress</label>
                <select name="status_progress" id="update_status" required class="w-full rounded-xl border border-slate-300 p-2.5 text-sm font-semibold text-slate-950 focus:border-blue-500 focus:ring-blue-500">
                    <option value="progress">On Progress</option>
                    <option value="terinstal">Selesai (Terinstal)</option>
                    <option value="gagal_terinstal">Gagal Terinstal / Terkendala</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Link Dokumentasi Hasil</label>
                <input type="url" name="dokumentasi" id="update_dokumentasi" required 
                    class="w-full rounded-xl border border-slate-300 p-2.5 text-sm text-slate-950 focus:border-blue-500 focus:ring-blue-500" 
                    placeholder="https://sharelink-cloud-atau-drive.com">
                <p class="text-xs text-slate-400 mt-1">*Wajib menyertakan URL bukti instalasi berupa folder drive/cloud.</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Catatan Tambahan Teknis</label>
                <textarea name="catatan_admin" id="update_catatan" rows="3" 
                    class="w-full rounded-xl border border-slate-300 p-2.5 text-sm text-slate-950 focus:border-blue-500 focus:ring-blue-500" 
                    placeholder="Tuliskan kendala atau rincian spesifikasi jika diperlukan..."></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeUpdateModal()" class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-blue-950 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-900 shadow-sm">
                    Simpan Progress
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Logic Modal 1: Detail Tugas
    function openDetailModal(data) {
        document.getElementById('modal_software').innerText = data.software;
        document.getElementById('modal_versi').innerText = 'v.' + data.versi;
        document.getElementById('modal_dosen').innerText = data.dosen;
        document.getElementById('modal_lab').innerText = data.lab;
        document.getElementById('modal_waktu').innerText = data.waktu;
        document.getElementById('modal_matkul_kelompok').innerText = data.matkul + ' (' + data.kelompok + ')';
        
        // Handle Teks link dokumentasi secara dinamis
        const dokElem = document.getElementById('modal_dokumentasi');
        if(data.dokumentasi && data.dokumentasi !== '-') {
            dokElem.innerHTML = `<a href="${data.dokumentasi}" target="_blank" class="text-blue-600 underline font-bold hover:text-blue-800">${data.dokumentasi}</a>`;
        } else {
            dokElem.innerText = '-';
        }
        document.getElementById('modal_catatan_admin').innerText = data.catatan_admin;
        
        const headerContainer = document.getElementById('modal_header_container');
        const titleText = document.getElementById('modal_title_text');
        const iconContainer = document.getElementById('modal_icon_container');
        
        headerContainer.className = "relative flex items-center justify-center p-6 transition-colors duration-200 ";
        titleText.className = "text-xl font-black ";
        
        const iconWaiting = `<svg class="h-8 w-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
        const iconProgress = `<svg class="h-8 w-8 text-blue-700 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>`;
        const iconSuccess = `<svg class="h-8 w-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
        const iconFailed = `<svg class="h-8 w-8 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;

        switch(data.status) {
            case 'pending':
            case 'menunggu':
                headerContainer.classList.add('bg-amber-100');
                titleText.classList.add('text-amber-800');
                iconContainer.innerHTML = iconWaiting;
                break;
            case 'progress':
            case 'on progress':
                headerContainer.classList.add('bg-blue-100');
                titleText.classList.add('text-blue-800');
                iconContainer.innerHTML = iconProgress;
                break;
            case 'terinstal':
            case 'installed':
            case 'selesai':
                headerContainer.classList.add('bg-emerald-100');
                titleText.classList.add('text-emerald-800');
                iconContainer.innerHTML = iconSuccess;
                break;
            case 'terkendala':
            case 'gagal_terinstal':
            case 'gagal':
                headerContainer.classList.add('bg-red-100');
                titleText.classList.add('text-red-800');
                iconContainer.innerHTML = iconFailed;
                break;
            default:
                headerContainer.classList.add('bg-slate-100');
                titleText.classList.add('text-slate-800');
                iconContainer.innerHTML = '';
        }
        
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Logic Modal 2: Form Input Update Progress Pengerjaan
    function openUpdateModal(id, currentStatus, currentDokumentasi, currentCatatan) {
        const form = document.getElementById('updateForm');
        
        // PENTING: Gunakan rute dinamis dari Laravel agar aman dan tidak error 404
        let actionUrl = "{{ route('admin.updateProgressTugas', ':id') }}";
        form.action = actionUrl.replace(':id', id);
        
        // Map status string agar pas dengan value tag <option>
        let mappedStatus = currentStatus.toLowerCase();
        if (mappedStatus === 'menunggu' || mappedStatus === 'pending') {
            mappedStatus = 'progress'; 
        }
        
        document.getElementById('update_status').value = mappedStatus;
        document.getElementById('update_dokumentasi').value = currentDokumentasi;
        document.getElementById('update_catatan').value = currentCatatan;
        
        document.getElementById('updateModal').classList.remove('hidden');
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').classList.add('hidden');
    }
</script>
@endsection