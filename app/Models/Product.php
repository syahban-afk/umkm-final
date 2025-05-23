<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'product_category_id',
        'discount_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class,'discount_id');
    }

    // Tambahkan metode alias untuk discounts
    public function discounts(): BelongsTo
    {
        return $this->discount();
    }
}
