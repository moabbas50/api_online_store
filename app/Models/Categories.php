<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'image', // You can choose to store image as string if it's a URL or path
    ];

    public function products()
    {
        return $this->hasMany(Products::class);
    }
}
