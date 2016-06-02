<div class="container" id="package">
    <div class="panel">
        <div class="panel-heading">
            <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <table class="table table-bordered lead_status" id="table">
                    <thead>
                        <tr>
                            <th>Lead Id</th>
                            <th>Cart Id</th>
                            <th>Name</th>
                            <th>CRE</th>
                            <th>Nutritionist</th>
                            <th>Location</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Upgrade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cart in carts">
                            <td>
                                <a href="/lead/@{{ cart.lead_id }}/cart" target="_blank">
                                    @{{ cart.lead_id }}
                                </a>                                
                            </td>
                            <td>
                                <a href="/cart/@{{ cart.id }}" target="_blank">
                                    @{{ cart.id }}
                                </a>
                            </td>
                            <td>
                                @{{ cart.lead.name }}
                            </td>
                            <td>
                                @{{ cart.creator.employee.name }}
                            </td>
                            <td>
                                @{{ cart.lead.patient.nutritionist }}
                            </td>
                            <td>
                                @{{ cart.lead.city }}
                                @{{ cart.lead.state }}
                                @{{ cart.lead.country }}
                            </td>
                            <td>
                                @{{ cart.fee.start_date | format_date2 }}
                            </td>
                            <td>
                                @{{ cart.fee.end_date | format_date2 }}
                            </td>

                            <td>
                                <div v-for='cart1 in cart.lead.carts'>
                                    <div v-if="(cart1.created_at > cart.created_at) && cart1.status_id == 4">
                                        <a href="/cart/@{{ cart1.id}}" target="_blank">
                                            <b>Cart Id : </b>@{{ cart1.id }}
                                        </a>
                                        <div v-for='product1 in cart1.products' v-if="product1.product_category_id == 1">
                                            @{{ product1.name }}
                                            <div>
                                                <b>Amount : </b>@{{ cart1.currency.symbol }} @{{ product1.pivot.amount }}
                                            </div>
                                            <div v-if="product1.pivot.discount > 0">
                                                <b>Discount : </b>
                                                @{{ product1.pivot.discount }} %
                                            </div>
                                            <div>
                                                <b>Start Date : </b>
                                                @{{ cart1.fee.start_date | format_date2 }}
                                            </div>
                                            <div>
                                                <B>End Date : </B>
                                                @{{ cart1.fee.end_date | format_date2  }}
                                            </div>
                                            
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
      return moment(value).format('D MMM, YY');
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