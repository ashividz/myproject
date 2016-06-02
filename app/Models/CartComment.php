<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartComment extends Model
{
    protected $fillable = ['text', 'created_by'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
