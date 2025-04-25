<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable =[
        'title',
        'description',
        'image',
        'donate_share',
        'name',
        'email',
        'contact_number',
        'city',
        'address',
        'profile',
        'status',
        'payment_type',
        'card_number',
        'start_budget',
        'end_budget',
        'duration'
    ];
    public function contributors()
    {
        return $this->hasMany(Contributor::class);
    }

}
