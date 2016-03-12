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
</div>
<div class="col-md-9">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Measurements</div>
        </div>
        <div class="panel-body">
            <form id="form" class="form-inline">
                <div class="form-group">
                    <input type="text" name="arm" placeholder="Arm" size="6">
                    <input type="text" name="abdomen" placeholder="Abdomen" size="6">
                    <input type="text" name="thighs" placeholder="Thighs" size="6">
                    <input type="text" name="Waist" placeholder="waist" size="6">
                    <button class="btn btn-primary" disabled>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('main')
<div class="container">  
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
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->weight}}],
    @endif
@endforeach
    ],
    smooth_weight = [
@foreach($days as $day)
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->smooth_weight}}],
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