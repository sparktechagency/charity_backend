<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
    protected $fillable = [
        'auction_id',
        'name',
        'email',
        'contact_number',
        'bit_online',
        'payment_type',
        'card_number',
        'status'
    ];
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

}
