<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorium extends Model
{
    use HasFactory;

    protected $table = "laboratoriums";
    
    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'no_lab',
        'nama_lab',
        'level',
        'jumlah_pc',
        'spesifikasi', 
        'status', 
    ];

    /**
     * Tambahan agar Laravel otomatis mengubah JSON menjadi Array
     */
    protected $casts = [
        'spesifikasi' => 'array', 
    ];

    // PERBAIKAN: Menambahkan 'no_induk' agar Laravel tahu user_id merujuk ke no_induk di tabel users
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id', 'no_induk');
    }

    // Relasi balik: Satu ruang Laboratorium bisa menerima banyak data Pengajuan
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'laboratorium_id');
    }

    public function instalasis()
    {
        return $this->hasMany(Instalasi::class, 'no_lab', 'no_lab');
    }
}