<div class="modal-mask" v-show="showPaymentModal" transition="modal">
    <div class="modal-wrapper">
        <div class="modal-container">
            <div class="modal-header">
                <h4>Add Payment Details</h4>
                <button type="button" class="close" @click="showPaymentModal=false">
                    <i class="fa fa-close" @click="show=false"></i>
                </button>
            </div>    
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-body">
                            <div class="col-md-2">
                                <label>Payment Date :</label>
                                <input type="date" v-model="payment.date" class="form-control" value="{{ Carbon::today()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label>Amount :</label>
                                @{{ cart.currency.symbol }} <input type="text" v-model="payment.amount" :value="cart.balance" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Payment Mode :</label>
                                <select v-model="payment.payment_method_id" class="form-control">                                    
                                    <option value="">--Select Payment Method--</option>
                                    <option v-for="method in methods" :value="method.id">@{{ method.name }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Remark :</label>
                                <textarea v-model="payment.remark" class="form-control"></textarea>
                            </div>
                            <div class="" style="text-align: center;">
                                <button class="btn btn-danger" @click="showPaymentModal=false">Close</button>
                                <button class="btn btn-primary" @click="storePayment">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                                 
            </div>
        </div>
    </div>
</div>