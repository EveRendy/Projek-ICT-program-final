<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'no_induk',
        'nama',
        'email',
        'password',
        'no_hp',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi balik: Seorang Dosen bisa memiliki banyak data Pengajuan
public function pengajuans()
{
    return $this->hasMany(Pengajuan::class, 'user_id');
}

// Relasi balik penugasan: Seorang Admin bisa memiliki banyak tugas Pengajuan
public function tugasInstalasi()
{
    return $this->hasMany(Pengajuan::class, 'tugaskan_admin');
}

// Relasi penanggung jawab: Seorang Admin bisa mengelola beberapa ruang Laboratorium
public function laboratoriums()
{
    return $this->hasMany(Laboratorium::class, 'user_id');
}
}
