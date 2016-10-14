<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use SoftDeletes;/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function attachments()
    {
    	return $this->hasMany(EmailAttachment::class);
    }

    public function category()
    {
        return $this->belongsTo(EmailTemplateCategory::class);
    }
}
