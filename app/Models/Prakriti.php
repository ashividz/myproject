<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Carbon;

use OwenIt\Auditing\AuditingTrait;

class Prakriti extends Model
{
    use AuditingTrait;
    
    protected $table = "prakritis";
}