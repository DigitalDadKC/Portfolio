<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;
    protected $casts = [
        'start_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    public function scopes(): HasMany
    {
        return $this->hasMany(Scope::class);
    }

    // public function startDate(): Attribute
    // {
    //     return new Attribute(
    //         get: fn($value) => Carbon::createFromFormat('Y-m-d', $value)->format('M d, Y'),
    //         set: fn($value) => Carbon::parse($value)->format('Y-m-d')
    //     );
    // }
}
