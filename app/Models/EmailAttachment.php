<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAttachment extends Model
{
    public function template()
    {
    	return $this->belongsTo(EmailTemplate::class);
    }
}