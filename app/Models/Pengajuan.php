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
        'laboratorium_id',
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

    // Relasi ke Laboratorium (Lab tujuan instalasi)
    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class, 'laboratorium_id');
    }

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