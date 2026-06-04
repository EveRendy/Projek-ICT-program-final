<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    use HasFactory;

    // Mengunci nama tabel agar tidak otomatis dibaca sebagai 'softwares'
    protected $table = 'software';

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
}