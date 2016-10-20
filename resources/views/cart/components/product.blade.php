<template id="product">
    <div class="col-md-12" @click="toggleProduct">
        <div class="col-md-3">@{{ product.name }}</div>
        <div class="col-md-4">@{{ product.description }}</div>
        <div class="col-md-2">@{{ product.price | currency cart.currency.symbol }}</div>
        <div class="col-md-3">
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
                this.$parent.products.push(this.product);
            }
        },
    }
})
</script>