<?php

namespace App\Models\Dialer;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    protected $connection = 'pgsql';
    protected $table 	  = 'ct_dispositions';
}
