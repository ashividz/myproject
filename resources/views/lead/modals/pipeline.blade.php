<div class="col-md-6 col-md-offset-3" id="pipeline">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title">{{ $lead->name }} : Create Hot Pipeline</span> 
        </div>
        <div class="panel-body">
            <div class="col-md-8 col-md-offset-2">
                <form action="/lead/{{ $lead->id }}/pipeline" method="post" class="form">
                    <div class="form-group">
                        <label class="col-md-4">Date </label>
                        <input type="text" id="datepicker" name="date" class="" placeholder="Date" required></input>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Currency </label>
                        <select name="currency" v-model="currency" required>
                            <option v-for="currency in currencies" v-bind:value="currency">@{{ currency.symbol }}</option>
                        </select>
                       
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Price @{{ currency.symbol }}</label>
                        <input type="text" name="price" v-model="price" placeholder="Price" required></input>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Discount </label>
                        <input type="text" name="discount" v-model="discount" maxlength="3"  class="" placeholder="Discount %"></input>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Amount @{{ currency.symbol }}</label>
                        <input type="text" name="amount" v-model="amount" placeholder="Amount" readonly="true"></input>
                    </div>

                    <div class="form-group">
                        <textarea name="remark" class="form-control" placeholder="Remark" required></textarea>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="hidden" name="currency_id" v-model="currency.id"></input>
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}"></input>
                        <input type="hidden" name="created_by" value="{{ Auth::id() }}"></input>

                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
new Vue({
    el: '#pipeline',
    data: {
        price: '',
        discount:'',
        amount: '',
        currencies: [],
        currency: [],
    },

    ready: function(){
        this.getCurrencies()
    },

    methods: {
        getCurrencies() {
            $.getJSON("/api/getCurrencies", function(currencies){
                this.currencies = currencies;
                console.log(currencies);
            }.bind(this));
        }
    },

    computed: {
        amount() {
            return this.price - this.price*this.discount/100;
        },
    }
})
</script>
<script>
$(function() {
    $( "#datepicker" ).datepicker({
        minDate: 0 
    });
});
</script>