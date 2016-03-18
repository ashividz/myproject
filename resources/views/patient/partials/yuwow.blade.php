@extends('patient.index')
@section('top')
<div class="container1">  
    <div class="panel panel-default">
        <div class="panel-heading1">
        </div>
        <div class="panel-body">
            <div id='container' style="max-height:400px"></div>
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
					<th>Diary</th>
					<th>Fitness</th>
					<th>Deviation</th>
				</tr>				
			</thead>
			<tbody>

		@foreach($days as $day)
				<tr>
					<td>{{date('jS M, Y', strtotime($day->date))}}</td>
					<td>{{$day->weight}}</td>
					<td>{{$day->diary}}</td>
					<td>{{$day->fitness}}</td>
					<td>{{$day->deviation}}</td>
				</tr>

		@endforeach
			</tbody>
		</table>
	</div>
</div>


@endsection