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
@extends('patient.index')
@section('top')
<div class="col-md-3">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Weight</div>
        </div>
        <div class="panel-body">
            <form id="form-weight" method="POST" class="form" >
                <div class="form-group">
                    <input type="text" name="weight" size="4" placeholder="Weight"> Kgs
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Save</button>
                </div>
                <input type="hidden" name='_token' value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
    <button class="btn btn-primary" id="weight-copy-ifitter" value="{{$patient->id}}" >Copy from iFitter</button>        
</div>
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
<div class="col-md-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Full iFitter Profile</div>
        </div>
        <div class="panel-body">            
            <a href="{{url('patient/'.$patient->id.'/fullIfitterProfile')}}" target="_blank">Click to see full iFitter profile</a>     
        </div>
    </div>
</div>
@if(Auth::user()->canUpdateInitialWeight())
<div class="col-md-6"> 
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">update initial weight</div>
        </div>
        <div class="panel-body">
            <form id="initial-weight" action="/patient/{{$patient->id}}/initialWeight" method="POST" class="form" >
                <div class="form-group">
                    <input type="text" name="initial_weight" size="4" placeholder="initial weight"> Kgs
                </div>                
                <input type="date" name="initial_weight_date" value="{{$startFee->start_date->toDateString()}}"/> 
                <div class="form-group">
                    <button class="btn btn-primary">Save</button>
                </div>
                <input type="hidden" name='_token' value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('main')
<div class="container1">  
    <div class="panel panel-default">
        <div class="panel-heading1">
        </div>
        <div class="panel-body">
            <div id='container'></div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {

    var weight = [
@foreach($days as $day)
    @if($day->weight)
        <?php
            $time    = strtotime($day->date);
            $utcTime = date('Y',$time).','.(date('m',$time)-1).','.date('d',$time);
        ?>
            [Date.UTC({{$utcTime}}), {{$day->weight}}],
    @endif
@endforeach
    ],
    smooth_weight = [
@foreach($days as $day)
        <?php
            $time    = strtotime($day->date);
            $utcTime = date('Y',$time).','.(date('m',$time)-1).','.date('d',$time);
        ?>
            [Date.UTC({{$utcTime}}), {{$day->smooth_weight}}],
@endforeach
    ];


    $('#container').highcharts({
        global: {
            useUTC: false
        },
        chart: {
            type: 'line',
            height: '300'
        },
        
        title: {
            text: 'Weight'
        },

        xAxis: {
            <?php
                $startTime    = strtotime($days->first()->date);
                $endTime      = strtotime($days->last()->date);  
                $startUTCTime = date('Y',$startTime).','.(date('m',$startTime)-1).','.date('d',$startTime);
                $endUTCTime   = date('Y',$endTime).','.(date('m',$endTime)-1).','.date('d',$endTime);
            ?>
            type: 'datetime',
            min: Date.UTC({{$startUTCTime}}),
            max: Date.UTC({{$endUTCTime}}),
        },

        yAxis: {
            title: {
                text: null
            }
        },

        tooltip: {
            crosshairs: true,
            shared: true
        },

        legend: {
        },

        series: [{
            name: 'Weight',
            data: weight
        }]

    });
});
</script>

<script type="text/javascript">
$(document).ready(function() 
{
    $('#weight-copy-ifitter').on('click', function(){
        
        var url = '/patient/'+this.value+'/copyWeightFromIfitter';
        $.ajax(
        {
           type: "POST",
           url: url,
           data: {_token : '{{ csrf_token() }}'},
           success: function(data)
           {
               $('#alert').show();
               $('#alert').empty().append(data);
                setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        location.reload();
                     });
                }, 3000);
           },
           error : function(data) {
                var errors = data.responseJSON;

                console.log(errors);

                $('#alert').show();
                $('#alert').empty();
                $.each(errors, function(index, value) {
                    $('#alert').append("<li>"+value+"</li>");
                });

                setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        //location.reload();
                     });
                }, 3000);
           }
        });
    });
});
</script>
@endsection