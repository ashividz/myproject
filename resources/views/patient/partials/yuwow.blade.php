@extends('patient.index')
@section('top')
@if(Auth::user()->hasRole('yuwow_support'))
<div class="container1">  
    <div class="panel panel-default">
        <div class="panel-heading1">
        </div>
        <div class="panel-body">
            <b>Preferred Time</b> : {{$patient->suit->trial_plan or ""}}
        </div>
    </div>
</div>
@endif
<div class="container1">  
    <div class="panel panel-default">
        <div class="panel-heading1">
        </div>
        <div class="panel-body">
            <div id='container' style="max-height:400px"></div>
        </div>
    </div>
</div>

<div class="container1">  
    <div class="panel panel-default">
        <div class="panel-heading1">
        </div>
        <div class="panel-body">
            <div id='glucocontainer' style="max-height:400px"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function () {

    var weight = [
        @foreach($days as $day)
            @if($day->weight)
                    [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->weight}}],
                    
            @endif
        @endforeach
    ];

    var sugar = [
        @foreach($days as $day)
            @if($day->sugar)
                    [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->sugar}}],
                    
            @endif
        @endforeach
    ];

    $('#container').highcharts({
        global: {
            useUTC: false
        },
        chart: {
            type: 'line',
            height: '250'
        },
        
        title: {
            text: 'Weight'
        },

        xAxis: {
            type: 'datetime',
            min: Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($days->first()->date)))}}),
            max: Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($days->last()->date)))}}),
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

    $('#glucocontainer').highcharts({
        global: {
            useUTC: false
        },
        chart: {
            type: 'line',
            height: '250'
        },
        
        title: {
            text: 'Blood Sugar(mg/dl)'
        },

        xAxis: {
            type: 'datetime',
            min: Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($days->first()->date)))}}),
            max: Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($days->last()->date)))}}),
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
            name: 'BloodSugar',
            data: sugar
        }]

    });
});
</script>
@endsection
@section('main')
<div class="container1">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-left">@include('partials/daterange')</div>
		</div>
		<div class="panel-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Date</th>
					<th>Weight</th>
                    <th>Blood-Sugar(mg/dl)</th>
					<th>Diary</th>
					<th>Fitness</th>
					<th>Deviation</th>
                    <th>Body Fat(%)</th>  
                    <th>Hydration(%)</th>
                    <th>Muscle mass(%)</th>
                    <th>Bone Weight(%)</th>
				</tr>				
			</thead>
			<tbody>

		@foreach($days as $day)
				<tr>
					<td>{{date('jS M, Y', strtotime($day->date))}}</td>
					<td>{{$day->weight}}</td>
                    <td>{{$day->sugar}}</td>
					<td>{{$day->diary}}</td>
					<td>{{$day->fitness}}</td>
					<td>{{$day->deviation}}</td>
                    <td>{{$day->body_fat}}</td>
                    <td>{{$day->hydration}}</td>
                    <td>{{$day->muscle_mass}}</td>
                    <td>{{$day->bone_weight}}</td>
				</tr>

		@endforeach
			</tbody>
		</table>
	</div>
</div>


@endsection