    <div class="row">

        <div class="panel panel-default">
            <div class="panel-heading">
                <b>Payment Date :</b> <input type="text" id="daterange" v-model="daterange" size="25" readonly/>  
                <span class="pull-right">
                    <a href='/sales/report/performance/download?start_date=@{{ start_date }}&end_date=@{{ end_date }}' class="btn btn-primary" v-on:click="download">Download</a>
                </span>                
            </div>
            <div class="panel-body">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th>Status</th>
                            <th>Lead Details</th>
                            <th width="10%">CRE</th>
                            <th width="30%">Payment Details</th>
                            <th width="25%">Product Details</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in payments">
                            <td>
                                @{{ payment.date | format_date2 }}
                            </td>
                            <td>
                                <span class="statusbar status@{{ payment.cart.status.id + payment.cart.state_id }}" title="@{{ payment.cart.status.name + ' : ' + payment.cart.state.name }}"></span>
                            </td>
                            <td>
                                <a href="/lead/@{{ payment.cart.lead.id }}/cart" target="_blank">
                                    @{{ payment.cart.lead.name }}
                                </a>
                                <div v-if="payment.cart.lead.patient">
                                    Patient Id : <a href="/lead/@{{ payment.cart.lead.id }}/payment" target="_blank">
                                        @{{ payment.cart.lead.patient.id }}
                                    </a>
                                </div>
                                <div>
                                    @{{ payment.cart.source.name }}
                                </div>
                            </td>
                            <td>
                                @{{ payment.cart.cre.employee.name }}
                                <div>
                                    <b>TL : </b> 
                                    @{{ payment.cart.cre.employee.supervisor.employee.name }}
                                </div>
                            </td>
                            <td>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>@{{ payment.amount | currency payment.cart.currency.symbol }}</td>
                                        <td>@{{ payment.method.name }}</td>
                                        <td>@{{ payment.date }}</td>
                                        <td>@{{ payment.remark }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table class="table table-bordered">
                                    <tr v-for='product in payment.cart.products'>
                                        <td>@{{ product.name }}</td>
                                        <td>@{{ product.pivot.quantity }}</td>
                                        <td>@{{ product.pivot.price | currency payment.cart.currency.symbol }}</td>
                                        <td>@{{ product.pivot.discount | discount }}%</td>
                                        <td>@{{ product.pivot.amount | currency payment.cart.currency.symbol }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                @{{ payment.cart.amount | currency payment.cart.currency.symbol }}
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
    
</style>
<script>
    var vm = new Vue({
        el: 'body',

        data: {
            loading: false,
            payments: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
        },

        ready: function(){
            this.getPayments();
        },

        methods: {

            getPayments() {
                this.loading = true;
                this.$http.get("/api/getPayments", {'start_date': this.start_date, 'end_date' : this.end_date}).success(function(data){
                    this.payments = data;
                    this.loading = false;
                }).bind(this);
            },
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
    vm.$watch('daterange', function (newval, oldval) {
        this.getPayments();
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