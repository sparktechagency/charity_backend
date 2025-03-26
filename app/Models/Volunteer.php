<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'location',
        'reason',
        'donated',
        'status',
        'image',
    ];
}
