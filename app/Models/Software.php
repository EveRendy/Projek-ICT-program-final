<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    use HasFactory;

    // Mengunci nama tabel agar tidak otomatis dibaca sebagai 'softwares'
    protected $table = 'software';

     /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_software',
        'nama_software',
        'versi',
        'keterangan',
    ];

    /**
     * Fitur Casting Laravel: Otomatis mengubah JSON database menjadi Array PHP
     */
    protected $casts = [
        'versi' => 'array',
    ];

    // Relasi balik: Satu jenis Software bisa tercatat di banyak data Pengajuan
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'software_id');
    }

    public function instalasis()
    {
        return $this->hasMany(Instalasi::class, 'id_software', 'id_software');
    }
}