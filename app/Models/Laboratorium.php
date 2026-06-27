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
        'is_active',
    ];

    /**
     * Tambahan agar Laravel otomatis mengubah JSON menjadi Array
     */
    protected $casts = [
        'spesifikasi' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Mutator untuk memastikan level selalu antara 1-3
     */
    public function setLevelAttribute($value)
    {
        $level = (int) $value;
        if ($level < 1) {
            $this->attributes['level'] = '1';
        } elseif ($level > 3) {
            $this->attributes['level'] = '3';
        } else {
            $this->attributes['level'] = (string) $level;
        }
    }

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


    public function licenseTrackings()
    {
        return $this->hasMany(LicenseTracking::class);
    }
}