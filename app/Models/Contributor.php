<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
    protected $fillable = [
        'auction_id',
        'user_id',
        'contact_number',
        'bit_online',
        'status',
        'payment_status'
    ];
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
