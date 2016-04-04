<div class="" id="products">
    <div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="panel-title">Add Product</div>
        </div>
        <div class="panel-body">
            <form action="/cart/{{$cart->id}}/product/add" method="POST" class="form-inline">
            @foreach($categories as $category)
             
            <?php    
                if($category->id == 1 && $cart->lead->programs->isEmpty()) {
                    continue;
                } else if ($category->id == 2 && $cart->lead->country <> 'IN'){
                    continue;
                } else if ($category->id == 3 && $cart->lead->state <> 'IN.07'){
                    continue;
                }else if ($category->id == 4 && $cart->lead->country <> 'IN'){
                    continue;
                }

            ?>
                <div>{{$category->name}}</div>
                <table id="products-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Coupon</th>
                            <th>Discount</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                
                @foreach($category->products as $product)

<?php
    if($cart->lead->country == 'IN') {

        $price = $product->domestic_price_inr;

    } elseif($cart->currency_id == 1) {

        $price = $product->international_price_inr;

    } elseif($cart->currency_id == 2) {

        $price = $product->international_price_usd;
    }


?>
                    @if($price)
                        <tr>
                            <td><input type="checkbox" name="product_ids[]" value="{{$product->id}}"></td>
                            <td>{{$product->name}}</td>
                            <td>{{$product->description}}</td>
                            <td><input id="quantity[{{$product->id}}]" name="quantity[{{$product->id}}]" class="form-control" value="1" size="2" onchange="changeQuantity({{$product->id}})"></td>
                            <td>
                                {{$cart->currency->symbol or ""}} {{$price}}
                                <input type="hidden" id="price[{{$product->id}}]" name="price[{{$product->id}}]" value="{{$price}}"></input>
                            </td>

                            <td>
                                <input id="coupon[]" name="coupon[{{$product->id}}]" class="form-control" value="" pid="{{$product->id}}" size="10" ><input type="hidden" value="{{$product->id}}">
                            </td>

                            <td><input id="discount[{{$product->id}}]" name="discount[{{$product->id}}]" class="form-control" value="0" size="2" readonly></td>

                            <td>
                                <div class="input-group">
                                    <label class="input-group-addon">{{$cart->currency->symbol or ""}}</label>
                                    <input id="amount[{{$product->id}}]" name="amount[{{$product->id}}]" class="form-control" value="{{$price}}" readonly></input>
                                </div>
                            </td>

                            <td>
                        @if(!$product->offers->isEmpty())

            <?php
                $msg = '';
                foreach ($product->offers as $offer) {
                    $msg .= "With minimum <b>".$offer->minimum_quantity."</b> quantity get <b>".$offer->product_offer_quantity." ".$offer->product->name."</b> (".$offer->product->duration.") FREE<p>";
                }
            ?>
                                <i class="fa fa-gift fa-2x" data-html="true" data-toggle="popover" title="Free Offer" data-content="{!!$msg!!}" data-placement="left" data-trigger="hover"></i> 
                                
                        @endif
                            </td>

                        </tr>
                    
                    @endif <!--Price > 0 -->

                @endforeach

                    </tbody>
                        
                </table>
               
                 
                    <div style="text-align: center;">
                        <div class="btn-actions">
                            <button class="btn btn-success select-items-confirm" type="submit">Submit</button>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>            
                
            @endforeach
                {{ csrf_field() }}
            </form>
        </div>
    </div>    
</div>
<style type="text/css">
    .popover {
        max-width:1024px; 
    }
    table tr.selected {
        background-color: #fff4c5;
        border: 1px solid #e4c94b;
    }
</style>
<script type="text/javascript">
    function changeQuantity(id) {
        calculateAmount(id);
    }

    function changeDiscount(id) {
        calculateAmount(id);
    }
    
    $("#coupon\\[\\]").bind("change", function(){
        var product_id = $(this).next('input').val();

        var data = {
            cart_id : {{$cart->id}}, 
            product_id : product_id,
            coupon : $(this).val(),
            _token : "{{ csrf_token() }}"
        };

        $.ajax({
            context: this,
            type: "post",
            data: data,
            url: "/api/coupon/validate"
        }).success(function(data){
            console.log(data);
            $(this).closest('td').next().find('input').val(data);
            calculateAmount(product_id);
        });        
    });

    function calculateAmount(id)
    {
        var quantity = $("#quantity\\["+id+"\\]").val();
        var price = $("#price\\["+id+"\\]").val();
        var discount = $("#discount\\["+id+"\\]").val(); 
        var amount = (price - (discount*price/100))*quantity;

        $("#amount\\["+id+"\\]").val(amount);
    }

    $(function () {
        $('[data-toggle="popover"]').popover()
    })
    //dismiss-a-twitter-bootstrap-popover-by-clicking-outside
    $('body').on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('table tr').click(function(event) {
        if (event.target.type !== 'checkbox') {
            if ($(':checkbox', this).is(":checked")) {
                return false;
            }
            $(':checkbox', this).trigger('click');
        }
    });

    $("input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) { //If the checkbox is checked
            $(this).closest('tr').addClass("selected"); 
            //Add class on checkbox checked
        } else {
            $(this).closest('tr').removeClass("selected");
            //Remove class on checkbox uncheck
        }
    });
});
</script>