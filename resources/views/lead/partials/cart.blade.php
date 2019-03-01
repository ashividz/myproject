@extends('lead.index')

@section('top')

<div class="" id="cart">
    <div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="panel-title">Add Cart</div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>                        
                        <td width="25%">
                            <label>DOB <div class='asterix'>*</div> :</label> 
                            <a href="/lead/@{{ lead.id }}/viewPersonalDetails" target="_blank">
                                @{{ lead.dob | format_date1 }}  
                            </a>
                        </td>
                        <td>
                            <label>Gender <div class='asterix'>*</div> : </label>   
                            <a href="/lead/@{{ lead.id }}/viewPersonalDetails" target="_blank">
                                @{{ lead.gender }}  
                            </a>
                        </td>
                        <td>
                            <label>Email <div class='asterix'>*</div> : </label>  
                            <a href="/lead/@{{ lead.id }}/viewContactDetails" target="_blank" :class="{'required': !lead.email}">
                                @{{ lead.email }}  
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Phone <div class='asterix'>*</div> :</label>
                            <a href="/lead/@{{ lead.id }}/viewContactDetails" target="_blank" :class="{'required': !lead.phone}">
                                @{{ lead.phone }}  
                            </a>
                        </td>
                        <td>
                            <label>Mobile : </label> 
                            <a href="/lead/@{{ lead.id }}/viewContactDetails" target="_blank" :class="{'required': !lead.mobile}">
                                @{{ lead.mobile }}  
                            </a>
                        </td>
                        <td>
                            <label>Address :</label>  
                            <a href="/lead/@{{ lead.id }}/viewContactDetails" target="_blank">
                                <span v-if="!lead.address" class="@{{ lead.country == 'IN' ? 'required' : 'warning'}}">
                                    @{{ lead.address }}  
                                </span>
                                <span v-else>
                                    @{{ lead.address }}  
                                </span>                                
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Country <div class='asterix'>*</div> :</label> 
                            <a href="/lead/@{{ lead.id }}/viewContactDetails" target="_blank" :class="{'required': !lead.country}">
                                @{{ lead.country }}  
                            </a>
                        </td>
                        <td>
                            <label>Region/State <div class='asterix'>*</div> :</label>  
                             
                            <a href="/lead/@{{ lead.id }}/viewContactDetails" target="_blank" :class="{'required': !lead.state}">
                                @{{ lead.state }}  
                            </a>                                  
                        </td>
                        <td>
                            <label>City <div class='asterix'>*</div> :</label>   
                            <a href="/lead/@{{ lead.id }}/viewContactDetails" target="_blank" :class="{'required': !lead.city}">
                                @{{ lead.city }}  
                            </a>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>PIN/ZIP :</label> 
                            <a href="/lead/@{{ lead.id }}/viewContactDetails" target="_blank">
                                <span v-if="!lead.zip || lead.zip == 0" class="@{{ lead.country == 'IN' ? 'required' : 'warning'}}">
                                    @{{ lead.zip }}  
                                </span>
                                <span v-else>
                                    @{{ lead.zip }}  
                                </span>                                
                            </a>
                        </td>
                        <td>
                            <label>Source <div class='asterix'>*</div> :</label> 

                            <a href="/lead/{{ $lead->id }}/viewDetails" target="_blank" :class="{'required': !lead.source_id}">
                        @if(Auth::user()->canViewLeadSource())
                                @{{ lead.lsource.source_name }}
                        @else
                                @{{ lead.lsource.channel.name }}
                        @endif 
                            </a>
                            
                        </td>
                        <td>
                            <label>Seller <div class='asterix'>*</div> :</label>
                    @if(Auth::user()->canCreateCartForOthers()) 

                            <select v-model="cre_id">                  
                        
                                <option v-for="user in users" :value="user.id" :selected="lead.cre_name == user.name">
                                    @{{ user.name }}
                                </option>
                            </select>
                    @else
                            <input type="hidden" v-model="cre_id" value="{{ Auth::id() }}">
                            {{ Auth::user()->employee->name }}
                            
                            
                    @endif
                            <span class="pull-right red">
                                <b>This lead belongs to @{{ lead.cre_name }}</b>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <label>Currency :</label>
                            <select class="form-control" v-model="currency_id" required>
                                <option value="">Select Currency</option>
                                <option v-for="currency in currencies" :value="currency.id">
                                    @{{ currency.name }} (@{{ currency.symbol }})
                                </option>
                            </select>
                        </td>
                        <td colspan="2">
                            <div class="col-sm-6">
                            <label>Shipping Address :</label>
                            <select class="form-control" v-model="shipping_address_id" id="shipping_address">
                                <option value="">Same as Billing Address</option>
                                <option v-for="address in lead.addresses" :value="address.id">@{{ address.address_type }}</option>                  
                            </select>
                            </div>
                            <div class="col-sm-6">
                            <b><i>Shipping Address</i></b>
                            <div v-show="address.id == shipping_address_id" v-for="address in lead.addresses">
                                <div>
                                    <label>@{{ address.address_type }}</label>
                                </div>
                                <div>
                                    <label>Name : </label>@{{ address.name }}
                                </div>
                                <div>
                                    <label>Address : </label>
                                    @{{ address.address }}
                                    @{{ address.city }}

                                    @{{ address.state }}
                                    @{{ address.zip }}
                                    @{{ address.country }}
                                </div>
                            </div>                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <label>Order Id :</label>
                            <input type="text" v-model="order_id">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="col-md-4">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" v-if="status" :disabled="!currency_id ||  !cre_id" @click="store">Add Cart</button>
            </div>
            <div class="col-md-8">
                <div class="alert alert-danger" v-show="message">
                    <h4>@{{ message }}</h4>
                    <div v-if="cart">
                        <label>Cart Id :</label>
                        <a href="/cart/@{{ cart.id }}" target="_blank"> @{{ cart.id }}</a>
                    </div>
                    
                </div>
            </div>
                
                
        </div>
    </div>
</div>
<style type="text/css">
    .available {
        display: inline;
    }
    .asterix {
        display: inline;
        color: #D43F3A;
    }
    .required {
        display: inline;
    }
    .required:before {
        content: "This field is required";
        color: #D43F3A;
        font-weight: 700;
    }
    .warning {
        display: inline;
    }
    .warning:before {
        content: "This field is important";
        color: #2E6DA4;
        font-weight: 700;
    }
</style>
@endsection

@section('main')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            
        @foreach($lead->carts as $cart)
            <div class="panel panel-warning">
                <div class="panel-heading"></div>
                <div class="panel-body">
                    <div class="container"> 
                        <div class="col-md-4">
                            <div>
                                <label>Cart Id :</label> <a href="/cart/{{$cart->id}}" target="_blank">{{ $cart->id}}</a>
                            </div>
                            <div>
                                <label>Created By :</label> {{ $cart->creator->employee->name or "" }}
                            </div>
                            <div>
                                <label>Created At :</label> {{ $cart->created_at->format('jS M Y h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label>Amount :</label> {{ $cart->currency->symbol }} {{ $cart->amount }}
                            </div>
                            <div>
                                <label>Payment :</label> {{ $cart->currency->symbol }} {{ $cart->payment }}
                            </div>
                            <div>
                                <label>Balance :</label> {{ $cart->currency->symbol }} {{ $cart->amount - $cart->payment }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Programs</label>
                            @foreach($cart->lead->programs as $program)
                                <li>{{ $program->name }}</li>
                            @endforeach
                        </div>                   
                        
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">            
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="col-md-2"><label>Name</label></div>
                                <div class="col-md-4"><label>Description</label></div>
                                <div class="col-md-1"><label>Duration</label></div>
                                <div class="col-md-1"><label>Quantity</label></div>
                                <div class="col-md-1"><label>Price</label></div>
                                <div class="col-md-1"><label>Discount</label></div>
                                <div class="col-md-1"><label>Coupon</label></div>
                                <div class="col-md-1"><label>Amount</label></div>
                            </div>
                
                @foreach($cart->products as $product)
                            <div class="cart-content">
                                <div class="col-md-2">{{$product->name or "" }}</div>
                                <div class="col-md-4">{{$product->description or "" }}</div>
                                <div class="col-md-1">{{$product->duration or "" }} {{$product->unit->name or "" }}</div>
                                <div class="col-md-1">{{$product->pivot->quantity or "" }}</div>
                                <div class="col-md-1">{{$product->pivot->price or "" }}</div>
                                <div class="col-md-1">{{$product->pivot->discount or "" }}% <em><small></small></em></div>
                                <div class="col-md-1">{{$product->pivot->coupon or "" }}</div>
                                <div class="col-md-1">{{$product->pivot->amount or "" }}</div>
                            </div>
                @endforeach

                        </div>
                    </div>
                    <div>
                        @include('cart.partials.workflow')
                    </div>
                </div>
            </div>

        @endforeach
        </div>
    </div>
    
</div><!-- Modal Template-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Modal title</h4>

            </div>
            <div class="modal-body"><div class="te"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<style type="text/css">
    .modal-dialog {
        /* new custom width */
        width: 90%;
    }
    .cart-content {
        font-size: .90em;
    }
</style>
<script type="text/javascript">
    $('body').on('hidden.bs.modal', '.modal', function () {
      $(this).removeData('bs.modal');
    });
</script>
<script>
var shipping_addresses ={}; 
<?php
    foreach($lead->addresses as $address){
        $shippingAddress = '<b>Name</b> : '.$address->name.', <br><b>Address</b> : '.$address->address.', '.$address->city.', '.$regions->where('region_code',$address->state)->first()->region_name.', '.$countries->where('country_code',$address->country)->first()->country_name.' - '.$address->zip;
        $cod_status = '<div class=\"col-sm-12\" style=\"border:solid 1px #e4c94b;background-color:#fff4c5;\">'.$address->cod.'</div>';
        echo 'shipping_addresses['.$address->id.'] = "'.$shippingAddress.$cod_status.'";'  ;        
    }
    
?>
$("#shipping_address").change(function() {
    address_value = this.value;
    if( address_value==''){
        $("#shipping_address_string").html('same as billing address');    
    }
    $("#shipping_address_string").html(shipping_addresses[address_value]);
});
</script>
<script>
new Vue({
    el: '#cart',
    mixins: [mixin],
    data: {
        loading: false,
        id: {{ $lead->id }},
        lead: '',
        currencies: [],
        users: [],
        status: false,
        message: '',
        cart: ''
    },

    ready: function(){
        this.findLead();
        this.getCurrencies();
    },

    methods: {

        findLead() {
            this.$http.get("/findLead", {
                id: this.id
            }).success(function(data){
                this.lead = data;
                this.getUsers();
                this.canCreateCart();
            }).bind(this);
        },

        getUsers() {
            this.$http.get("/api/getUsers").success(function(data){
                this.users = data;
            }).bind(this);
        },

        getCurrencies() {
            this.$http.get("/getCurrencies").success(function(data){
                this.currencies = data;
            }).bind(this);
        },

        canCreateCart() {
            this.$http.get("/canCreateCart", {
                id: this.lead.id
            })
            .success(function(data){
                if (data.status == 'true') {
                    this.status = true;
                } else {
                    this.status = false;
                }                
                this.message = data.message;
                this.cart = data.cart;
            }).bind(this);
        },

        store() {
            this.$http.post("/lead/" + this.lead.id + "/cart", {
                order_id:               this.order_id,
                currency_id:            this.currency_id,
                cre_id:                 this.cre_id,
                source_id:              this.lead.source_id,
                shipping_address_id:    this.shipping_address_id,
                created_by:             {{ Auth::id() }}
            })
            .success(function(cart){
                toastr.success("Cart created", "Success!");
                window.location.href = "/cart/" + cart.id;
            })
            .error(function(errors){
                this.toastErrors(errors);
            })
            .bind(this);
        }
    }
})
</script>
@endsection 