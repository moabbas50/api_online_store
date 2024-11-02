<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_items extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price', // You may want to store price at the time of purchase
        'total'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    // Optionally, define the relationship with the product
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
