<?php

namespace App\Models\KD;

use Illuminate\Database\Eloquent\Model;



class Ayurvedic_Info extends Model
{
    protected $table = 'test.Ayurvedic_Info';
    protected $fillable = ['Title','Author','Description','created_at','updated_at','tag'];
}
