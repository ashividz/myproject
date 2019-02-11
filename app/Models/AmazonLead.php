<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Session;

class AmazonLead extends Model
{
	 public $table = 'amazon_leads';

	public function dispositions()
    {
        return $this->hasMany(AmazonDisposition::class, 'amazon_lead_id')->orderBy('id', 'desc');
    }

}