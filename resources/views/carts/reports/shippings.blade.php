@extends('master')

@section('content')
<div id="app">
    <div class="col-md-12">
        <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
    </div>
    <div class="col-md-6">
        <chart :start_date.sync="start_date" :end_date.sync="end_date"></chart>
    </div>
</div>
<script>

Vue.component('chart', {
    template: '<div id="shipping" style="height: 450px"></div>',
    props : ['start_date', 'end_date'],
    data: function() {
        return {

            opts: {
                chart: {
                    renderTo: 'shipping',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Shippings'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: 'Shippings',
                    data: [{}]
                }]
            }
        }
    },
    ready: function() {
        this.getCartShippings();
        this.$watch('start_date', function (oldval, newval) {
            this.getCartShippings();
        })
        this.$watch('end_date', function (oldval, newval) {
            this.getCartShippings();
        })
    },
    methods: {
        getCartShippings() {
            $.isLoading({ text: "Loading" });
            this.$http.get("/getCartShippings", {
                'start_date': this.start_date, 
                'end_date' : this.end_date, 
            })
            .then( (response) => {
                //this.opts.xAxis.categories = response.data.date;
                //this.opts.series[0] = "Invoices";
                this.opts.series[0].data = response.data;

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