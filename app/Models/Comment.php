<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['content', 'post_id', 'user_id'];
    protected $morphClass = 'Comment';
    public function likes()
    {
        return $this->morphMany(Like::class, 'likable', 'content_type', 'content_id')->where('state', '1');
    }
    public function dislikes()
    {
        return $this->morphMany(Like::class, 'likable', 'content_type', 'content_id')->where('state', '2');
    }

     public function creator()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

     public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
