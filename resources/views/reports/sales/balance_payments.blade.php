    <div class="container-fluid" id="payments">

        <div class="panel panel-default">
            <div class="panel-heading"> 
                <span class="pull-right">
                    <a href='/sales/report/performance/download?start_date=@{{ start_date }}&end_date=@{{ end_date }}' class="btn btn-primary" v-on:click="download">Download</a>
                </span>                
            </div>
            <div class="panel-body">
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Lead Details</th>
                            <th>CRE</th>
                            <th>Product Details</th>
                            <th>Payment Details</th>
                            <th>Program</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cart in carts">
                            <td>
                                @{{ cart.created_at | format_date }}
                            </td>
                            <td>
                                <span class="statusbar status@{{ cart.status.id + cart.state_id }}" title="@{{ cart.status.name + ' : ' + cart.state.name }}"></span>
                            </td>
                            <td>
                                <a href="/lead/@{{ cart.lead.id }}/cart" target="_blank">
                                    @{{ cart.lead.name }}
                                </a>
                                <div v-if="cart.lead.patient">
                                    <b>Patient Id</b> : <a href="/patient/@{{ cart.lead.patient.id }}/diet" target="_blank">
                                        @{{ cart.lead.patient.id }}
                                    </a>
                                </div>
                                <div>
                                    @{{ cart.source.name }}
                                </div>
                            </td>
                            <td>
                                @{{ cart.cre.employee.name }}
                                <div>
                                    <b>TL : </b>@{{ cart.cre.employee.supervisor.employee.name }}
                                </div>
                            </td>
                            <td>
                                <li v-for='product in cart.products'>
                                    @{{ product.name }} (@{{ product.pivot.quantity }})
                                </li>
                            </td>
                            <td>
                                <div>
                                    <b>Amount : </b>@{{ cart.amount | currency cart.currency.symbol }}
                                </div>
                                <div>
                                    <b>Payment : </b>@{{ cart.payment | currency cart.currency.symbol }}
                                </div>
                                <hr>
                                <div>
                                    <b>Balance : </b>@{{ cart.amount - cart.payment | currency cart.currency.symbol }}
                                </div>
                            </td>
                            <td>
                                <div>
                                    <b>Start Date</b> : @{{ cart.lead.patient.fees[0].start_date | format_date2 }}
                                </div>
                                <div>
                                    <b>End Date</b> : @{{ cart.lead.patient.fees[0].end_date | format_date2 }}
                                </div>  
                                <hr>
                                <b>Duration : </b> @{{ cart.lead.patient.fees[0].duration }} days                              
                            </td>
                            <td>
                                Button
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@include('partials.modal')
<style type="text/css">
    
    table.table {
        font-size: 12px;
    }
    hr {
        margin: 5px;
    }
    
</style>
<script>
    var vm = new Vue({
        el: '#payments',

        data: {
            loading: false,
            carts: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
        },

        ready: function(){
            this.getCarts();
        },

        methods: {

            getCarts() {
                this.loading = true;
                this.$http.get("/api/getBalancePayments", {'start_date': this.start_date, 'end_date' : this.end_date})
                .success(function(data){
                    this.carts = data;
                    this.loading = false;
                }).bind(this);
            },
        }
    })
    Vue.filter('format_date', function (value) {
        if (value == null) {
            return null;
        }
      return moment(value).format('D MMM hh:mm A');
    })
    Vue.filter('format_date2', function (value) {
        if (value == null) {
            return null;
        }
      return moment(value).format('D MMM');
    })
</script>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#daterange').daterangepicker(
    { 
        ranges: 
        {
            'Today': [new Date(), new Date()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), new Date()],
            'Last 30 Days': [moment().subtract(29, 'days'), new Date()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }, 
        format: 'YYYY-MM-DD' 
        }
    );   
    $('#daterange').on('apply.daterangepicker', function(ev, picker) 
    {   
        $('#daterange').trigger('change'); 
    });

});
</script>