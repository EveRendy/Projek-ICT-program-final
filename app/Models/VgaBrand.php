<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VgaBrand extends Model
{
    protected $fillable = ['name'];

    public function vgaSeries(): HasMany
    {
        return $this->hasMany(VgaSeries::class);
    }
}

