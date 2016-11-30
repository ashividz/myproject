<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Helper;

use App\Support\ArraySorter;

class PatientBT extends Model
{

  /*  CREATE TABLE IF NOT EXISTS `patient_bts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `remark` varchar(255) NULL,
  `file_data` mediumblob NOT NULL,
  `mime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `created_by` int(4) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `created_at` (`created_at`),
  KEY `report_date` (`report_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;*/

    protected $table = 'patient_bts';
    protected $fillable = ['mime', 'size', 'file_data', 'remark', 'created_by', 'patient_id'];
    protected $dates = ['report_date'];

    public function patient()
    {

        return $this->belongsTo(Patient::class, 'id', 'patient_id');        
    }



     public function uploader()
        {
            return $this->belongsTo(User::class, 'id', 'created_by');
        }
 }