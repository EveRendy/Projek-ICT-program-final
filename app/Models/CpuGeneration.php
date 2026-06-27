<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpuGeneration extends Model
{
    protected $fillable = ['cpu_brand_id', 'name', 'score'];

    public function cpuBrand(): BelongsTo
    {
        return $this->belongsTo(CpuBrand::class);
    }
}
