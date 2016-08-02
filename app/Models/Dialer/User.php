<?php

namespace App\Models\Dialer;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'pgsql';
    protected $table 	  = 'ct_user';
}
