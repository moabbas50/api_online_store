<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shiping extends Model
{
    use HasFactory;
    protected $fillable = [
         'order_id', 'address', 'status'
    ];

    // Each shipping address belongs to an order
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

}
