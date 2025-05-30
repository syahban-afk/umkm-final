<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Customer;

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

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    // Mendapatkan review dari user tertentu
    public function reviewByUser($userId)
    {
        // Dapatkan customer_id dari user_id
        $customer = Customer::where('user_id', $userId)->first();
        if (!$customer) {
            return null;
        }

        return $this->reviews()->where('customer_id', $customer->id)->first();
    }
}
