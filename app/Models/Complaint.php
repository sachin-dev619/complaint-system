<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

     // ✅ USER RELATION (keep only ONE)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ CATEGORY RELATION
    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    // ✅ SUBCATEGORY RELATION
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Subcategory::class);
    }
    
}
