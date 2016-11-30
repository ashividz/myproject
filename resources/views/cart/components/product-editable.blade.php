<template id="product-editable">
    <div v-if="edit" class="row">
        <div class="col-md-1">
            @{{ product.category.name }}
        </div>
        <div class="col-md-2">
            @{{ product.name }}
        </div>
        <div class="col-md-1">
            @{{ product.duration }}
        </div>
        <div class="col-md-1">
            <input type="text" v-model="product.pivot.quantity" class="form-control" @keyup.enter="update">
        </div>
        <div class="col-md-1">
            @{{ product.pivot.price | currency cart.currency.symbol }}
        </div>
        <div class="col-md-3">
            <input type="text" v-model="product.pivot.coupon" class="form-control" @keyup.enter="applyCoupon">
        </div>
        <div class="col-md-1">
            @{{ product.pivot.amount | currency cart.currency.symbol }}
        </div>
    </div>
    <div v-else @click="toggleEdit" class="row">
        <div class="col-md-1">
            @{{ product.category.name }}
        </div>
        <div class="col-md-2">
            @{{ product.name }}
        </div>
        <div class="col-md-1">
            @{{ product.duration }}
        </div>
        <div class="col-md-1">
            @{{ product.pivot.quantity }}
        </div>
        <div class="col-md-1">
            @{{ product.pivot.price | currency cart.currency.symbol }}
        </div>
        <div class="col-md-2">
            @{{ product.pivot.coupon }}
        </div>
        <div class="col-md-1">
            @{{ product.pivot.discount }} %
        </div>
        <div class="col-md-1">
            @{{ discount_amount | currency cart.currency.symbol }}
        </div>
        <div class="col-md-1">
            @{{ product.pivot.amount | currency cart.currency.symbol }}
        </div>
        <div class="col-md-1">
            <div v-if="cart.status_id == 1 || cart.state_id == 2">
                <i class="fa fa-close text-red" @click="delete"></i>
            </div>
        </div>
    </div>        
</template>
<script>
Vue.component('productEditable', {
    template: '#product-editable',
    props: ['product', 'cart'],
    data: function() {
        return {
            edit: false,
            products: [],
            amount: '',
            discount_amount: ''
        }
    },

    ready() {
        //console.log("read");
        //this.getProducts();
        this.discount_amount = this.product.pivot.quantity*this.product.pivot.price - this.product.pivot.amount;
        this.$watch('product.pivot.quantity', function() {
            this.total();  
        });
    },

    methods: {
        applyCoupon() { 
            if (!this.product.pivot.coupon) {
                this.discount_amount = 0;
                this.product.pivot.discount = 0;
                this.total(); 
                this.update();
                return true;
            }
            this.$http.post("/api/coupon/validate", {
                cart_id: this.cart.id,
                product_id: this.product.id,
                coupon: this.product.pivot.coupon,
            })
            .then((response) => {
                if (response.data.benefit=='discount amount') {
                    this.discount_amount = response.data.discount_amount;
                    this.product.pivot.discount = this.discount_amount * 100 / (this.product.pivot.price * this.product.pivot.quantity);
                    this.product.pivot.discount = this.product.pivot.discount.toFixed(2);
                } else {
                    this.discount_amount = response.data.percentage * this.product.pivot.price * this.product.pivot.quantity / 100;
                    this.product.pivot.discount = response.data.percentage;
                }

                if (this.product.pivot.coupon) {
                    toastr.success("Coupon "+this.product.pivot.coupon+" applied. <p><b>Discount % :</b> "+this.product.pivot.discount+"<p><b>Discount Amount : </b>"+this.cart.currency.symbol + this.discount_amount, "Success");
                }
                
                this.toggleEdit();
                this.total(); 
                this.update();
            }, (response) => {
                toastr.error("Error fetching products", "Error");
            }).bind(this);
        },

        getProducts() {
            this.$http.get("/api/products")
            .then((response) => {
                this.products = response.data;
            }, (response) => {
                toastr.error("Error fetching products", "Error");
            }).bind(this);
        },

        update() {
            this.$http.patch("/cart/"+this.cart.id+"/product/"+this.product.id, {
                quantity: this.product.pivot.quantity,
                coupon: this.product.pivot.coupon,
                discount: this.product.pivot.discount,
                amount: this.product.pivot.amount
            })
            .success(function(data){
                this.cart = data;
                toastr.success('Product updated', 'Success!');
                this.toggleEdit()
            })       
            .error(function(errors) {
                toastr.error("Error occured", "Error!");
            }).bind(this);

            this.toggleEdit();
        },

        toggleEdit() {
            if (this.cart.status_id==1 || this.cart.state_id==2) {
                this.edit = !this.edit;
            }            
        },

        delete() {
            this.$http.delete("/cart/"+this.cart.id+"/product/"+this.product.pivot.id)
            .success(function(data){
                //this.cart.amount = data.amount;
                //this.cart.balance = data.balance;
                //this.$parent.cart.products = [];
                this.$parent.cart = data;
                toastr.warning("Product deleted!", "Success!");
            }).bind(this);
        },

        total() {
            //this.discount_amount = this.product.pivot.discount * this.product.pivot.price * this.product.pivot.quantity/100;
            this.product.pivot.amount = this.product.pivot.quantity * this.product.pivot.price - this.discount_amount;
        }
    }, 
    computed : {
        amount() {
            discount_amount = this.product.pivot.discount * this.product.pivot.price * this.product.pivot.quantity/100;
            return this.product.pivot.price * this.product.pivot.quantity - discount_amount;
        }
    }
})
</script>