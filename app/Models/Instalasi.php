<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instalasi extends Model
{
    use HasFactory;

    protected $table = 'instalasi';

    protected $fillable = [
        'id_software',
        'versi_terinstall',
        'no_lab',
        'status_lisensi',
        'tgl_aktif',
        'tgl_expired',
        'diinstal_oleh',
    ];

    // Otomatis mengubah format tgl_aktif dan tgl_expired menjadi objek Carbon untuk mempermudah manipulasi tanggal
    protected $casts = [
        'tgl_aktif' => 'date',
        'tgl_expired' => 'date',
    ];

    /**
     * Relasi ke model Software (Many-to-One)
     */
    public function software()
    {
        return $this->belongsTo(Software::class, 'id_software', 'id_software');
    }

    /**
     * Relasi ke model Laboratorium (Many-to-One)
     */
    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class, 'no_lab', 'no_lab');
    }

    /**
     * Relasi ke model User / Admin yang menginstal (Many-to-One)
     */
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'diinstal_oleh', 'no_induk');
    }
}