<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartStatus extends Model
{   
    public function approvers()
    {
        return $this->belongsToMany(Role::class, 'cart_approver', 'status_id', 'approver_role_id')->withPivot('id');
    }
}
