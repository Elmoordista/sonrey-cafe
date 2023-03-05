<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminInfo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'logo',
        'gcash_name',
        'gcash_number',
        'contact_number',
    ];
}
