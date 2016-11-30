<?php

namespace App\Models;

use Carbon\Carbon;

 /*CREATE TABLE `patient_measurements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) unsigned NOT NULL,
  `abdomen` double(5,2) unsigned DEFAULT NULL,
  `arms` double(5,2) unsigned DEFAULT NULL,
  `chest` double(5,2) unsigned DEFAULT NULL,
  `hips` double(5,2) unsigned DEFAULT NULL,
  `thighs` double(5,2) unsigned DEFAULT NULL,
  `waist` double(5,2) unsigned DEFAULT NULL,
  `bp_systolic` int(3) unsigned DEFAULT NULL,
  `bp_diastolic` int(3) unsigned DEFAULT NULL,
  `created_by` int(3) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1*/

use Illuminate\Database\Eloquent\Model;
use DB;

class PatientMeasurement extends Model
{
    //
}