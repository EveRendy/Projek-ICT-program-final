<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'tgl_pengajuan',
        'mata_kuliah',
        'kelompok_matkul',
        'user_id',
        'lab_ids',
        'level_akses',
        'software_id',
        'versi_requested',
        'software_lain',
        'versi_lain',
        'status_persetujuan',
        'catatan_spv',
        'tugaskan_admin',
        'tgl_penugasan',
        'status_progress',
        'dokumentasi',
        'catatan_admin',
        'foto_bukti',
        'status_verifikasi',
        'catatan_penolakan_foto',
    ];

    /**Supaya tanggal otomatis dibaca sebagai object date */
    protected $casts = [
        'tgl_pengajuan' => 'date',
        'tgl_penugasan' => 'date',
        'lab_ids'       => 'array', // <-- Ditambahkan agar Laravel otomatis mengubah JSON menjadi Array
    ];
    
    /**
     * ==========================================
     * RELASI ANTAR MODEL / TABEL
     * ==========================================
     */

    // Relasi ke User (Dosen yang membuat pengajuan)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // --- DIUBAH ---
    // Karena lab_ids sekarang berupa array/JSON, kita tidak bisa memakai belongsTo biasa.
    // Fungsi ini akan mengambil semua data Laboratorium yang ID-nya ada di dalam array lab_ids.
    public function getLaboratoriums()
    {
        return Laboratorium::whereIn('id', $this->lab_ids ?? [])->get();
    }
    // --------------

    // Relasi ke Software (Software master yang dipilih, jika ada)
    public function software()
    {
        return $this->belongsTo(Software::class, 'software_id');
    }

    // Relasi ke User (Admin yang ditugaskan untuk melakukan instalasi)
    public function admin()
    {
        return $this->belongsTo(User::class, 'tugaskan_admin');
    }

}