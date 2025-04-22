<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transition extends Model
{
    protected $fillable = [
        'invoice',
        'payment_type',
        'donation_type',
        'frequency',
        'name',
        'email',
        'amount',
        'phone_number',
        'remark',
        'payment_status',
    ];

}
