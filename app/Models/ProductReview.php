<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'rating',
        'comment',
        'review_date'
    ];

    protected $casts = [
        'review_date' => 'datetime'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    // Accessor untuk mendapatkan user_id dari customer_id
    public function getUserIdAttribute()
    {
        return $this->customer->user_id;
    }
}
