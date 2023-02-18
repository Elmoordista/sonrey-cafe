<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'email',
        'image',
        'birth_date',
        'number',
        'name',
        'username',
        'password',
    ];
}
