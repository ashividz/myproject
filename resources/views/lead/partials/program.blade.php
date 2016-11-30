@extends('lead.index')
@section('top')
<div id="programs">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title">Program</span></div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="col-md-6" v-for="program in programs">
                    <input type="checkbox" v-model="selected" :value="program.id">
                    @{{ program.name }}
                </div>
                @{{$array}}
            </div>
            <hr>
            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary" v-if="selected.length > 0" @click="store">Save</button> 
            </div> 
        </div>
    </div>
</div>
<script>
    new Vue({
        el: '#programs',

        data: {
            id: {{ $lead->id }},
            programs: [],
            selected: [],
        },

        ready: function(){
            this.getPrograms();
            this.getLeadPrograms();
        },

        methods: {

            getPrograms() {
                this.$http.get("/api/getPrograms")
                .success(function(data){
                    this.programs = data;
                }).bind(this);
            },

            getLeadPrograms() {
                $.isLoading({ text: "Loading" });
                this.$http.get("/getLeadPrograms", {
                    'id': this.id, 
                })
                .success(function(data){
                    this.selected = data;
                    $.isLoading( "hide" );
                }).bind(this);
            },

            store() {
                $.isLoading({ text: "Storing" });
                this.$http.post("/lead/"+ this.id +"/program", {
                    'programs': this.selected,
                })
                .success(function(data){
                    $.isLoading( "hide" );
                    toastr.success("Leads Churned", "Success");
                }).bind(this);
            }
        },
    })
</script>
@endsection
@section('main')

<div id="main">
    <div class="panel panel-default" v-for="cart in carts">
        <div class="panel-body">
            <div class="col-md-12">
                <div class="col-md-3">
                    <label>Cart Id : </label>
                    <a href="/lead/{{ $lead->id }}/cart" target="_blank">@{{ cart.id }}</a>
                </div>
                <div class="col-md-3">
                    <label>Date : </label> 
                    @{{ cart.created_at | format_date }}
                </div>
                <div class="col-md-3">
                    <label>Cart Amount : </label> 
                    @{{ cart.amount | currency cart.currency.symbol }}
                </div>
            </div>
            <hr>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in cart.products">
                            <td>
                                @{{ product.name }}
                            </td>
                            <td>
                                @{{ product.pivot.quantity }}
                            </td>
                            <td>
                                @{{ product.duration }}
                            </td>
                            <td>
                                @{{ product.pivot.price }}
                            </td>
                            <td>
                                <span v-if="product.pivot.discount > 0">
                                    @{{ product.pivot.discount }} %
                                </span>
                            </td>
                            <td>
                                @{{ product.pivot.amount | currency cart.currency.symbol }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in cart.payments">
                            <td>
                                @{{ payment.date | format_date1 }}
                            </td>
                            <td>
                                @{{ payment.method.name }}
                            </td>
                            <td>
                                @{{ payment.amount | currency cart.currency.symbol }}
                            </td>
                            <td>
                                @{{ payment.remark }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#main',

        data: {
            id: {{ $lead->id }},
            carts: []
        },

        ready: function(){
            this.getLeadCarts();
        },

        methods: {
            getLeadCarts() {
                $.isLoading({ text: "Loading" });
                this.$http.get("/getLeadCarts", {
                    'id': this.id, 
                })
                .success(function(data){
                    this.carts = data;
                    $.isLoading( "hide" );
                }).bind(this);
            },

            store() {
                $.isLoading({ text: "Storing" });
                this.$http.post("/lead/"+ this.id +"/program", {
                    'programs': this.selected,
                })
                .success(function(data){
                    $.isLoading( "hide" );
                    toastr.success("Leads Churned", "Success");
                }).bind(this);
            }
        },
    })
</script>
<script type="text/javascript">
$("input[type=checkbox]").on('change', function(e){
    if ($("input[type=checkbox]:checked").length === 0) {
        e.preventDefault();
        $("#submit").prop('disabled', true);
        return false;
    } else {
        $("#submit").prop('disabled', false);
    }
});
$('#form-program').on('submit', function (e) {
  if ($("input[type=checkbox]:checked").length === 0) {
      e.preventDefault();
      alert('Program required');
      return false;
  }
});
</script>
@endsection