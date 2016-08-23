@extends('master')

@section('content')
<div class="container-fluid" id="carts">
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Cart Created Date :</b> <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
            <div class="roles" style="display:inline;padding:5px; border:1px solid #eee;margin-left:30px">
                All : <input type="radio" name="role" value="all" v-model="role" debounce="5000" checked>
                <span style="margin-left:30px">
                    Nutritionist : <input type="radio" name="role" value="nutritionist" v-model="role" debounce="5000" >   
                </span>
                <span style="margin-left:30px">
                    CRE : <input type="radio" name="role" value="cre" v-model="role" debounce="5000" > 
                </span>
            </div> 
            <div style="display:inline;padding:5px; border:1px solid #eee;margin-left:30px">
                <span>
                    All : <input type="radio" v-model="filter" debounce="5000" value="all" checked>
                </span> 
                <span style="margin-left:30px">
                    PI : <input type="radio" v-model="filter" debounce="5000" value="pi">
                </span>    
                <span style="margin-left:30px">
                    FedEx : <input type="radio" v-model="filter" debounce="5000" value="fedex">
                </span>    
                <span style="margin-left:30px">
                    Paid : <input type="radio" v-model="filter" debounce="5000" value="paid">
                </span> 
            </div>  
            <div style="display:inline;padding:5px; border:1px solid #eee;margin-left:30px">
                <span>
                    Diets : <input type="checkbox" v-model="categories" value="1" debounce="5000" checked>
                </span> 
                <span>
                    Goods : <input type="checkbox" v-model="categories" value="2" debounce="5000" checked>
                </span>
                <span>
                    BT : <input type="checkbox" v-model="categories" value="3" debounce="5000" checked>
                </span> 
                <span>
                    Books : <input type="checkbox" v-model="categories" value="4" debounce="5000" checked>
                </span>  
            </div>
        </div>
    </div>

    <div v-for="cart in carts">
        <cart-approval :cart.sync="cart" :methods="methods" v-if="! (cart.status_id == 4 && cart.state_id == 3)"></cart-approval>
    </div>
</div>
<template id="cart-approval-template">
    <div class="panel panel-info">
        <div class="panel-heading" v-show="!expand" v-bind:class="{'benefitCart': cart.benefitCart}">
            <div class="row">
                <div class="col-md-2">
                    <div>
                        <label>Cart Id :</label>
                        <a href="/cart/@{{ cart.id }}/" target="_blank">
                            @{{ cart.id }}
                        </a>
                    </div>
                    <div>
                        <span class="statusbar status@{{ cart.status_id }}@{{ cart.state_id }}"></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <label>Created By :</label> @{{ cart.creator.employee.name }}
                    </div>
                    <div>
                        <label>Created At :</label> @{{ cart.created_at | format_date }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div>
                        <label>Payment :</label> @{{ cart.payments[0].method.name }}
                    </div>
                     <div>
                        <label>Date :</label> @{{ cart.payments[0].created_at | format_date2 }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div v-if="cart.status_id == 2">
                        <label>Max Discount :</label> @{{ cart.products | max 'discount' }}%
                    </div>
                    <div v-for="shipping in cart.shippings" v-else>
                        <div>
                            <label>Carrier :</label> @{{ shipping.carrier.name }}
                        </div>
                         <div>
                            <span class="statusbar @{{ shipping.status | lowercase }}"></span>
                        </div>
                    </div>                        
                </div>
                <div class="col-md-4">
                    <div class="pull-right" @click="toggleExpand">
                        <i class="fa fa-plus-circle fa-2x"></i>
                    </div>
                    <div class="form-horizontal">
                         <div class="form-group">
                            <div class="col-md-3">
                                <input type="radio" v-model="state" value="1" @click="canApproveCart"> Approve
                            </div>
                            <div class="col-md-3">                                
                                <input type="radio" v-model="state" value="2" @click="canApproveCart"> Reject 
                            </div>   
                            <div class="col-md-3" v-show="!cancelled">
                                <button class="btn btn-primary" v-bind:disabled="!state || !approvePayment || !approveDiscount || loading" @click="approve">Save</button>
                            </div>                           
                        </div> 
                        <div class="col-md-8">
                            <div class="form-group">
                                <textarea v-model="remark" class="form-control"></textarea>
                            </div>                            
                        </div>                      
                    </div>
                </div>                
            </div>
        </div>
        <div class="panel-body" v-show="expand" v-bind:class="{'benefitCart': cart.benefitCart}">
            <div class="row">
                <div class="col-md-2"> 
                    <table class="table table-bordered table-condensed table-striped">
                        <tr>
                            <td colspan="2">
                                <span class="statusbar status@{{ cart.status_id + cart.state_id }}"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Cart Id :</label></td>
                            <td>
                                <a href="/cart/@{{ cart.id }}/" target="_blank">@{{ cart.id }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Created By :</label></td>
                            <td>@{{ cart.creator.employee.name }}</td>
                        </tr>
                        <tr>
                            <td><label>Created At :</label></td>
                            <td>@{{ cart.created_at | format_date }}</td>
                        </tr>
                        <tr>
                            <td><label>Lead Id :</label></td>
                            <td>@{{ cart.lead_id }}</td>
                        </tr>
                        <tr>
                            <td><label>Name :</label></td>
                            <td> @{{ cart.lead.name }}</td>
                        </tr>
                        <tr v-show="cart.lead.patient">
                            <td><label>Patient Id :</label></td>
                            <td>@{{ cart.lead.patient.id }}</td>
                        </tr>
                        <tr>
                            <td><label>Source :</label></td>
                            <td>@{{ cart.source.name }}</td>
                        </tr>
                        <tr>
                            <td><label>CRE :</label></td>
                            <td>@{{ cart.cre.employee.name }}</td>
                        </tr>
                        <tr>
                            <td><label>TL :</label></td>
                            <td>@{{ cart.cre.employee.supervisor.employee.name }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in cart.products">
                                <td>
                                    @{{ product.name }}
                                </td>
                                <td>
                                    @{{ product.pivot.quantity }}
                                </td>
                                <td>
                                    <span v-if="product.pivot.product_offer_id">
                                        FREE
                                    </span>
                                    <span v-else>
                                        @{{ cart.currency.symbol}} @{{ product.pivot.price }}
                                    </span>
                                </td>
                                <td>
                                    @{{ product.pivot.coupon}} 
                                    <span v-if="product.pivot.discount > 0">
                                        <small>( @{{ product.pivot.discount }}%)</small>
                                    </span>
                                </td>
                                <td>
                                    @{{ product.pivot.amount | currency cart.currency.symbol }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-md-12" style="background-color:#5697CC;">
                        <div class="col-md-3"><label>Payment Date</label></div>
                        <div class="col-md-3"><label>Method</label></div>
                        <div class="col-md-3"><label>Amount</label></div>
                        <div class="col-md-3"><label>Remark</label></div>
                    </div>
                    <div v-for="payment in cart.payments">
                        <editable-field :payment.sync="payment" :methods="methods" />
                    </div>
                    <!--
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Payment Date</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="payment in cart.payments">
                                <td>
                                    @{{ payment.date | format_date2 }}
                                </td>
                                <td>
                                    @{{ payment.method.name }}
                                </td>
                                <td>
                                    
                                        @{{ cart.currency.symbol}} @{{ payment.amount }}
                                </td>
                                <td>
                                    @{{ payment.remark }} 
                                </td>
                            </tr>
                        </tbody>
                    </table> -->
                </div>                
                <div class="col-md-2">

                    <div>
                        <label>Max Discount :</label> @{{ cart.products | max 'discount' }}%
                    </div>
                    <hr>
                    <div v-for="shipping in cart.shippings">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="2">
                                    <span class="statusbar @{{ shipping.status | lowercase }}"></span>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Carrier :</label></td>
                                <td> @{{ shipping.carrier.name }}</td>
                            </tr>

                            <tr v-show="shipping.estimated_delivery_timestamp">
                                <td><label>Est. Delivery Time :</label></td>
                                <td>@{{ shipping.estimated_delivery_timestamp | format_date }}</td>
                            </tr>
                            <tr v-show="shipping.actual_delivery_timestamp">
                                <td><label>Act. Delivery Time:</label></td>
                                <td>@{{ shipping.actual_delivery_timestamp | format_date }}</td>
                            </tr>
                        </table>
                    </div> 
                    <div>
                        <table class="table table-bordered">
                            <tr v-if="cart.proforma">
                                <td><label>Proforma :</label></td>
                                <td> 
                                    <a href="/cart/@{{ cart.id }}/proforma/download">@{{ cart.proforma.id }}</a>
                                </td>
                            </tr>
                            <tr v-for="invoice in cart.invoices">
                                <td><label>Invoice :</label></td>
                                <td> 
                                    <a href="/invoice/@{{ invoice.id }}" data-toggle="model" data-target="#modal">@{{ invoice.number }}</a>
                                </td>
                            </tr>
                        </table>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="pull-right" @click="toggleExpand">
                        <i class="fa fa-minus-circle fa-2x"></i>
                    </div> 
                    <div class="form-horizontal">
                        <div class="col-md-3">
                            <input type="radio" v-model="state" value="1" @click="canApproveCart"> Approve
                        </div>
                        <div class="col-md-3">                                
                            <input type="radio" v-model="state" value="2" @click="canApproveCart"> Reject 
                        </div>   
                        <div class="col-md-3" v-show="!cancelled">
                            <button class="btn btn-primary" v-bind:disabled="!state || !approvePayment || !approveDiscount" @click="approve">Save</button>
                        </div>  
                        <div class="col-md-8" style="margin-top:20px;">
                            <div class="form-group">
                                <textarea v-model="remark" class="form-control">@{{ remark }}</textarea>
                            </div>                            
                        </div>
                    </div>
                    <div>
                        <table class="table table-bordered table-striped table-condensed">
                            <tr v-for="step in cart.steps">
                                <td>
                                    <b>@{{ step.status.name }} @{{ step.state.name }} </b>
                                </td>

                                <td>
                                    @{{ step.remark }}
                                </td>
                                <td>
                                     <b>@{{ step.creator.employee.name }}</b>
                                </td>
                                <td>
                                    <em>@{{ step.created_at | format_date }}</em>
                                </td>                                
                            </tr>
                        </table>
                    </div>
                    <div>
                        <table class="table table-bordered table-striped table-condensed">
                            <tr v-for="comment in cart.comments">
                                <td width="50%">
                                    <b>@{{ comment.text }}</b>
                                </td>
                                <td>
                                     <b>@{{ comment.creator.employee.name }}</b>
                                </td>
                                <td>
                                    <em>@{{ comment.created_at | format_date }}</em>
                                </td>                                
                            </tr>
                        </table>
                    </div>
                </div>
                           
            </div>
                   
        </div>
    </div>
</template>
<template id="editable-field">
    <div class="form-group col-xs-12 col-md-12">
        <div v-show="edit">
            <span class="col-xs-4 col-md-3" style="padding:0">
                <input type="text" v-model="payment.date" class="form-control" @keyup.13="update" v-transition="fade"/>
            </span>
            <span class="col-xs-4 col-md-6">
                <select class="form-control" v-model="payment.payment_method_id" @change="update">
                    <option v-for="method in methods" :value="method.id">@{{ method.name }}</option>
                </select>
            </span>
            <span class="col-xs-4 col-md-3">
                <input type="text" v-model="payment.amount"  class="form-control"  @keyup.13="update" v-transition="fade"/>
            </span>
            </span>
        </div>
        <div v-else>
            <span class="col-xs-4 col-md-2" @click="toggleEdit">@{{ payment.date | format_date2 }}</span>
            <span class="col-xs-4 col-md-5" @click="toggleEdit">@{{ payment.method.name }}</span>
            <span class="col-xs-4 col-md-2" @click="toggleEdit">@{{ payment.amount }}</span>
            <span class="col-xs-4 col-md-3">@{{ payment.remark }}</span>
        </div>
    </div>
</template>
@{{methods}}
@include('partials.modal')
<script>
Vue.component('editableField', {
    mixins: [ VueFocus.mixin ],
    template: '#editable-field',
    props: ['payment', 'methods'],
    data: function() {
        return {
            edit: false
        }
    },

    methods: {
        update() {
            this.$http.patch("/cart/payment/" + this.payment.id, {
                amount: this.payment.amount,
                payment_method_id: this.payment.payment_method_id,
                date: this.payment.date,
            })
            .success(function(data){
                this.payment = data;
                toastr.success('Cart Payment updated', 'Success!');
            })       
            .error(function(errors) {
                //this.toastErrors(errors);
                console.log(errors);
                toastr.error("Error occured. "+errors.error.description, "Error!");
            }).bind(this);

            this.edit = false;
        },

        toggleEdit() {
            this.edit = true;
        },

        toggleDelete() {
            this.$http.patch("/master/status/" + this.status.id + "/delete")
            .success(function(data){
                this.$parent.statuses = data;
                this.$parent.toastDelete(this.status.deleted_at);
            }).bind(this);
        }
    }
})
Vue.component('cartApproval', {
    template: "#cart-approval-template",
    props: ['cart', 'methods'],
    data : function() {
        return {
            loading: false,
            expand : false,
            approvePayment : true,
            approveDiscount : true,
            remark: '',
            discount_id: '',
            state: '',
            cancelled: false
        }
    },
    ready: function() {
        this.isBenefitCart();
    },

    methods: {

         isBenefitCart(){

            this.$http.get("/cart/isBenefitCart/" + this.cart.id).success(function(data){
                if(data)
                {
                   
                    this.cart = Object.assign({}, this.cart, { benefitCart: true});
                    if(this.cart.state_id=='2')
                        this.cancelled = true; 

                }
                else
                    this.cart = Object.assign({}, this.cart, { benefitCart: false});
            })
            .error(function(data){
                
            })
            .bind(this);

        },

        toggleExpand() {
            this.expand = !this.expand;
        },

        canApproveCart() {
            /*if (this.state == 1) {

                this.remark = this.cart.status_id == 4 ? "Order Approved" : "Cart Approved";

            } else if (this.state == 2) {

                this.remark = this.cart.status_id == 4 ? "Order Rejected" : "Cart Rejected";
            }*/

            if (this.cart.status_id == 2) {
                this.$http.get("/canApproveDiscount", {
                    cart_id: this.cart.id
                }).success(function(data){
                    if (data.status == 'Error!') {
                        toastr.error(data.message, data.status);
                        this.approveDiscount = false;
                    } else if (data == false) {
                        this.approveDiscount = data;
                    }else if (data == true) {
                        this.approveDiscount = data;
                    } else {
                        this.remark = "Discount approval upto " + data.discount + "%"; 
                        this.discount_id = data.discount_id;
                        this.approveDiscount = true;
                    }
                    
                }).bind(this);

            } else if (this.cart.status_id == 3) {
                this.$http.get("/canApprovePayment", {
                    cart_id: this.cart.id
                }).success(function(data){
                    this.approvePayment = data;
                }).bind(this);
            }                    
        },

        approve() {
            this.loading = true;
            this.$http.post("/cart/" + this.cart.id + "/approve", {
                cart_id: this.cart.id,
                state: this.state,
                discount_id: this.discount_id,
                remark: this.remark,
                benefitCart: this.cart.benefitCart,
            }).success(function(data){
                if (data.status == 'Success!') {
                    toastr.success(data.message, data.status);
                    this.remark = '';
                    
                } else {
                    toastr.error(data.message, data.status);
                }                    
                this.loading = false;
                this.findCart();
            })
            .error(function(data){
                toastr.error(data.message, data.status);
                this.loading = false;
            })
            .bind(this);
        },

        findCart() {
            this.$http.get("/findCart", {
                cart_id: this.cart.id,
                statuses: this.$parent.statuses
            }).success(function(data){
                if (data) {
                    this.cart = data;
                } else {
                    this.$parent.carts.$remove(this.cart)
                }
            }).bind(this);
        },

        getPaymentMethods() {
            this.$http.get("/getPaymentMethods")
            .success(function(data){
               this.payment_methods = data;
            }).bind(this);
        }
    },

    computed: function() {
        if (this.cart.status_id == 2) {
            this.approveDiscount = false;

        } else if (this.cart.status_id == 3) {
            this.approvePayment = false;
        }
    }
});
new Vue({
    el: '#carts',

    data: {
        //loading: false,
        carts: [],
        daterange: '{{ Carbon::now()->format('Y-m-01') }} - {{ Carbon::now()->format('Y-m-d') }}',
        start_date: '',
        end_date: '',
        statuses: [0],
        methods: []
    },

    ready: function(){
        this.getPaymentMethods(),
        this.getCartApproverStatuses(),
        this.$watch('daterange', function (newval, oldval) {
            this.getCarts();
        })
    },

    methods: {

        getCarts() {
            $.isLoading({ text: "Loading" });
            this.$http.get("/api/getCarts", {
                start_date: this.start_date, 
                end_date: this.end_date,
                statuses: this.statuses
            }).success(function(data){
                this.carts = data;
                $.isLoading( "hide" );
            }).bind(this);
        },

        getCartApproverStatuses() {
            this.$http.get("/getCartApproverStatuses")
            .success(function(data){
                if (data.length > 0) {
                    this.statuses = data;
                    console.log(data);
                }                    
                
                this.getCarts();
            }).bind(this);
        },
        getPaymentMethods() {
            this.$http.get("/getPaymentMethods")
            .success(function(data){
               this.methods = data;
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

Vue.filter('max', function (list, key) {
    if (list.length == 0) {
        return 0;
    }
    return Math.max.apply(Math,list.map(function(o){return o.pivot.discount;}))
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
<style type="text/css">
.panel-info {
    border-color: #ddd;
}
.panel-info>.panel-heading {
    background-color: #fff; 
    border-color: #ddd;
    color: #111;
}
.panel-info>.panel-heading.benefitCart, .panel-info>.panel-body.benefitCart, .panel-info>.panel-body.benefitCart .table-striped > tbody > tr:nth-child(2n+1)
{
    background: #e0ebeb;
   background: #ffe6cc;
}
</style>
@endsection