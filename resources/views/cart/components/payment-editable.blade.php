<template id="payment-editable">
    <div v-if="edit">
        <div class="col-md-2">
            <input type="text" v-model="payment.amount" class="form-control">
        </div>
        <div class="col-md-3">
            <select v-model="payment.payment_method_id" class="form-control">
                <option v-for="method in methods" :value="method.id">
                    @{{ method.name }}
                </option>
            </select>
        </div>
        <div class="col-md-4">
            <textarea v-model="payment.remark" class="form-control"></textarea>
        </div>
        <div class="col-md-2">
            <input type="date" v-model="payment.date" class="form-control">
        </div>
        <div class="col-md-1">
            <button class="btn btn-primary" @click="update">Update</button>
        </div>
    </div>
    <div v-else @click="toggleEdit">
        <div class="col-md-2">
            @{{ payment.amount | currency cart.currency.symbol}}
        </div>
        <div class="col-md-3">
            @{{ payment.method.name }}
        </div>
        <div class="col-md-4">
            @{{ payment.remark}}
        </div>
        <div class="col-md-2">
            @{{ payment.user }}
            @{{ payment.date | format_date1 }}
        </div>
        <div class="col-md-1">
            <div v-if="cart.status_id == 1 || cart.state_id == 2">
                <i class="fa fa-close text-red" @click="delete"></i>
            </div>
        </div>
    </div>        
</template>
<script>
Vue.component('paymentEditable', {
    mixins: [ VueFocus.mixin ],
    template: '#payment-editable',
    props: ['payment', 'cart'],
    data: function() {
        return {
            edit: false,
            methods: []
        }
    },

    ready() {
        this.getPaymentMethods();
    },

    methods: {
        getPaymentMethods() {
            this.$http.get("/getPaymentMethods")
            .then((response) => {
                this.methods = response.data;
            }, (response) => {
                toastr.error("Error fetching Payment Methods", "Error");
            }).bind(this);
        },

        update() {
            this.$http.patch("/cart/payment/" + this.payment.id, this.payment)
            .success(function(data){
                this.$parent.cart = data;
                toastr.success('Cart Payment updated', 'Success!');
            })       
            .error(function(errors) {
                toastr.error("Error occured", "Error!");
            }).bind(this);

            this.edit = false;
        },

        toggleEdit() {
            if (this.cart.status_id==1 || this.cart.state_id==2) {
                this.edit = true;
            }
        },

        delete() {
            this.$http.delete("/cart/"+this.cart.id+"/payment/"+this.payment.id)
            .success(function(data){
                this.$parent.cart = data;
                toastr.warning("Payment deleted!", "Success!");
            }).bind(this);
        }
    }
})
</script>