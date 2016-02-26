<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function lead()
    {
    	return $this->belongsTo(Lead::class);
    }

    public function template()
    {
    	return $this->belongsTo(EmailTemplate::class, 'template_id');
    }
}
