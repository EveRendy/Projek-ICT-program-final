@extends('layouts.app')

@section('content')
<div class="w-full px-4 py-4 sm:px-6 lg:px-8">
    
    <div class="mb-5">
        <a href="{{ route('pengajuan.status') }}" 
           class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-1.5 text-sm font-medium text-slate-600 shadow-sm transition-colors hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            <span>&larr;</span> Kembali ke Status Pengajuan
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        
        <div class="lg:col-span-7">
            <div class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div>
                        <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-blue-600">Spesifikasi Permohonan</span>
                        <h3 class="m-0 text-xl font-black text-slate-900 sm:text-2xl">
                            {{ $pengajuan->software_id ? $pengajuan->software->nama_software : $pengajuan->software_lain }}
                        </h3>
                    </div>
                    
                    <div class="shrink-0">
                        @if($pengajuan->status_persetujuan == 'pending')
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold tracking-wide text-amber-800">PENDING</span>
                        @elseif($pengajuan->status_persetujuan == 'ditolak')
                            <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold tracking-wide text-rose-800">DITOLAK</span>
                        @elseif($pengajuan->status_progress === 'terinstal' && $pengajuan->status_verifikasi === 'disetujui')
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold tracking-wide text-emerald-800">TERINSTAL</span>
                        @elseif($pengajuan->status_progress === 'gagal_terinstal')
                            <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold tracking-wide text-rose-800">GAGAL TERINSTAL</span>
                        @elseif($pengajuan->status_verifikasi === 'menunggu')
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold tracking-wide text-amber-800">MENUNGGU VERIF.</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1.5 text-xs font-bold tracking-wide text-blue-800">DIPROSES</span>
                        @endif
                    </div>
                </div>

                <hr class="my-5 border-t border-slate-100">

                <div class="grid grid-cols-1 gap-5 text-sm sm:grid-cols-2">
                    <div>
                        <span class="mb-1 block text-xs text-slate-500">Mata Kuliah</span>
                        <strong class="font-bold text-slate-900">{{ $pengajuan->mata_kuliah }}</strong>
                    </div>
                    <div>
                        <span class="mb-1 block text-xs text-slate-500">Kelompok / Kelas</span>
                        <strong class="font-bold text-slate-900">{{ $pengajuan->kelompok_matkul }}</strong>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <span class="mb-1 block text-xs text-slate-500">Laboratorium Dituju</span>
                        <strong class="font-bold text-slate-900">
                            @if(isset($laboratoriums) && $laboratoriums->count() > 0)
                                @foreach($laboratoriums as $lab)
                                    {{ $lab->no_lab ?? '-' }}{{ $lab->nama_lab ? ' : ' . $lab->nama_lab : '' }}@if(!$loop->last), @endif
                                @endforeach
                            @else
                                <span class="italic text-slate-500">Tidak ada lab spesifik</span>
                            @endif
                        </strong>
                    </div>
                    
                    <div>
                        <span class="mb-1 block text-xs text-slate-500">Versi Yang Diminta</span>
                        <span class="inline-block rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-xs font-bold text-slate-600">
                            v.{{ $pengajuan->software_id ? $pengajuan->versi_requested : ($pengajuan->versi_lain ?? 'Asli') }}
                        </span>
                    </div>
                    <div>
                        <span class="mb-1 block text-xs text-slate-500">Tanggal Kirim Berkas</span>
                        <span class="font-medium text-slate-600">{{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h5 class="mb-4 text-lg font-bold text-slate-900">Catatan Verifikasi Laboratorium</h5>

                @if($pengajuan->status_persetujuan == 'ditolak')
                    <div class="rounded-xl bg-rose-50 p-4">
                        <h6 class="mb-1 text-sm font-bold text-rose-700">Alasan Penolakan Supervisor:</h6>
                        <p class="mb-4 text-sm leading-relaxed text-rose-900">
                            {{ $pengajuan->catatan_spv ?? 'Mohon maaf, spesifikasi hardware pada lab yang dituju belum memenuhi batas minimum sistem software.' }}
                        </p>
                        
                        <div class="rounded-lg border border-rose-100 bg-white p-4 text-sm text-slate-600 shadow-sm">
                            <strong class="text-amber-600">💡 Rekomendasi Ruang Alternatif:</strong><br>
                            <span class="mt-1 block leading-relaxed">Disarankan untuk mengajukan pemindahan instalasi ke ruang laboratorium komputer pusat dengan spesifikasi RAM dan core prosesor yang lebih memadai untuk modul kuliah ini.</span>
                        </div>
                    </div>
                @endif

                @if($pengajuan->status_persetujuan == 'disetujui')
                    <div class="space-y-4">
                        @php
                            if ($pengajuan->status_progress === 'gagal_terinstal') {
                                $cardClass = 'border-rose-200 bg-rose-50/50';
                                $titleClass = 'text-rose-700';
                                $titleText = '✗ Tanggapan Deployment Admin (Gagal)';
                                $commentBorder = 'border-rose-100';
                                $defaultComment = 'Instalasi gagal dilakukan pada laboratorium tujuan.';
                            } elseif ($pengajuan->status_progress === 'terinstal') {
                                $cardClass = 'border-emerald-200 bg-emerald-50/50';
                                $titleClass = 'text-emerald-700';
                                $titleText = '✓ Tanggapan Deployment Admin (Selesai)';
                                $commentBorder = 'border-emerald-100';
                                $defaultComment = 'Instalasi selesai dikerjakan.';
                            } else {
                                $cardClass = 'border-blue-100 bg-blue-50/50';
                                $titleClass = 'text-blue-800';
                                $titleText = 'Tanggapan Deployment Admin (Dalam Proses)';
                                $commentBorder = 'border-blue-100';
                                $defaultComment = 'Master file software sudah dikonfirmasi. Tim teknisi kami sedang melakukan instalasi berkala pada komputer client di laboratorium.';
                            }
                        @endphp

                        <div class="rounded-xl border {{ $cardClass }} p-4">
                            <h6 class="mb-3 text-sm font-bold {{ $titleClass }}">{{ $titleText }}</h6>
                            
                            <div>
                                <small class="mb-1 block text-xs text-slate-500">Catatan Progres Lapangan:</small>
                                <p class="m-0 rounded-lg border {{ $commentBorder }} bg-white p-3 text-sm italic leading-relaxed text-slate-600 shadow-sm">
                                    "{{ $pengajuan->catatan_admin ?? $defaultComment }}"
                                </p>
                            </div>
                        </div>

                        {{-- Foto bukti instalasi — hanya tampil jika sudah diverifikasi supervisor --}}
                        @if($pengajuan->foto_bukti && $pengajuan->status_verifikasi === 'disetujui')
                            <div class="rounded-xl border border-emerald-200 bg-white p-4 shadow-sm">
                                <h6 class="mb-3 text-sm font-bold text-emerald-700 flex items-center gap-1.5">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    Bukti Foto Instalasi (Terverifikasi)
                                </h6>
                                <button type="button" onclick="toggleFotoModal()" class="block w-full group">
                                    <img src="{{ asset('storage/' . $pengajuan->foto_bukti) }}"
                                         alt="Bukti Foto Instalasi"
                                         class="w-full max-h-56 object-cover rounded-xl border border-emerald-100 shadow-sm group-hover:opacity-90 transition cursor-zoom-in">
                                    <p class="text-center text-xs font-semibold text-slate-400 mt-1.5">Klik untuk perbesar</p>
                                </button>
                            </div>

                            {{-- Modal lihat foto full --}}
                            <div id="modalFotoDetail" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="toggleFotoModal()"></div>
                                <div class="flex min-h-full items-center justify-center p-4">
                                    <div class="relative z-10 max-w-3xl w-full">
                                        <button type="button" onclick="toggleFotoModal()"
                                            class="absolute -top-10 right-0 rounded-xl p-1.5 text-white hover:bg-white/20 transition">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                        <img src="{{ asset('storage/' . $pengajuan->foto_bukti) }}"
                                             alt="Bukti Foto Instalasi"
                                             class="w-full rounded-2xl shadow-2xl border border-white/20">
                                    </div>
                                </div>
                            </div>
                        @elseif($pengajuan->status_progress === 'gagal_terinstal')
                            <div class="rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-xs text-rose-700 font-medium flex items-center gap-2">
                                <svg class="h-4 w-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span>Instalasi dilaporkan gagal. Tim teknisi mengalami kendala teknis saat proses instalasi.</span>
                            </div>
                        @elseif($pengajuan->status_progress === 'progress' || $pengajuan->status_verifikasi === 'menunggu')
                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-xs text-amber-700 font-medium flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
                                Instalasi sedang diproses. Foto bukti akan tersedia setelah instalasi selesai dan diverifikasi supervisor.
                            </div>
                        @endif
                    </div>
                @endif

                @if($pengajuan->status_persetujuan == 'pending')
                    <div class="flex h-full flex-col items-center justify-center py-8 text-center text-slate-500">
                        <p class="m-0 px-4 text-sm leading-relaxed">
                            Berkas permohonan instalasi Anda telah masuk ke sistem dan saat ini sedang berada dalam antrean pemeriksaan berkas kurikulum oleh <strong class="text-slate-700">Supervisor Laboratorium ICT</strong>.
                        </p>
                    </div>
                @endif

            </div>
        </div>
        
    </div>

</div>

<script>
    function toggleFotoModal() {
        const modal = document.getElementById('modalFotoDetail');
        if (!modal) return;
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
</script>
@endsection