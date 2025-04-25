<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceBook extends Model
{
    protected $fillable=[
        'name',
        'email',
        'telephone_number',
        'book_date',
        'book_time',
        'book_status'
    ];
}
