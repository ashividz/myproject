<?php

/*ALTER TABLE medical ADD patient_id int(11);
ALTER TABLE medical ADD created_at datetime default '2016-03-19 00:00:00';
ALTER TABLE medical ADD updated_at datetime default '2016-03-19 00:00:00';

update medical m  set patient_id = (select id from patient_details p where p.clinic=m.clinic  and p.registration_no= m.registration_no limit 1);*/

/*alter table medical add column (created_by int(4) unsigned not null);*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    protected $table = "medical";	
	protected $fillable=[
		'clinic',
		'registration_no',
		'hemoglobin',
		'hemoglobin_status',
		'mcv',
		'mcv_status',
		'mch',
		'mch_status',
		'mchc',
		'mchc_status',
		'esr',
		'esr_status',
		'fasting',
		'fasting_status',
		'pp',
		'pp_status',
		'sgot',
		'sgot_status',
		'sgpt',
		'sgpt_status',
		'alkaline',
		'alkaline_status',
		'ggtp',
		'ggtp_status',
		't3',
		't3_status',
		't4',
		't4_status',
		'tsh',
		'tsh_status',
		'total',
		'total_status',
		'hdl',
		'hdl_status',
		'ldl',
		'ldl_status',
		'vldl',
		'vldl_status',
		'tri',
		'tri_status',
		'urea',
		'urea_status',
		'serum',
		'serum_status',
		'uric',
		'uric_status',
		'totall',
		'totall_status',
		'proteins',
		'proteins_status',
		'seruma',
		'seruma_status',
		'prolactin_f',
		'prolactin_f_status',
		'prolactin_p',
		'prolactin_p_status',
		'insulin_f',
		'insulin_f_status',
		'insulin_p',
		'insulin_p_status',
		'date',
		'patient_id',
		'created_by',
	];

}
