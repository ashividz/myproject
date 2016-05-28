<div class="container" id="package">
    <div class="panel">
        <div class="panel-heading">
            <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <table class="table table-bordered lead_status">
                    <thead>
                        <tr>
                            <th>Lead Details</th>
                            <th width="15%">Patient Details</th>
                            <th>Cart Details</th> 
                            <th>Product Details</th>
                            <th>Payment Details</th>
                            <th>Extension Payment Details</th>
                            <th>Extension Payment Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cart in carts">
                            <td>
                                <div>
                                    <b>Name :</b>
                                        @{{ cart.lead.name }}
                                    </a>
                                </div>

                                <div>
                                    <b>Lead Id :</b>
                                    <a href="/lead/@{{ cart.lead_id }}/cart" target="_blank">
                                        @{{ cart.lead_id }}
                                    </a>
                                </div>
                                <div>
                                    <b>CRE :</b>
                                        @{{ cart.cre.employee.name }}
                                    </a>
                                </div>
                                <div>
                                    <b>Source :</b>
                                        @{{ cart.source.name }}
                                    </a>
                                </div>
                            </td>
                            <td>                                
                                <div v-if="cart.lead.patient">
                                    <b>Patient Id :</b>
                                    <a href="/patient/@{{ cart.lead.patient.id }}/diet" target="_blank">
                                        @{{ cart.lead.patient.id }}
                                    </a>
                                </div>

                                <div>
                                    <b>Start Date :</b>
                                        @{{ cart.lead.patient.fees[0].start_date | format_date2 }}
                                    </a>
                                </div>

                                <div v-bind:class="{ 'green' : today >= cart.lead.patient.fees[0].end_date }">
                                    <b>End Date :</b>
                                        @{{ cart.lead.patient.fees[0].end_date | format_date2 }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                
                                <div>
                                    <b>Creator :</b>
                                        @{{ cart.creator.employee.name }}
                                    </a>
                                </div>
                                <div>
                                    <b>Date :</b>
                                        @{{ cart.created_at | format_date }}
                                    </a>
                                </div>

                                <div>
                                    <b>Cart Id :</b>
                                    <a href="/cart/@{{ cart.id }}" target="_blank">
                                        @{{ cart.id }}
                                    </a>
                                </div>
                                <div>
                                    <b>Amount :</b>
                                        @{{ cart.amount | currency cart.currency.symbol }}
                                    </a>
                                </div>
                                <div>
                                    <b>Payment :</b>
                                        @{{ cart.payment | currency cart.currency.symbol }}
                                    </a>
                                </div>
                                <div v-if="cart.amount - cart.payment > 0" style="color:red">
                                    <b>Balance :</b>
                                        @{{ cart.amount - cart.payment | currency cart.currency.symbol }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div v-for="product in cart.products">
                                    @{{ product.name }}
                                    (@{{ product.pivot.quantity }}) 
                                    <span v-if="product.pivot.discount > 0" class="pull-right">
                                        <em>[@{{ product.pivot.discount }} %] </em>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div v-for="payment in cart.payments">
                                    @{{ payment.amount | currency cart.currency.symbol }} 
                                    <small>by</small>
                                    @{{ payment.method.name }}
                                </div>
                            </td>

                            <td>
                                <div v-for='cart1 in cart.lead.carts'>
                                    <div v-if='cart1.id <> cart.id'> 
                                        <div v-for='product1 in cart1.products'>
                                            @{{ product1.name }}
                                            (@{{ product1.pivot.quantity }}) 
                                            <span v-if="product1.pivot.discount > 0" class="pull-right">
                                                <em>[@{{ product1.pivot.discount }} %] </em>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div v-for='cart1 in cart.lead.carts'>
                                    <div v-if='cart1.id <> cart.id'> 
                                        <div v-for='payment1 in cart1.payments'>
                                            @{{ payment1.amount | currency cart.currency.symbol }} 
                                            <small>by</small>
                                            @{{ payment1.method.name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    //var tab = require('vue-strap').tab;
    var vm1 = new Vue({
        el: '#package',

        data: {
            carts: [],
            loading: false,
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            today: '{{ Carbon::now()->format('Y-m-d') }}'
        },

        ready: function(){
            this.getCarts();
        },

        methods: {
            getCarts() {
                this.$http.get("/api/getPackageExtensions", {
                    'start_date': this.start_date, 
                    'end_date' : this.end_date
                })
                .success(function(data){
                    this.carts = data;
                }).bind(this);
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
    vm1.$watch('daterange', function (newval, oldval) {
        this.getCarts();
    })
</script> 
<script src="/js/daterange.js"></script>
<style type="text/css">
.green {
    color: green;
}
</style>