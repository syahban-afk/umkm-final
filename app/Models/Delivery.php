<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'courier_name',
        'tracking_number',
        'status',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
