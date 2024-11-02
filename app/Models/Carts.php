<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;


    protected $fillable = [
        'customer_id',
    ];
    public function cartItems()
    {
        return $this->hasMany(Cart_items::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
