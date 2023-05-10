<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_ref',
        'client_id',
        'note',
        'total',
        'pay',
        'change',
    ];

    public function order_detail()
    {
        return $this->hasMany(Order_detail::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
