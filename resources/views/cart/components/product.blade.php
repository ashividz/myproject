<template id="product">
    <div class="col-md-12">
        <div class="col-md-2" @click="toggleProduct">@{{ product.name }}</div>
        <div class="col-md-3" @click="toggleProduct">@{{ product.description }}</div>
        <div class="col-md-1">
            <input type="text" v-model="product.quantity" value="1" size="2">
        </div>
        <div class="col-md-1" @click="toggleProduct">@{{ product.price | currency cart.currency.symbol }}</div>
        <div class="col-md-1" @click="toggleProduct">@{{ amount | currency cart.currency.symbol }}</div>
        <div class="col-md-3" @click="toggleProduct">
            <li v-for="offer in product.offers">
                @{{ offer.product_offer_quantity }} <label>@{{ offer.product.name }}</label> <em class="text-muted">worth</em> @{{ offer.product.price | currency cart.currency.symbol }}
            </li>
        </div>
    </div>
</template>
<script>
Vue.component('product', {
    template: '#product',
    props: ['product', 'cart'],
    data: function() {
        return {
            amount: ''
        }
    },

    ready() {
    },

    methods: {
        toggleProduct() {
            if (this.$parent.ifExists(this.$parent.products, this.product)) {
                this.$parent.products.$remove(this.product);
            } else {
                this.product.amount = this.product.quantity*this.product.price;
                this.$parent.products.push(this.product);
            }
        },
    }, 
    computed : {
        amount() {
            return this.product.price * this.product.quantity;
        }
    },
})
</script>