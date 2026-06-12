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
                        @elseif($pengajuan->status_persetujuan == 'disetujui')
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold tracking-wide text-emerald-800">DISETUJUI</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold tracking-wide text-rose-800">DITOLAK</span>
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
                        <strong class="font-bold text-slate-900">Ruang {{ $pengajuan->laboratorium->no_lab ?? '-' }} — {{ $pengajuan->laboratorium->nama_lab }}</strong>
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
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4">
                        <h6 class="mb-3 text-sm font-bold text-emerald-700">✓ Tanggapan Deployment Admin</h6>
                        
                        <div>
                            <small class="mb-1 block text-xs text-slate-500">Catatan Progres Lapangan:</small>
                            <p class="m-0 rounded-lg border border-emerald-100 bg-white p-3 text-sm italic leading-relaxed text-slate-600 shadow-sm">
                                "{{ $pengajuan->catatan_admin ?? 'Master file software sudah dikonfirmasi. Tim teknisi kami sedang melakukan instalasi berkala pada komputer client di laboratorium.' }}"
                            </p>
                        </div>
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
@endsection