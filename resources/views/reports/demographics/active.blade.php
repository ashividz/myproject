<div class="container1">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left">
                @include('partials/daterange')
            </div>
        </div>
        <div class="panel-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Total Active</a></li>
                <li role="presentation"><a href="#new" aria-controls="new" role="tab" data-toggle="tab">New/End</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <div id='chart-active'></div>
                </div>
                <div role="tabpanel" class="tab-pane" id="new">
                    <div id='chart-new' style="width:100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {

    var active = [
@foreach($activePatients as $day)
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->count}}],
@endforeach
    ],
    end = [
@foreach($endPatients as $day)
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->count}}],
@endforeach
    ],
    start = [
@foreach($newPatients as $day)
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->count}}],
@endforeach
    ];


    $('#chart-active').highcharts({
        chart: {
            type: 'area'
        },
        
        title: {
            text: 'Active'
        },

        xAxis: {
            type: 'datetime',
            min: Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($start_date)))}}),
            max: Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($end_date)))}}),
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
            name: 'Total',
            data: active,
            drilldown : start
        }]

    });
    $('#chart-new').highcharts({
        chart: {
            type: 'line'
        },
        
        title: {
            text: 'New End'
        },

        xAxis: {
            type: 'datetime',
            min: Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($start_date)))}}),
            max: Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($end_date)))}}),
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
            name: 'Start',
            data: start
        }, {
            name: 'End',
            data: end
        }]

    });
});
</script>