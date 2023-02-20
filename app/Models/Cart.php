<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'cart_total',
        'status',
    ];
    
    public function cart_detail()
    {
        return $this->hasMany(Cart_detail::class);
    }
}
