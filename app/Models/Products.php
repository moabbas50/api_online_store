<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table="products";
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'discount',
        'category_id',

    ];
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }


    // Define the relationship with product images
    public function images()
    {
        return $this->hasMany(product_images::class);
    }

    // Optionally, method to get the main image
    public function mainImage()
    {
        return $this->hasOne(product_images::class)->where('is_main_image', true);
    }
    public function orderItems()
    {
        return $this->hasMany(Order_items::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart_items::class);
    }
}
