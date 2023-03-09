<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'about_app',
        'prob_encounter',
        'suggestion',
        'rate',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
