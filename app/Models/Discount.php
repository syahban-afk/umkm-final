<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    protected $fillable = [
        'discount_category_id',
        'percentage',
        'start_date',
        'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DiscountCategory::class, 'discount_category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
