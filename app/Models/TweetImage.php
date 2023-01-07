<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TweetImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tweet_id',
        'image',
    ];
}
