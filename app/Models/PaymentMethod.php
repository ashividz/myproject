<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    public function approver()
    {
        $this->belongsToMany(Role::class, 'payment_approver', 'payment_method_id', 'role_id');
    }
}
