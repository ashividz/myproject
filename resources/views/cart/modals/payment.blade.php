<div id="payment-edit">
    <div class="panel panel-default">
        <div class="panel-heading">
            Add Payment Details
        </div>
        <div class="panel-body form-inline">
            <div class="form-group">
                <label for="date">Payment Date :</label>
                <input id="date" v-model="date" class="form-control" size="10" value="{{date('d-m-Y')}}" readonly></input>
            </div>
            <div class="form-group">
                <label for="amount">Amount :</label>
                <div class="input-group">
                    <label class="input-group-addon">{{ $cart->currency->symbol or ""}}</label>
                    <input id="amount" v-model="amount" class="form-control" size="7" value="{{$cart->amount - $cart->payment}}"></input>
                </div>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Mode :</label>
                <select id="payment_method" v-model="payment_method_id" class="form-control" required>
                    <option value="">Select Mode</option>
                    <option v-for="method in methods" v-bind:value="method.id">@{{method.name }}</option>

                </select>
            </div>
            <div class="form-group" v-show="payment_method_id == 2 || payment_method_id == 4">
                <label for="remark">Delivery Time :</label>
                <input type="text" id="timepicker" v-model="delivery_time" class="form-control input-small">
            </div>
            <div class="form-group">
                <label for="remark">Remark :</label>
                <textarea id="remark" v-model="remark" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <button type="button" id="close" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" @click="store">Save</button> 
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .form-group {
        padding: 10px;
    }
</style>
<script src="/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<link href="/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
<script>
    new Vue({
        el: 'body',
        mixins: [mixin],
        data: {
            cart_id: '{{ $cart->id }}',
            methods: [],
        },

        ready: function(){
            this.getPaymentMethods();
        },

        methods: {

            getPaymentMethods() {
                this.$http.get("/getPaymentMethods")
                .success(function(data){
                    this.methods = data;
                }).bind(this);
            },

            store() {
                this.$http.post("/cart/"+ this.cart_id +"/payment", {
                    cart_id: this.cart_id, 
                    amount : this.amount, 
                    payment_method_id : this.payment_method_id,
                    date: this.date,
                    delivery_time: this.delivery_time,
                    remark: this.remark,
                    created_by: {{ Auth::id() }},
                })
                .success(function(data){
                    //this.leads = data;
                    toastr.success("Payment saved", "Success!");
                    location.reload(true);
                    //$("#close").click()
                })
                .error(function(data){
                    this.toastErrors(data);
                })
                .bind(this);
            }
        }
    })
</script>
<script>
$(function() {
    $( "#date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: 0
    });

    $('#timepicker').timepicker({
        template: false,
        showInputs: false,
        minuteStep: 30,
        showMeridian: true,
        maxHours: 20,
        defaultTime: false,
    });
});
</script>