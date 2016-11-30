<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
class Like extends Model
{
    protected $fillable = ['content_id', 'content_type', 'user_id', 'like'];
    public $timestamps = false;

    /*Relation::morphMap([
    'posts' => App\Post::class,
    'comments' => App\Comment::class,
    ]);*/

    public function likable()
    {
        return $this->morphTo();
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }
   
}
