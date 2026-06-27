<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VgaModel extends Model
{
    protected $fillable = ['vga_series_id', 'name', 'vram', 'score'];

    public function vgaSeries(): BelongsTo
    {
        return $this->belongsTo(VgaSeries::class);
    }
}

