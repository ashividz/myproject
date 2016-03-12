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
                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <div id='container'></div>
                </div>
                <div class="container">
                    <table class="table table-bordered" style="width:400px">
                        <thead>
                            <tr>
                                <td></td>
                                <th>Patients</th>
                                <th>Total Active Patients</th>
                            </tr>
                        </thead>
                        <tr>
                            <td><h5>Female</h5></td>
                            <td>
                                <strong>{{$days->female}}</strong>
                                ({{$days->total==0 ? 0: round($days->female/($days->total)*100, 2)}}%)
                            </td>
                            <td>
                                <strong>{{$days->active_female}}</strong>
                                ({{$days->active_total==0 ? 0: round($days->active_female/($days->active_total)*100, 2)}}%)
                            </td>
                        </tr>
                        <tr>
                            <td><h5>Male</h5></td>
                            <td>
                                <strong>{{$days->male}}</strong>
                                ({{$days->total==0 ? 0: round($days->male/($days->total)*100, 2)}}%)
                            </td>
                            <td>
                                
                                <strong>{{$days->active_male}}</strong>
                                ({{$days->active_total==0 ? 0: round($days->active_male/($days->active_total)*100, 2)}}%)
                            </td>
                        </tr>
                        <tr>
                            <td><h5>None</h5></td>
                            <td>
                                <strong>{{$days->none}}</strong>
                                ({{$days->total==0 ? 0: round($days->none/($days->total)*100, 2)}}%)
                            </td>
                            <td>
                                
                                <strong>{{$days->active_none}}</strong>
                                ({{$days->active_total==0 ? 0: round($days->active_none/($days->active_total)*100, 2)}}%)
                            </td>
                        </tr>                            
                        <tr>
                            <td><h5>Total</h5></td>
                            <td><strong>{{$days->total}}</strong></td>
                            <td><strong>{{$days->active_total}}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {

    var male = [
@foreach($days as $day)
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->male}}],
@endforeach
    ],
    female = [
@foreach($days as $day)
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->female}}],
@endforeach
    ],
    none = [
@foreach($days as $day)
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->none}}],
@endforeach
    ],
    total = [
@foreach($days as $day)
            [Date.UTC({{date('Y, m, d', strtotime('-1 month', strtotime($day->date)))}}), {{$day->female + $day->male + $day->none}}],
@endforeach
    ];


    $('#container').highcharts({
        global: {
            useUTC: false
        },
        chart: {
            type: 'area'
        },
        
        title: {
            text: 'Gender'
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
            name: 'Total',
            data: total
        }, {
            name: 'Female',
            data: female
        }, {
            name: 'Male',
            data: male
        }, {
            name: 'None',
            data: none
        }]

    });
});
</script>