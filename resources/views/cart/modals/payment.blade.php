<div id="product-edit">
    <div class="panel panel-default">
        <div class="panel-heading">
            Add Payment Details
        </div>
        <div class="panel-body">
            <form class="form-inline" action="/cart/{{$cart->id}}/payment/add" method="POST">

                <div class="form-group">
                    <label for="date">Payment Date :</label>
                    <input id="date" name="date" class="form-control" size="10" value="{{date('d/m/Y')}}" readonly></input>
                </div>
                <div class="form-group">
                    <label for="amount">Amount :</label>
                    <div class="input-group">
                        <label class="input-group-addon">{{ $cart->currency->symbol or ""}}</label>
                        <input id="amount" name="amount" class="form-control" size="7" value="{{$cart->amount - $cart->payment}}"></input>
                    </div>
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Mode :</label>
                    <select id="payment_method" name="payment_method" class="form-control" required>
                        <option value="">Select Mode</option>
                    @foreach($methods as $method)
                        <option value="{{$method->id}}">{{$method->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="remark">Remark :</label>
                    <textarea id="remark" name="remark" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button> 
                {{ csrf_field() }}  
                </div>                              
            </form>
        </div>
    </div>
</div>
<style type="text/css">
    .form-group {
        padding: 10px;
    }
</style>
<script>
  $(function() {
    $( "#date" ).datepicker({
            dateFormat: 'dd-mm-yy',
            maxDate: 0
        });
  });
  </script>