<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clause extends Model
{
    public function category() {
        return $this->belongsTo(ClauseCategory::class);
    }
}
