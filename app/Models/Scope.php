<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scope extends Model
{
    use HasFactory;
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
    public function lines(): HasMany
    {
        return $this->hasMany(Line::class)->orderBy('order');
    }
}
