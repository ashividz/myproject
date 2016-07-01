<?php
/*Block authored by Sunil*/
//updated on 2016-07-01 to handle upgrade cases
    $upgradeDuration = 30;
    $upgradeSourceId = 22;
    $rejoinSourceId  = 23;
    $cfee = $patient->cfee;
    $fees = $patient->fees->filter(function ($item) use ($cfee) {
        return ($item->end_date <= $cfee->end_date);
    });
    $fees = $fees->sortByDesc('end_date');
    $startFee = $cfee;
    foreach($fees as $f){            
        $diffInDays = floor(strtotime($startFee->start_date) - strtotime($f->end_date)) ;
        $isUpgrade  = $f->source_id == $upgradeSourceId ? true :false;
        $isRejoin   = $f->source_id == $rejoinSourceId ? true :false;
        if ( ($diffInDays <= $upgradeDuration || $isUpgrade) && !$isRejoin )
            $startFee = $f;
        else
            break;                
    }
    //dd($startFee);
        
    $measurementsAfterStartDate = $measurements->filter(function ($item) use ($startFee){
            if ( $item->weight && (strtotime($item->date) >= strtotime($startFee->start_date)) )
                return true;
        });

    $initialWeight   = $measurementsAfterStartDate->sortBy('date')->first();
    $latestWeight    = $measurementsAfterStartDate->sortByDesc('date')->first();
    $initialBMI = null;
    $latestBMI = null;
    if($patient->lead->height && $patient->lead->height>0){  
        $initialBMI = $initialWeight ? number_format($initialWeight->weight*100*100/pow($patient->lead->height,2) ,1):null ;
        $latestBMI= $latestWeight ? number_format($latestWeight->weight*100*100/pow($patient->lead->height,2) ,1):null ;
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
</div>
<div class="col-md-6"> 
<div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Full iFitter Profile</div>
        </div>
        <div class="panel-body">            
            <a href="{{url('patient/'.$patient->id.'/fullIfitterProfile')}}">Click to see full iFitter profile</a>     
        </div>
    </div>
</div>
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