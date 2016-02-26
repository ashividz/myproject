<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use DB;

class Query extends Model
{
    public function lead()
    {
        return $this->hasOne(Lead::class, 'query_id', 'id');
    }

    public static function getLastQueryId($vendor)
    {
    	$query =  Query::where('vendor', $vendor)
    				->orderBy('id', 'DESC')
    				->first();

    	return $query->query_id;
    }

    public static function updateQuery($id, $lead, $status)
    {
    	$query = Query::find($id);

    	$query->lead_id = $lead->id;
        $query->status = $status;
    	$query->save();
    }

    public static function saveQuery($vendor, $request)
    {
        $query = new Query;
        $query->query_id = isset($request->id) ? $request->id : $request->sno;
        $query->name = $request->name;
        $query->country = isset($request->country) ? $request->country : "";
        $query->state = isset($request->state) ? $request->state : "";
        $query->city = $request->city;
        $query->email = $request->email;

        if (isset($request->mobile) <> "") {
            $query->phone = $request->mobile;
        }
        else {
            $query->phone = $request->country == "IN" ? $request->phone : $request->calling_code . $request->phone;
        }

        if (isset($request->comments) <> "") {
            $query->query = $request->comments;
        }
        else {
            $query->query = trim($request->time) <> "" ? $request->query . "<p>Time To Call : " . $request->time : $request->query;
        }
        
        $query->source = $request->source;
        $query->ip = $request->ip;
        $query->httpref = isset($request->httpref) ? $request->httpref : $request->page;
        $query->date = $request->date;
        $query->vendor = $vendor;
        $query->save();

        return true;
    }
}
