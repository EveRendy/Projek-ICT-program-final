<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Menentukan primary key kustom menggantikan 'id'
    protected $primaryKey = 'no_induk';

    // Menonaktifkan auto-increment karena primary key berbentuk string
    public $incrementing = false;

    // Menentukan tipe data primary key berupa string
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
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

    /**
     * Mutator untuk otomatis membuat huruf pertama menjadi kapital saat menyimpan data nama
     */
    protected function nama(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucwords(strtolower($value)),
        );
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

    // Untuk melacak riwayat instalasi apa saja yang pernah dikerjakan oleh admin ini
    public function riwayatInstalasi()
    {
        return $this->hasMany(Instalasi::class, 'diinstal_oleh', 'no_induk');
    }
}