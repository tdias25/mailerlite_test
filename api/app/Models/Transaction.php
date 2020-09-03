<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'to',
        'from',
        'amount',
        'details'
    ];

    protected $table = 'transactions';
}
