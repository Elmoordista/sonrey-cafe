<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fcmtoken extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'fcm_token',
    ];
}
