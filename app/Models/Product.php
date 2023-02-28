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
        'status',
    ];
    
    public function cart_detail()
    {
        return $this->hasMany(CartDetail::class);
    }
    public function order_detail()
    {
        return $this->hasMany(Order_detail::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
