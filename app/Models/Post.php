<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'publish', 'category_id', 'created_by'];
    protected $morphClass = 'Post';
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
        return $this->hasOne(User::class, 'id', 'created_by');
    }

     public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
