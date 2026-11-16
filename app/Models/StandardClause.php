<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandardClause extends Model
{
    public function category() {
        return $this->belongsTo(ClauseCategory::class);
    }
}
