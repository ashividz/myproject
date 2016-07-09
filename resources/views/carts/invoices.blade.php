@extends('master')

@section('content')
<div class="container-fluid" id="carts">
    <div class="panel panel-default">
        <div class="panel-heading">
            Cart Created Date :</b> <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
        </div>
    </div>

    <div v-for="cart in carts">
        <cart-field :cart.sync="cart"></cart-field>
    </div>
</div>
<template id="cart-field">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="col-md-12">                
                <div class="pull-right" @click="toggleExpand">
                    <i class="fa @{{ !expand ? 'fa-plus-circle' : 'fa-minus-circle' }} fa-2x"></i>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-md-2">
                     <div>
                        <label>Cart Id : </label> 
                        <a href="/cart/@{{ cart.id }}" target="_blank">
                            @{{ cart.id }}
                        </a>
                    </div>
                    <div>
                        <label>Date : </label>
                        @{{ cart.created_at | format_date }}
                    </div>
                    <div>
                        <label>Creator : </label>
                        @{{ cart.creator.employee.name }}
                    </div>
                    <div>
                        <label>Amount : </label> 
                        @{{ cart.amount | currency cart.currency.symbol }}
                    </div> 
                    <div>
                        <label>Payment : </label> 
                        @{{ cart.payment | currency cart.currency.symbol }}
                    </div>

                    <div v-if="cart.amount - cart.payment > 0" style="color:red">
                        <label>Balance : </label> @{{ cart.balance | currency cart.currency.symbol }}
                    </div>
                    <div>
                        <span class="statusbar status@{{ cart.status.id }}@{{ cart.state_id }}" title="@{{ cart.status.name + ' : ' + cart.state.name }}"></span>
                    </div>      
                </div>
                <div class="col-md-2">
                    <div class="col-md-12">
                        <label>Name : </label> @{{ cart.lead.name }}
                    </div> 
                    <div class="col-md-12">
                        <label>Lead Id : </label>
                        <a href="/lead/@{{ cart.lead_id }}/cart" target="_blank">
                            @{{ cart.lead_id }}
                        </a>
                    </div>                                   
                    <div class="col-md-12" v-if="cart.lead.patient">
                        <label>Patient Id :</label> 
                        <a href="/lead/@{{ cart.lead.id }}/cart" target="_blank">@{{ cart.lead.patient.id }}
                        </a>
                    </div>
                    <div class="col-md-12">
                        <label>Address : </label>
                        <span v-if="cart.shipping_address">
                            @{{ cart.shipping_address.city }}, @{{ cart.shipping_address.country }}
                        </span>
                        <span v-else>
                            @{{ cart.lead.city }}, @{{ cart.lead.country }}    
                        </span>
                        
                    </div>
                    <div class="col-md-12">
                        <label>Source : </label>
                        @{{ cart.source.name }}
                    </div>
                    <div class="col-md-12">
                        <label>CRE :</label> @{{ cart.cre.employee.name }}
                    </div>
                    <div class="col-md-12">
                        <label>TL : </label> 
                        @{{ cart.cre.employee.supervisor.employee.name }}
                    </div>
                </div> 
                <div class="col-md-5">
                    
                    <table class="table table-bordered">
                        <tr>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Remark</th>

                        @if(Auth::user()->canGeneratePI())
                            <th>Proforma</th>
                        @endif

                        </tr>
                        <tr v-for='payment in cart.payments'>
                            <td>@{{ payment.date | format_date2 }}</td>
                            <td>@{{ payment.amount | currency cart.currency.symbol }}</td>
                            <td>@{{ payment.method.name }}</td>
                            <td>
                                @{{ payment.remark }}
                                <div v-if="payment.delivery_time">
                                    <label>Delivery Time: </label>
                                    @{{ payment.delivery_time | format_date3 }}
                                </div>
                            </td>

                        @if(Auth::user()->canGeneratePI())
                            <td>
                                <div v-if="(payment.payment_method_id == 2 || payment.payment_method_id == 3) && cart.status_id > 1">
                                    <a href="/cart/@{{ cart.id }}/proforma/download" class="btn btn-danger">
                                        <i class="fa fa-download"></i>
                                    </a> 
                                </div>
                            </td>
                        @endif

                        </tr>
                    </table>
                </div>

                <!-- Invoices -->  
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="pull-right">
                                <i class="fa @{{ addInvoice ? 'fa-minus-square-o' : 'fa-plus-square-o' }} fa-2x" @click="toggleInvoiceForm"></i>
                            </span>
                            <div class="panel-title">Invoice</div>
                        </div>
                        <div class="panel-body"> 

                    @if(Auth::user()->canUploadInvoice())                      
                            <div class="form-horizontal" v-show="addInvoice">
                                <div class="form-group">
                                    <label class="col-md-4">Invoice No : </label>
                                    <div class="col-md-7">
                                        <input type="text" v-model="number" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Amount : </label>
                                    <div class="col-md-7">
                                        <input type="text" v-model="amount" class="form-control">
                                    </div>                            
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="file" id="file-@{{ cart.id }}" name="file" v-el="file">
                                    </div>                            
                                </div>
                                <div class="form-group text-center">
                                    <button class="btn btn-danger" @click="toggleInvoiceForm">Cancel</button>
                                    <button class="btn btn-primary" @click="storeInvoice(cart.id)">Save</button>
                                </div>
                            </div>
                    @endif                           
                            <table class="table table-bordered" v-show="cart.invoices.length > 0 && !addInvoice">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="invoice in cart.invoices">
                                        <td>
                                            <a href="/invoice/@{{ invoice. id }}" v-bind:class="{ 'red': !tracking.invoice }" target="_blank" >
                                                @{{ invoice.number }}
                                            </a>
                                        </td>
                                        <td>
                                            @{{ invoice.amount | currency cart.currency.symbol }}
                                        </td>
                                        <td>
                                            @{{ invoice.created_at | format_date2 }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body" v-show="expand">
                <div class="col-md-3">
                    <h4>Cart Steps</h4>
                    <li v-for="step in cart.steps">
                        <span class="@{{ step.state.css_class }}">@{{ step.status.name + ' ' +step.state.name }}</span> 
                        <small>by</small> @{{ step.creator.employee.name }}
                        <small class="pull-right"><em>@{{ step.created_at | format_date }}</em></small>
                    </li>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="pull-right">
                                <i class="fa @{{ addComment ? 'fa-minus-square-o' : 'fa-plus-square-o' }} fa-2x" @click="toggleCommentBox"></i>
                            </span>
                            <span>                        
                                <div class="panel-title">COMMENTS</div>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div  v-show="addComment">
                                <div class="form-group">
                                    <textarea v-model="comment" class="form-control" placeholder="Add Comment"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button class="btn btn-danger" @click="toggleCommentBox">Cancel</button>
                                    <button class="btn btn-primary" @click="storeComment">Save</button>
                                </div>
                            </div>
                            <li v-for="comment in cart.comments" v-show="!addComment" transition="expand">
                                <b>@{{ comment.text }}</b> 
                                <small>by</small>
                                <b>@{{ comment.creator.employee.name }}</b>
                                <small class="pull-right">
                                    [@{{ comment.created_at | format_date }}]
                                </small>
                            </li>                            
                        </div>                    
                    </div>                           
                </div>  
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Amount</th>
                        </tr>
                        <tr v-for='product in cart.products'>
                            <td>@{{ product.name }}</td>
                            <td>@{{ product.pivot.quantity }}</td>
                            <td>@{{ product.pivot.price | currency cart.currency.symbol }}</td>
                            <td>@{{ product.pivot.discount | discount }}%</td>
                            <td>@{{ product.pivot.amount | currency cart.currency.symbol }}</td>
                        </tr>
                    </table>
                </div>  
            </div>
        </div>
    </div>
</template>
<template id="cart-field-editable">
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
<script>
Vue.component('cartField', {
    mixins: [ VueFocus.mixin ],
    template: '#cart-field',
    props: ['cart', 'carriers'],
    data: function() {
        return {
            edit: false,
            expand: false,
            addComment: false,
            addInvoice: false,
            addShipping: false,
            number: '',
            amount: '',
            file: '',
            created_by: {{ Auth::id() }},
            carriers: []
        }
    },

    ready: function() {
        this.carriers = this.$parent.carriers;
    },

    methods: {
        toggleExpand() {
            this.expand = !this.expand;
        },

        toggleCommentBox() {
            this.addComment = !this.addComment;
        },

        toggleInvoiceForm() {
            this.addInvoice = !this.addInvoice;
        },

        toggleShippingForm() {
            this.addShipping = !this.addShipping;
        },

        storeComment() {
            this.$http.post("/cart/"+ this.cart.id +"/comment", {
                text: this.comment,
                created_by: {{ Auth::id() }}
            }).success(function(data){
                toastr.success("Comment added", "Success!");
                this.comment = '',
                this.cart.comments = data;
            }).bind(this);
            this.toggleCommentBox();
        },

        storeInvoice(id) {
            //e.preventDefault();
            //var files = this.$$.file.files;
            var files = $('#file-' + id).prop('files');
            var data = new FormData();
            // for single file
            data.append('invoice', files[0]);
            data.append('number',this.number);
            data.append('amount', this.amount);

            this.$http.post(this.$http.post("/cart/"+ this.cart.id +"/invoice", data)
            .success(function (data, status, request) {
                toastr.success("Invoice added", "Success!");
                    this.number = '',
                    this.amount = '',
                    this.file = '',
                    this.cart.invoices = data;
                    console.log(data, status, request);
            }).error(function (data, status, request) {
                toastr.error(data.message, "Error!");
            })).bind(this);
            this.toggleInvoiceForm();
        },

        storeShipping() {
            this.$http.post("/cart/"+ this.cart.id +"/shipping", {
                carrier_id: this.carrier_id,
                tracking_id: this.tracking_id,
                created_by: {{ Auth::id() }}
            }).success(function(data){
                toastr.success("Shipping added", "Success!");
                this.carrier_id = '',
                this.tracking_id = '',
                this.cart.shippings = data;
            }).bind(this);
            this.toggleShippingForm();
        },

        same(list, amount) {
            var payment = list.reduce(function(total, item) {
                return total + parseInt(item['amount'])
            }, 0)

            if(payment == amount) {
                return true;
            }
            return false;
        },
    }
})
new Vue({
    el: '#carts',

    data: {
        loading: false,
        carts: [],
        daterange: '{{ Carbon::now()->subDays(7)->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
        start_date: '',
        end_date: ''
    },

    ready: function(){
        this.getCarts();
        this.$watch('daterange', function (newval, oldval) {
            this.getCarts();
        })
    },

    methods: {

        getCarts() {
            $.isLoading({ text: "Loading" });
            this.$http.get("/getCartsWithoutInvoice", {
                start_date: this.start_date, 
                end_date: this.end_date,
            }).success(function(data){
                this.carts = data;
                $.isLoading( "hide" );
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
Vue.transition('fade', {
  css: false,
  enter: function (el, done) {
    // element is already inserted into the DOM
    // call done when animation finishes.
    $(el)
      .css('opacity', 0)
      .animate({ opacity: 1 }, 1000, done)
  },
  enterCancelled: function (el) {
    $(el).stop()
  },
  leave: function (el, done) {
    // same as enter
    $(el).animate({ opacity: 0 }, 1000, done)
  },
  leaveCancelled: function (el) {
    $(el).stop()
  }
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
</style>
@endsection