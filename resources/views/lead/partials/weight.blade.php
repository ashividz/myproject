<?php
/*Block authored by Sunil*/
//updated on 2016-07-01 to handle upgrade cases
    $upgradeDuration = \App\Models\Fee::getUpgradeDuration();
    $endFee          = $patient->fees->sortByDesc('end_date')->first();
    $startFee        = $endFee; 
    $fees            = $patient->fees->sortByDesc('end_date');

    foreach ($fees as $f ) {
        $diffInDays = $f->end_date->diffInDays($startFee->start_date,false);
        if ( ($diffInDays <= $upgradeDuration))
            $startFee = $f;
        else
            break;
    }
    $initialWeight =  \App\Models\PatientWeight::where('patient_id',$patient->id)
                             ->where('weight','>',0)
                             ->where('date','>=',$startFee->start_date)
                             ->orderBy('date')
                             ->first();

    $latestWeight  =  \App\Models\PatientWeight::where('patient_id',$patient->id)
                             ->where('weight','>',0)
                             ->where('date','>=',$startFee->start_date)
                             ->orderBy('date','desc')
                             ->first();
    $initialBMI = null;
    $latestBMI  = null;
    
    if ( $patient->lead->height >0 ) {
        $initialBMI = $initialWeight ? number_format($initialWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;
        $latestBMI = $latestWeight ? number_format($latestWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;
    }   
        
/*end of the Block authored by Sunil*/
?>
@extends('lead.index')
@section('top')
<div class="col-md-9">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Weight Synopsis</div>
        </div>
        <div class="panel-body">            
            <label>Height</label> : {{$patient->lead->height .'cm'}}</span>
            <table class="table table-bordered">              
                <thead>
                    <tr>
                        <th>#</th>
                        <th>date</th>
                        <th>weight</th>
                        <th>BMI</th>
                    </tr>
                </thead>                    
                <tbody>
                    <tr>
                        <td>Initial weight</td>
                        <td>{{$initialWeight ? date("F j, Y",strtotime($initialWeight->date)) :''}}</td>
                        <td>{{$initialWeight ? $initialWeight->weight:''}}</td>
                        <td>{{$initialBMI ? $initialBMI :''}}</td>
                    </tr>
                    <tr>
                        <td>Latest weight</td>
                        <td>{{$latestWeight ? date("F j, Y",strtotime($latestWeight->date)) :''}}</td>
                        <td>{{$latestWeight ? $latestWeight->weight:''}}</td>
                        <td>{{$latestBMI ? $latestBMI :''}}</td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection