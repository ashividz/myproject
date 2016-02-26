<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DialerPush extends Model
{
	protected $table = "dialer_push";

	public function lead()
	{
		return $this->belongsTo(Lead::class);
	}
}