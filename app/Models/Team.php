<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'work_experience',
        'photo',
        'twitter_link',
        'linkedIn_link',
        'instagram_link',
    ];
}
