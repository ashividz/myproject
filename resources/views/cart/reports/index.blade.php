    <div class="container-fluid" id="carts">

        <div class="panel panel-default">
            <div class="panel-heading">
                <b>Cart Created Date :</b> <input type="text" id="daterange" v-model="daterange" size="25" readonly/> 
            </div>
            <div class="panel-body">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>Cart Details</th>
                            <th>Lead Details</th>
                            <th width="30%">Payment Details</th>
                            <th width="30%">Product Details</th>
                            <th>Proforma Invoice</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cart in carts">
                            <td>
                                <div>
                                    <b>Cart Id : </b> 
                                    <a href="/cart/@{{ cart.id }}" target="_blank">
                                        @{{ cart.id }}
                                    </a>
                                </div>
                                <div>
                                    <b>Date : </b>@{{ cart.created_at | format_date }}
                                </div>
                                <div>
                                    <b>Creator : </b>@{{ cart.creator.employee.name }}
                                </div>
                                <div>
                                    <b>Amount : </b> @{{ cart.amount | currency cart.currency.symbol }}
                                </div> 
                                <div>
                                    <b>Payment : </b> @{{ cart.payment | currency cart.currency.symbol }}
                                </div>

                                <div v-if="cart.amount - cart.payment > 0" style="color:red">
                                    <b>Balance : </b> @{{ cart.amount - cart.payment | currency cart.currency.symbol }}
                                </div>
                                <div>
                                    <span class="statusbar status@{{ cart.status.id + cart.state_id }}" title="@{{ cart.status.name + ' : ' + cart.state.name }}"></span>
                                </div>                                
                            </td>
                            <td>
                                <div>
                                    <b>Name : </b> @{{ cart.lead.name }}
                                </div> 
                                <div>
                                    <b>Lead Id : </b>
                                    <a href="/lead/@{{ cart.lead_id }}/cart" target="_blank">
                                        @{{ cart.lead_id }}
                                    </a>
                                </div>                                   
                                <div v-if="cart.lead.patient">
                                    <b>Patient Id :</b> <a href="/lead/@{{ cart.lead.id }}/cart" target="_blank">@{{ cart.lead.patient.id }}
                                    </a>
                                </div>
                                <div>
                                    <b>Source : </b>@{{ cart.source.name }}
                                </div>
                                <div>
                                    <b>CRE :</b> @{{ cart.cre.employee.name }}
                                </div>
                                <div>
                                    <b>TL : </b> 
                                    @{{ cart.cre.employee.supervisor.employee.name }}
                                </div>
                            </td>
                            <td>
                                <table class="table table-bordered">
                                    <tr v-for='payment in cart.payments'>
                                        <td>@{{ payment.amount | currency cart.currency.symbol }}</td>
                                        <td>@{{ payment.method.name }}</td>
                                        <td>@{{ payment.date }}</td>
                                        <td>@{{ payment.remark }}</td>
                                    </tr>
                                </table>
                                <hr>
                                <div style="background:#f9f9f9; padding:10px;">
                                    <span>
                                        <i class="fa fa-sticky-note-o"></i>
                                        <b>Comments</b>
                                    </span>
                                    <span class="pull-right">
                                        <a href="/cart/@{{ cart.id }}/comment" data-toggle="modal" data-target="#modal">
                                            <i class="fa fa-plus-square-o"></i>
                                        </a>
                                    </span>
                                    <li v-for="comment in cart.comments">
                                        <b>@{{ comment.text }}</b> 
                                        <small>by</small>
                                        <b>@{{ comment.creator.employee.name }}</b>
                                        <small class="pull-right">
                                            [@{{ comment.created_at | format_date }}]
                                        </small>
                                    </li>
                                </div>
                            </td>
                            <td>
                                <table class="table table-bordered">
                                    <tr v-for='product in cart.products'>
                                        <td>@{{ product.name }}</td>
                                        <td>@{{ product.pivot.quantity }}</td>
                                        <td>@{{ product.pivot.price | currency cart.currency.symbol }}</td>
                                        <td>@{{ product.pivot.discount | discount }}%</td>
                                        <td>@{{ product.pivot.amount | currency cart.currency.symbol }}</td>
                                    </tr>
                                </table>
                            </td>

                            <td style="text-align:center">
                                <div v-for="payment in cart.payments">
                                    <div v-if="payment.payment_method_id == 2 && cart.status_id > 1">
                                        <div vi-if="cart.performa">
                                            @{{ cart.performa }}
                                        </div>
                                        <div else>
                                            <a href="/cart/@{{ cart.id }}/proforma/download" class="btn btn-danger">
                                                <i class="fa fa-download"></i>
                                            </a> 
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <li v-for="invoice in cart.invoices">
                                    <a href="/invoice/@{{ invoice. id }}" v-bind:class="{ 'red': !tracking.invoice }" data-toggle="modal" data-target="#modal" >
                                        @{{ invoice.number }}
                                    </a>
                                </li>
                             @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('registration'))
                                <div v-show="!same(cart.invoices, cart.amount)">
                                    <div v-for="payment in cart.payments" v-if="(payment.payment_method_id == 4 && cart.status_id > 1) || cart.status_id == 4"> 
                                            <a href="/cart/@{{ cart.id }}/invoice" data-toggle="modal" data-target="#modal" class="btn btn-primary">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                    </div>                                 
                                </div>
                            @endif
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
        el: '#carts',

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
                this.$http.get("/api/getCarts", {'start_date': this.start_date, 'end_date' : this.end_date}).success(function(data){
                    this.carts = data;
                    this.loading = false;
                }).bind(this);
            },
            same(list, amount) {
                var payment = list.reduce(function(total, item) {
                    return total + parseInt(item['amount'])
                }, 0)

                if(payment == amount) {
                    return true;
                }
                return false;
            }
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
    Vue.filter('total', function (list, key1) {
        return list.reduce(function(total, item) {
            return total + parseInt(item[key1])
        }, 0)
    })
    vm.$watch('daterange', function (newval, oldval) {
        this.getCarts();
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