<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class OBD extends Model
{
    protected $table = "obd_nos";

    //Check if the incoming call is from OBD Campaign
    public static function checkCall($mobile)
    {
        $obd = OBD::where('begin', '<=', $mobile)
        		->where('end', '>=', $mobile)
                ->first();

        if($obd)
        {
            return true;
        }

        return false;
    }
}
