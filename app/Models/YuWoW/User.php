<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'yuwow_alpha_1_0.users';

    public function healthtrack()
    {
    	return $this->hasMany(Healthtrack::class, 'dch_cust_id', 'Id');
    }

    public function deviation()
    {
    	return $this->hasMany(Deviation::class, 'dcd_cust_id', 'Id');
    }

    public function diary()
    {
    	return $this->hasMany(Diary::class, 'dch_cust_id', 'Id');
    }

    public function fitness()
    {
    	return $this->hasMany(Fitness::class, 'dcf_cust_id', 'Id');
    }

    public function getUserEmailAttribute($value)
    {
        return trim(strtolower(ucfirst($value)));
    }

    public function firstUseDate()
    {
        $firstUseDates = array();
        if($this->deviation->first())
            $firstUseDates[] = $this->deviation()->orderBy('Id')->first()->dcd_date;
        if($this->healthtrack->first())
            $firstUseDates[] = $this->healthtrack()->orderBy('dch_htrack_id')->first()->dch_date_recording;
        if($this->diary->first())
            $firstUseDates[] = $this->diary()->orderBy('Id')->first()->dch_date;
        if($this->fitness->first())
            $firstUseDates[] = $this->fitness()->orderBy('Id')->first()->dcf_date;

        if($firstUseDates)
            return min($firstUseDates);
        else
            return null;        
    }
    
    public function lastUseDate()
    {
            $lastUseDates = array();
            if($this->deviation->first())
                $lastUseDates[] = $this->deviation()->orderBy('Id','desc')->first()->dcd_date;
            if($this->healthtrack->first())
                $lastUseDates[] = $this->healthtrack()->orderBy('dch_htrack_id','desc')->first()->dch_date_recording;
            if($this->diary->first())
                $lastUseDates[] = $this->diary()->orderBy('Id','desc')->first()->dch_date;
            if($this->fitness->first())
                $lastUseDates[] = $this->fitness()->orderBy('Id','desc')->first()->dcf_date;
            if($lastUseDates)
                return max($lastUseDates);
        else
            return null;        
    }

    public function totalUsage()
    {
        return $this->deviation->count() + $this->healthtrack->count() + $this->diary->count() + $this->fitness->count();
    }
}
