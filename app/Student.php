<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = "studentlists";
    protected $fillable = ['student_id','student_name','father_name','address','dob','mobile_no','department','description','gender','country','state','city','web_url','pin_code'];
}
