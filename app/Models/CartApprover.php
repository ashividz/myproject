<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CartApprover extends Model
{
    public $table = 'cart_approver';

    protected $fillable = ['status_id', 'approver_role_id'];
}