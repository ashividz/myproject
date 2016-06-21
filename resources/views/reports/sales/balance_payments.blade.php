<div class="container-fluid" id="payments">
    <div v-for="cart in carts">
        <div class="panel panel-default">
        <div class="panel-heading">
            <div class="col-md-1">
                <strong>Cart Id @{{ cart.id }}</strong>
            </div>
            <div class="col-md-3">
                @{{ cart.created_at | format_date }}
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                <div class="col-md-2">
                    <div>
                        <label>CRE : </label>@{{ cart.cre.employee.name }}
                    </div>
                    <div>
                        <label>TL </label>: </b>
                        @{{ cart.cre.employee.supervisor.employee.name }}
                    </div>

                    <div>
                        <label>Source : </label> @{{ cart.source.source_name }}
                    </div>
                    <span class="statusbar status@{{ cart.status.id + cart.state_id }}" title="@{{ cart.status.name + ' : ' + cart.state.name }}"></span>
                </div>
                <div class="col-md-2">
                    <a href="/lead/@{{ cart.lead.id }}/cart" target="_blank">
                        @{{ cart.lead.name }}
                    </a>
                    <div>
                        <label>Lead Id : </label>
                        <span>
                            @{{ cart.lead.id }}
                        </span>
                    </div>
                        
                    <div>
                        <label>Patient Id</label> : <a href="/patient/@{{ cart.lead.patient.id }}/diet" target="_blank">
                            @{{ cart.lead.patient.id }}
                        </a>
                    </div>
                    <div>
                        <label>Start Date : </label>
                        <span>
                            @{{ cart.lead.patient.fees[0].start_date | format_date1 }}
                        </span>
                    </div>
                    <div>
                        <label>End Date : </label>
                        <span>
                             @{{ cart.lead.patient.fees[0].end_date | format_date1 }}
                        </span>
                    </div>
                    <div>
                        <label>Duration : </label>
                        <span>
                             @{{ cart.lead.patient.fees[0].duration }}
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Discount</th>
                        </tr>
                        <tr v-for="product in cart.products">
                            <td>
                                @{{ product.name }}
                            </td>
                            <td>
                                @{{ product.pivot.quantity }}
                            </td>
                            <td>
                                @{{ product.pivot.amount | currency cart.currency.symbol }}
                            </td>
                            <td>
                                <span v-if="product.pivot.discount > 0">
                                    @{{ product.pivot.discount + "%" }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-3">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                        </tr>
                        <tr v-for="payment in cart.payments">
                            <td>
                                @{{ payment.date | format_date2 }}
                            </td>
                            <td>
                                @{{ payment.amount | currency cart.currency.symbol }}
                            </td>
                            <td>
                                @{{ payment.method.name }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-2">
                    
                    Button
                </div>
            </div>
        </div>
    </div>
    </div>
    
</div>

<script>
    new Vue({
        el: '#payments',

        data: {
            carts: []
        },

        ready: function(){
            this.getCarts();
        },

        methods: {

            getCarts() {
                $.isLoading({ text: "Loading" });
                this.$http.get("/getBalancePayments")
                .success(function(data){
                    this.carts = data;
                    $.isLoading( "hide" );
                }).bind(this);
            },
        }
    })
</script>