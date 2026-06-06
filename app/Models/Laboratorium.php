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
        'level',
        'jumlah_pc',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi balik: Satu ruang Laboratorium bisa menerima banyak data Pengajuan
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'laboratorium_id');
    }
}