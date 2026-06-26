<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CpuBrand extends Model
{
    protected $fillable = ['name', 'score'];

    public function cpuTypes(): HasMany
    {
        return $this->hasMany(CpuType::class);
    }

    public function cpuGenerations(): HasMany
    {
        return $this->hasMany(CpuGeneration::class);
    }
}
