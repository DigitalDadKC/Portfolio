<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\PrettyPrinter\Standard;

class ClauseCategory extends Model
{
    public function clauses() {
        return $this->hasMany(Clause::class);
    }

    public function standardClauses() {
        return $this->hasMany(StandardClause::class);
    }
}
