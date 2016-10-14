@extends('master')

@section('content')
<div class="container" id="cart">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Name :</label>
                    @{{ cart.lead.name }}                    
                </div>
                <div class="form-group">
                    <label>Lead Id :</label>
                    <a href="/lead/@{{ cart.lead_id }}/cart" target="_blank">@{{ cart.lead_id }}</a>                       
                </div>
                <div class="form-group">
                    <label>Patient Id :</label>
                    @{{ cart.lead.patient.id }}                 
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Location :</label>
                    @{{ cart.lead.country }}, @{{ cart.lead.region.region_name }}, @{{ cart.lead-.city }}
                </div>
                <div class="form-group">
                    <label>DOB :</label>
                    @{{ cart.lead.dob | format_date1 }}
                </div>
                <div class="form-group">
                    <label>Gender :</label>
                    @{{ cart.lead.gender }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Amount :</label>
                    @{{ cart.currency.symbol }} @{{ cart.amount }}
                </div>
                <div class="form-group">
                    <label>Payment :</label>
                    @{{ cart.currency.symbol }} @{{ cart.payment }}
                </div>
                <div class="form-group">
                    <label>Balance :</label>
                    @{{ cart.currency.symbol }} @{{ cart.balance }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Product Category :</label>
                    @{{ cart.category.name }}
                </div>
                <div class="form-group">
                    <label>Programs : </label> <a href="/lead/@{{ cart.lead_id }}/program" target="_blank"><i class="fa fa-edit"></i></a>
                    <ul>
                        <li v-for="program in cart.lead.programs">@{{ program.name }}</li>  
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <b><i>Shipping Address</i></b><br>
            <div v-if="cart.address">
                <div>
                    <label>Name : </label> @{{ cart.address.name }}
                </div>
                <div>
                    <label>Address : </label> @{{ cart.address.address }}
                </div>
                <div>
                    @{{ cart.address.city }},
                    @{{ cart.address.region.region_name }},
                    @{{ cart.address.country }}
                    - @{{ cart.address.zip }}
                </div>
            </div>
            <div v-else>
                <div>
                    <label>Name : </label> @{{ cart.lead.name }}
                </div>
                <div>
                    <label>Address : </label> @{{ cart.lead.address }}
                </div>
                <div>
                    @{{ cart.lead.city }},
                    @{{ cart.lead.region.region_name }},
                    @{{ cart.lead.country }}
                    - @{{ cart.lead.zip }}
                </div>
            </div>
        </div>
    </div>
    @include('cart.partials.workflow')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Product Details</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-1">
                    <label>Category</label>
                </div>
                <div class="col-md-2">
                    <label>Product</label>
                </div>
                <div class="col-md-1">
                    <label>Duration</label>
                </div>
                <div class="col-md-1">
                    <label>Quantity</label>
                </div>
                <div class="col-md-1">
                    <label>Price</label>
                </div>
                <div class="col-md-2">
                    <label>Coupon</label>
                </div>
                <div class="col-md-1">
                    <label>Discount %</label>
                </div>
                <div class="col-md-1">
                    <label>Discount Amount</label>
                </div>
                <div class="col-md-1">
                    <label>Total</label>
                </div>
            </div>
            <div v-for="product in cart.products" class="row" style="border-bottom: 1px solid #999; margin: 15px 0px">
                <product-editable
                    :product="product"
                    :cart="cart"
                >
                </product-editable>
            </div>
            <div class="row">
                <div class="col-md-1 col-md-offset-9">
                    <label>Total</label>
                </div>
                <div class="col-md-2">
                    @{{ cart.amount | currency cart.currency.symbol }}
                </div>
            </div>
        </div>
    </div>
    <!-- Payment Details Begin -->
    <div class="panel panel-default" v-if="cart.payments.length>0">
        <div class="panel-heading">
            <h4>Payment Details</h4>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                <div class="col-md-2">
                    <label>Amount</label>
                </div>
                <div class="col-md-3">
                    <label>Payment Method</label>
                </div>
                <div class="col-md-4">
                    <label>Remark</label>
                </div>
                <div class="col-md-2">
                    <label>Payment Date</label>
                </div>
            </div>
            <div v-for="payment in cart.payments" class="col-md-12" style="border-bottom: 1px solid #999; margin: 10px 0px">
                <payment-editable
                    :payment="payment"
                    :cart="cart"
                >
                </payment-editable>
            </div>
        </div>
    </div>
    <!-- Payment Details End -->

    <div v-if="cart.status_id == 1 || cart.state_id == 2" style="text-align: center; margin: 40px">
        
        <!--<span v-if="cart.product_category_id == 1 && cart.programs.length > 0">
            <a data-toggle="modal" data-target="#sModal" href="/cart/{{$cart->id}}/program/add" class="btn btn-success">Add Program</a>

        </span>-->
        
        <span>
            <button class="btn btn-primary" @click="showProductModal=true">Add Product</button>
        </span>
        <span v-show="cart.products.length > 0 && (cart.amount == 0 || cart.balance > 0)">
            <button class="btn btn-primary" @click="showPaymentModal=true">Add Payment</button>
        </span>
        
        <span v-if="cart.payments.length > 0 && cart.state_id == 2"> 
            <a data-toggle="modal" data-target="#sModal" href="/cart/{{$cart->id}}/approval/update" class="btn btn-danger">Update Order</a>
        </span>

         <span v-if="cart.payments.length > 0 && cart.status_id ==1">
             <button type="submit" class="btn btn-danger" @click="processOrder">Process Order</button>
         </span>
    </div>

@if(Auth::user()->canActivateCart($cart))
    <button class="btn btn-primary" @click="activateCart" v-if="!loading">Activate Cart for Extension or Balance Payment</button>
@endif
    <div class="row">
        <div class="col-md-6">
            <!-- Cart Steps -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Steps</div>
                </div>
                <div class="panel-body">
                    <ul>
                        <li v-for="step in cart.steps">
                            <span class='@{{ step.state.css_class }}'>
                                <b>
                                    Cart @{{ step.status.name }}
                                    @{{ step.state.name }}
                                </b>
                            </span>
                            <small>by</small> 
                            <b>@{{ step.creator.employee.name }}</b>
                            <small v-if="step.remark">(@{{ step.remark }} )</small>
                            <small class="pull-right"><em> @{{ step.created_at | format_date }}</em></small>
                        </li>

                    </ul>
                </div>
            </div>        
        </div>
        <div class="col-md-6">
             <!-- Cart Comments -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="panel-title">Comments</span>
                    <span class="pull-right">
                        <a href="/cart/{{ $cart->id }}/comment" data-toggle="modal" data-target="#sModal">
                            <i class="fa fa-plus fa-2x"></i>
                        </a>
                    </span>
                </div>
                <div class="panel-body">
                    <ul>
                        <li v-for="comment in cart.comments">
                            <b>@{{ comment.text }}</b> 
                            <small>by</small> 
                            <b>@{{ comment.creator.employee.name }}</b>
                            <small class="pull-right"><em> [@{{ comment.created_at | format_date1 }}</em> ]</small>
                        </li>
                    </ul>
                </div>
            </div>   
        </div>
    </div>
@include('cart.components.product')
@include('cart.components.products')
@include('cart.components.payment')
</div>
@include('cart.components.product-editable')
@include('cart.components.payment-editable')

<script>
new Vue({
    el: '#cart',

    data: {
        loading: false,
        id: {{ $cart->id }},
        cart: {!! $cart !!},
        showPaymentModal: false,
        showProductModal: false,
        categories: [],
        products: [],
        payment: '',
        methods: [],
    },

    ready() {
        this.findCart();
        this.getPaymentMethods();
    },

    methods: {
        findCart() {
            $.isLoading({ text: "Loading cart" });
            this.$http.get("/findCart", {
                cart_id: this.id
            })
            .success(function(data){
                this.cart = data;
                $.isLoading( "hide" );
                this.getCategories();
            }).bind(this);
        },

        getCategories() {
            this.$http.get("/api/categories?country="+this.cart.lead.country+"&currency="+this.cart.currency.name)
            .then((response) => {
                this.categories = response.data;
            }, (response) => {
                toastr.error("Error occured", "Error");
            }).bind(this);
        },

        storeProducts() {
            this.$http.post('/cart/'+this.cart.id+'/products', {
                products: this.products
            })
            .then((response) => {
                toastr.success("Products saved", "Hurray!");
                this.cart = response.data;
                this.showProductModal = false;
                this.products = [];
            }, (response) => {
                toastr.error("Error Occured "+response.toString(), "Error");
            }).bind(this);
        },

        storePayment() {
            this.$http.post('/cart/'+this.cart.id+'/payment', this.payment)
            .then((response) => {
                toastr.success("Payment saved", "Hurray!");
                this.cart = response.data;
                this.showPaymentModal = false;
            }, (response) => {
                toastr.error("Error Occured "+response.toString(), "Error");
            }).bind(this);
        },

        processOrder() {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to make changes to the Cart!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, process cart!'
            }).then(function(isConfirm) {
                if (isConfirm) {
                    this.$http.post('/cart/'+ this.id +'/process')
                    .success(function(data) {
                        swal(
                          'Processed!',
                          'Cart Processed.',
                          'success'
                        );
                        this.findCart();
                    });                        
                }
            }.bind(this))
        },

        activateCart() {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to undo this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Activate Cart!'
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.isLoading({ text: "Activating Cart" });
                    this.$http.post("/cart/" + this.id + "/activate")
                    .then(function(data){
                        swal(
                          'Activated!',
                          'Cart Activated.',
                          'success'
                        );
                        $.isLoading( "hide" );
                        this.loading = true;
                        this.findCart();
                    });                        
                }
            }.bind(this))
        },

        getPaymentMethods() {
            this.$http.get("/getPaymentMethods")
            .success(function(data){
                this.methods = data;
            }).bind(this);
        },

        ifExists(haystack, needle) {
            for (item in haystack){
                if (haystack[item]['id'] === needle.id){
                    return true;
                }
            }
            return false;
        }
    },

    filters: {
        exists: function(pdt) {
            if (!pdt) { return false };
            return this.ifExists(this.products, pdt);
        }
    }
})
</script>
<style>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: auto;
    background-color: rgba(0, 0, 0, .5);
    display: table;
    transition: opacity .3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 95%;
    height: 100%;
    margin: 0px auto;
    padding: 20px 30px;
    background-color: #ecf0f5;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
    transition: all .3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.modal-body {
    margin: 20px 0;
}

.modal-default-button {
    float: right;
}

/*
 * the following styles are auto-applied to elements with
 * v-transition="modal" when their visiblity is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter, .modal-leave {
    opacity: 0;
}

.modal-enter .modal-container,
.modal-leave .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}
.modal-footer {
    border-top: 0;
}
.modal-body {
    padding: 0;
}
.form-control {
    font-size: 10px;
}
</style>
@endsection