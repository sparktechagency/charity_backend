<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastStore extends Model
{
    protected $fillable = [
        'podcast_title',
        'host_title',
        'guest_title',
        'host_profile',
        'guest_profile',
        'description',
        'mp3',
        'thumbnail',
    ];
}
