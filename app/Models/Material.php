<?php

namespace App\Models;

use App\Models\MaterialUnitSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('pricebooks.Name', 'like', $term)
                ->orWhere('pricebooks.SKU', 'like', $term);
        });
    }

    public function scopeCategory($query, $category)
    {
        if ($category ?? false) {
            $query->where('pricebook_category_id', '=', $category);
        }
    }
    public function materialUnitSize(): BelongsTo
    {
        return $this->belongsTo(MaterialUnitSize::class);
    }
    public function materialCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class);
    }
}
