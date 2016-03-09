<?php

namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'yuwow_alpha_1_0.cities';

    public function timeZoneObject()
    {
        return $this->hasOne(Timezone::class,'time_zone_id','timezone');
    }

    public function getLocalTime()
    {
        if($this->timeZoneObject){
            $carbon = Carbon::now('UTC');        
            $carbon->addSeconds($this->timeZoneObject->gmt_offset*3600);
            return $carbon->toDayDateTimeString();
        }          
        else
            return null;
    }
}
