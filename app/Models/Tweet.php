<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment_to',
        'body',
    ];

    protected $appends = ['comments'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(TweetImage::class);
    }

    public function likeUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->using(Like::class);
    }

    public function reTweetUsers()
    {
        return $this->belongsToMany(User::class, 'retweets')->using(Like::class);
    }

    public function getCommentsAttribute()
    {
        return $this->where('id', 'comment_to')->get();
    }
}
