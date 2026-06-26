<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hardware extends Model
{
    protected $fillable = ['name', 'vram', 'parent_id', 'type', 'category'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Hardware::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Hardware::class, 'parent_id');
    }
}
