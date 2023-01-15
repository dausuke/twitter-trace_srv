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

    protected $appends = [
        'comments',
        'like_count',
        'retweet_count',
        'comment_count',
        'is_liked',
        'is_retweeted',
        'is_commented'
    ];

    public function user()
    {
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
        return $this->belongsToMany(User::class, 'retweets')->using(Retweet::class);
    }

    public function getCommentsAttribute()
    {
        return $this->where('id', 'comment_to')->get();
    }

    public function getLikeCountAttribute()
    {
        return $this->likeUsers()->count();
    }

    public function getRetweetCountAttribute()
    {
        return $this->reTweetUsers()->count();
    }

    public function getCommentCountAttribute()
    {
        return $this->where('id', 'comment_to')->count();
    }

    public function getIsLikedAttribute()
    {
        $user_id = auth()->id();
        return $this->likeUsers()->exists('user_id', $user_id);
    }

    public function getIsRetweetedAttribute()
    {
        $user_id = auth()->id();
        return $this->reTweetUsers()->exists('user_id', $user_id);
    }

    public function getIsCommentedAttribute()
    {
        $user_id = auth()->id();
        return $this->where('id', 'comment_to')->exists('user_id', $user_id);
    }
}
