<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Auth;

/*https://laravel.com/docs/5.1/queues
https://laravel.com/docs/5.3/scheduling
http://stackoverflow.com/questions/18737407/how-to-create-cron-job-using-php
http://www.adminschoice.com/crontab-quick-reference
https://code.tutsplus.com/tutorials/managing-cron-jobs-with-php--net-19428*/

class LeadDnd extends Model 
{
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}