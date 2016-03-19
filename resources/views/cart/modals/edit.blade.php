
<div id="product-edit">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Edit Product</div>
        </div>
        <div class="panel-body">
            <form class="form-inline" action="/cart/product/{{$product->id or ''}}/edit" method="POST">
                <table class="table table-striped">
                    <tr>
                        <th>Product</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Coupon</th>
                        <th>Discount (%)</th>
                        <th>Amount</th>
                    </tr>
                    <tr>
                        <td>{{$product->product->name or ""}}</td>
                        <td>{{$product->product->description or ""}}</td>
                        <td><input class="form-control" id="quantity" name="quantity" value="{{$product->quantity or ''}}" size="2"></td>
                        <td>{{ $product->cart->currency->symbol or "" }} {{$product->price or ""}}</td>                        

                        <td>
                            <input id="coupon" name="coupon" class="form-control" value="{{$product->coupon}}"size="10" >
                        </td>

                        <td><input id="discount" name="discount" class="form-control" value="{{$product->discount or ''}}" maxlength="2" readonly></td>
                        <td>
                            <div class="input-group">
                                <label class="input-group-addon">{{$product->cart->currency->symbol or ""}}</label>
                                <input id="amount" name="amount" class="form-control" readonly></input>
                            </div>
                        </td>
                    </tr>
                </table>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button> 
                    {{ csrf_field() }}                
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() 
{ 
    calculateAmount();
    
    $("#quantity").change(function(){
        calculateAmount();
    })

    $("#coupon").bind("change", function(){
        var data = {
            cart_id : {{$product->cart_id}},
            product_id : {{$product->id}},
            coupon : $(this).val(),
            _token : "{{ csrf_token() }}"
        };

        $.ajax({
            context: this,
            type: "post",
            data: data,
            url: "/api/coupon/validate"
        }).success(function(data){
            /*$("#alert").show();
            $("#alert").append(data);*/
            console.log(data);
            $(this).closest('td').next().find('input').val(data);
            calculateAmount();
        });        
    });

    function calculateAmount()
    {
        var quantity = $("#quantity").val();
        var price = {{$product->price or ""}};
        var discount = $("#discount").val(); 
        var amount = (price - (discount*price/100))*quantity;

        $("#amount").val(amount);
    }        
});
</script>