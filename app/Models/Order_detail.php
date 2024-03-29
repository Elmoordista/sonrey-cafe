<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'quantity',
        'product_id',
        'total',
    ];
    protected $with = [
        'product',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class)->where('status', 3);
    }
}
