<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'laboratorium_id',
        'software_id',
        'pc_number',
        'license_account',
        'license_password',
        'unique_code',
        'license_type',
        'active_date',
        'expiry_date',
    ];

    protected $casts = [
        'active_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class);
    }

    public function software()
    {
        return $this->belongsTo(Software::class);
    }

    // Helper untuk cek apakah akan kadaluarsa dalam 3 hari
    public function getIsExpiringSoonAttribute()
    {
        if (!$this->expiry_date) return false;
        $now = now()->startOfDay();
        $expiry = $this->expiry_date->startOfDay();
        
        // Hitung selisih hari dari sekarang ke expiry
        $daysUntilExpiry = $now->diffInDays($expiry, false);
        
        // Jika <= 3 hari dan >= 0 (belum kadaluarsa)
        return $daysUntilExpiry <= 3 && $daysUntilExpiry >= 0;
    }

    // Helper untuk cek apakah sudah kadaluarsa
    public function getIsExpiredAttribute()
    {
        if (!$this->expiry_date) return false;
        $now = now()->startOfDay();
        $expiry = $this->expiry_date->startOfDay();
        return $expiry->lt($now);
    }

    // Helper untuk mendapatkan label lisensi
    public function getLicenseTypeLabelAttribute()
    {
        $types = [
            'paid' => 'Berbayar',
            'free' => 'Gratis',
            'opensource' => 'Gratis'
        ];
        return $types[$this->license_type] ?? 'Gratis';
    }

    // Helper untuk mendapatkan warna lisensi
    public function getLicenseTypeColorAttribute()
    {
        $colors = [
            'paid' => 'amber',
            'free' => 'emerald',
            'opensource' => 'emerald'
        ];
        return $colors[$this->license_type] ?? 'emerald';
    }
}
