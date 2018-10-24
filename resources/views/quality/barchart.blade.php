<script type="text/javascript">
$(function () {
    $('#{{$id}}').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: '{!!$question!!}'
        },
        subtitle: {
            text: 'Answered: {!!$count!!}'
        },
        xAxis: {
            categories: [
                @foreach($survey AS $item)
                    ['{{$item->answer}}'],
                @endforeach
            ],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Patients Feedback',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' '
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Survey Result',
            colorByPoint: true,
            data: [
                @foreach($survey AS $item)
                    {{$item->count}},
                @endforeach
            ]
        }]
    });
});
        </script>