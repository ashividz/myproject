<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class NotificationRecipient extends Model
{
    protected $fillable = [
        'notification_id',
        'recipient_id'
    ];
}