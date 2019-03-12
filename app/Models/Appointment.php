<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Appointment extends Model {
    
    protected $table = 'appointments';

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

   public static function getAppointmentsByUser($user, $start_date, $end_date)
    {
    
        return Appointment::with('lead')
                ->whereBetween('created_at', array($start_date, $end_date))
                ->where('doctor_name', $user)
                ->limit(env('DB_LIMIT'))
                ->orderBy('id', 'DESC')
                ->get();
    }

}