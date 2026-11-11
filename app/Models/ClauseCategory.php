<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClauseCategory extends Model
{
    public function clauses() {
        return $this->hasMany(Clause::class);
    }
}
