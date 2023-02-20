<?php

namespace App\Models;

use App\CartDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'product_name',
        'category_id',
        'price',
    ];
    
    public function cart_detail()
    {
        return $this->hasMany(CartDetail::class);
    }
}
