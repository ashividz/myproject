<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Auth;

class LeadDnc extends Model
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