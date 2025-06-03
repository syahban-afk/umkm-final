<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'admin_id'
    ];

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
