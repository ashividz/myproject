<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    //use SoftDeletes;
    protected $fillable = ['value'];

    public function approvers()
    {
        return $this->belongsToMany(Role::class, 'approver_discount', 'discount_id', 'approver_role_id')->withPivot('id');
    }
}