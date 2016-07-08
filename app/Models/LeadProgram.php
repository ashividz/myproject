<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadProgram extends Model
{
    protected $table = "lead_program";

    protected $fillable = [
        'program_id'
    ];

    public static function store($id, $programs)
    {
        //Delete all existing
        LeadProgram::where('lead_id', $id)->delete();

        foreach ($programs as $key => $program) {
            $lp = new LeadProgram;
            $lp->lead_id = $id;
            $lp->program_id = $key;
            $lp->save();
        }
    }
}
