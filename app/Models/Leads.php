<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use DB;

class Leads extends Model
{
    protected $table = "marketing_details";

    protected $fillable = ['clinic', 'enquiry_no', 'entry_date', 'name', 'dob', 'gender', 'email', 'email_alt', 'phone', 'mobile', 'weight', 'country', 'state', 'city'];

    public function patient()
    {
        return $this->hasOne('App\Models\Patient', 'lead_id');
    }

    public function latestDisposition()
    {
    	return $this->hasMany('CallDisposition', 'clinic', 'enquiry_no')->latest();
    }

    public function status()
    {
        return $this->hasMany( LeadStatus::class, 'lead_id');
    }

    public function lastStatus()
    {
        return $this->hasMany( LeadStatus::class, 'lead_id')->latest();
    }

    public static function filterByCRE($cre, $start_date, $end_date)
	{
		return DB::select("SELECT m.id, m.name, m.clinic, m.enquiry_no, m.entry_date, c.start_date, mso.source_name AS source, mst.status, mcd.disposition, cd.remarks, cd.created_at, COUNT(cd2.id) AS calls_count FROM marketing_details m
                    LEFT JOIN (SELECT clinic, enquiry_no, cre, start_date FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.clinic=B.clinic AND A.enquiry_no=B.enquiry_no)) c ON c.clinic = m.clinic AND c.enquiry_no = m.enquiry_no 
                    LEFT JOIN (SELECT clinic, enquiry_no, source FROM lead_sources A WHERE id = (SELECT MAX(id) FROM lead_sources B WHERE A.clinic=B.clinic AND A.enquiry_no=B.enquiry_no)) so ON so.clinic = m.clinic AND so.enquiry_no = m.enquiry_no 
                    LEFT JOIN m_lead_source mso ON mso.id = so.source
                    LEFT JOIN (SELECT clinic, enquiry_no, status FROM lead_status A WHERE id = (SELECT MAX(id) FROM lead_status B WHERE A.clinic=B.clinic AND A.enquiry_no=B.enquiry_no)) st ON st.clinic = m.clinic AND st.enquiry_no = m.enquiry_no 
                    LEFT JOIN m_lead_status mst ON mst.id = st.status
                    LEFT JOIN (SELECT clinic, enquiry_no, remarks, disposition, created_at FROM call_dispositions A WHERE id = (SELECT MAX(id) FROM call_dispositions B WHERE A.clinic=B.clinic AND A.enquiry_no=B.enquiry_no AND name LIKE '%" . $cre . "%')) cd ON cd.clinic = m.clinic AND cd.enquiry_no = m.enquiry_no 
                    LEFT JOIN (SELECT id, clinic, enquiry_no FROM call_dispositions  WHERE name LIKE '%" . $cre . "%') cd2 ON cd2.clinic = m.clinic AND cd2.enquiry_no = m.enquiry_no 
                    LEFT JOIN m_call_disposition mcd ON mcd.id = cd.disposition
                    WHERE c.start_date BETWEEN :start_date AND :end_date
                    AND cre LIKE :cre
                    AND (st.status <> 6 OR st.status IS NULL)
                    GROUP BY clinic, enquiry_no
                    ORDER BY start_date DESC", 
                    [$start_date, $end_date, $cre]);
	}

    public static function crePipelinesByStatus($cre, $status, $start_date, $end_date)
    {
        return DB::select("SELECT m.id, m.clinic, m.enquiry_no, m.name, m.phone, m.country, m.state, mls.source, c.cre, c.start_date, so.created_at,
                    (SELECT COUNT(*) FROM call_dispositions cd WHERE cd.lead_id=m.id) AS calls FROM marketing_details m
                    JOIN lead_cre c ON c.lead_id = m.id
                    JOIN lead_status s ON s.lead_id = m.id
                    LEFT JOIN lead_sources so ON so.lead_id = m.id
                    LEFT JOIN m_lead_source mls ON mls.id = so.source
                    WHERE c.cre LIKE :cre
                    AND c.id = ( SELECT MAX( id ) FROM lead_cre WHERE lead_id = m.id)
                    AND s.id = ( SELECT MAX( id ) FROM lead_status WHERE lead_id = m.id )
                    AND (so.id = ( SELECT MAX( id ) FROM lead_sources WHERE lead_id = m.id) OR so.id IS NULL)
                    AND s.status = :status
                    AND c.start_date BETWEEN :start_date AND :end_date
                    GROUP BY clinic, enquiry_no
                    ORDER BY c.start_date DESC",
                    [$cre, $status, $start_date, $end_date]);
    }

    public static function getHotPipelines($start_date, $end_date)
    {
        $query = "SELECT calDay as `date`, m.id, m.clinic, m.enquiry_no, m.name, d.name AS cre, remarks, callback, l.status FROM
                    (SELECT cast('" . $start_date . "' + interval `day` day as date) calDay FROM days365
                    WHERE  cast('" . $start_date . "' + interval `day` day as date) <= '" . $end_date . "') calendar
                    LEFT JOIN call_dispositions d ON DATE_FORMAT(callback, '%y-%m-%d') = calDay
                    JOIN marketing_details m ON m.clinic=d.clinic AND m.enquiry_no = d.enquiry_no
                    JOIN (SELECT lead_id, clinic, enquiry_no, status FROM lead_status A
                    WHERE id = (SELECT MAX(id) FROM lead_status B WHERE A.lead_id = B.lead_id)) s
                    ON s.clinic=m.clinic AND s.enquiry_no=m.enquiry_no
                    JOIN m_lead_status l ON l.id = s.status
                    WHERE d.callback = (SELECT MAX(callback) FROM call_dispositions A WHERE A.lead_id = d.lead_id AND disposition=15) 
                    GROUP BY clinic, enquiry_no
                    ORDER BY callback";
        return DB::select($query);//, [$start_date, $start_date, $end_date]);
    }

    public static function getLeadsPipeline($start_date, $end_date)
    {
        return DB::select("SELECT m.id, m.name, m.clinic, m.enquiry_no, m.phone, m.mobile, m.entry_date, c.cre, c.start_date, mso.source_name AS source, d.calls, mst.status FROM marketing_details m      
                LEFT JOIN (SELECT lead_id, clinic, enquiry_no, source, created_at FROM lead_sources A WHERE id = (SELECT MAX(id) FROM lead_sources B WHERE A.lead_id = B.lead_id)) so
                ON so.lead_id = m.lead_id
                LEFT JOIN m_lead_source mso
                ON mso.id = so.source
                LEFT JOIN (SELECT DISTINCT lead_id, clinic, enquiry_no, cre, start_date  FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id = B.lead_id)) c
                ON c.lead_id = m.id
                LEFT JOIN (SELECT DISTINCT lead_id, clinic, enquiry_no, status  FROM lead_status A WHERE id = (SELECT MAX(id) FROM lead_status B WHERE A.lead_id = B.lead_id) AND (status <> 5 OR status <> 6)) st
                ON st.lead_id = m.id
                LEFT JOIN m_lead_status mst
                ON mst.id = st.status
                LEFT JOIN (SELECT lead_id, clinic, enquiry_no, COUNT(*) AS calls FROM call_dispositions GROUP BY clinic, enquiry_no) d
                ON d.lead_id = m.id
                WHERE m.entry_date BETWEEN :start_date AND :end_date
                ORDER BY so.created_at DESC",
                [$start_date, $end_date]
            );
    }

}
