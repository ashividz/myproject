@extends('master')

@section('content')
<div id="app">
    <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
    <chart :start_date.sync="start_date" :end_date.sync="end_date"></chart>
</div>
<script>
Vue.component('chart', {
    template: '<div id="container" style="height: 300px"></div>',
    props : ['start_date', 'end_date'],
    data: function() {
        return {

            opts: {
                chart: {
                    renderTo: 'container',
                    type: 'bar',
                    height: 450
                },
                title: {
                    text: 'Carts Funnel'
                },

                xAxis: {
                    categories: [],
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Carts Count'
                    }
                },
                legend: {
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    },
                    bar: {
                        dataLabels: {
                            enabled: true
                        },
                    }
                },
                series: [{}]
            }
        }
    },
    created: function() {
        
    },
    ready: function() {
        this.getFunnels();
        this.$watch('start_date', function (oldval, newval) {
            this.getFunnels();
        })
        this.$watch('end_date', function (oldval, newval) {
            this.getFunnels();
        })
    },
    methods: {
        getFunnels() {
            $.isLoading({ text: "Loading" });
            this.$http.get("/getCartsFunnel", {
                'start_date': this.start_date,
                'end_date': this.end_date
            })
            .then( (response) => {
                this.opts.xAxis.categories = response.data.name;
                this.opts.series[0].name = "Stages";
                this.opts.series[0].data = response.data.count;

                this.chart = new Highcharts.Chart(this.opts);
                $.isLoading( "hide" );
            }).bind(this);
        }
    }
})

new Vue({
    el: '#app',
    data: {
        chart: null,


        daterange: '{{ Carbon::now()->subDay(30)->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
        start_date: '',
        end_date: '',
    },

    computed: {

        start_date() {
            var range = this.daterange.split(" - ");
            return moment(range[0]).format('YYYY-MM-DD') + ' 0:0:0';
        },

        end_date() {
            var range = this.daterange.split(" - ");
            return moment(range[1]).format('YYYY-MM-DD') + ' 23:59:59';
        }
    }
})
</script>
<script type="text/javascript" src="/js/daterange.js"></script>

@endsection