<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    public function approvers()
    {
        return $this->belongsToMany(Role::class, 'approver_payment', 'payment_method_id', 'approver_role_id')->withPivot('id');
    }
}
