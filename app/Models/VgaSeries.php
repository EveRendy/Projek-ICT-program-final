<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VgaSeries extends Model
{
    protected $fillable = ['vga_brand_id', 'name'];

    public function vgaBrand(): BelongsTo
    {
        return $this->belongsTo(VgaBrand::class);
    }

    public function vgaModels(): HasMany
    {
        return $this->hasMany(VgaModel::class);
    }
}

