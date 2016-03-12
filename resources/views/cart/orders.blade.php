<div class="container">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <form id="form" method="post">
                <select id="category" name="category_id">
                    <option value="">All</option>
                
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{$category->id == $category_id ? 'selected' : ''}}>{{ $category->name }}</option>
                @endforeach
                
                </select>
                {{ csrf_field() }}
            </form>
    
        </div>
        <div class="panel-body">
            
        @foreach($orders as $order)
             <div class="panel panel-info">
                <div class="panel-heading"></div>
                <div class="panel-body">
                    <div class="col-md-2">
                        <div>
                            <label>Cart No : </label>
                            <a href="/cart/{{ $order->cart_id }}" target="_blank">
                                {{ $order->cart_id }}
                            </a>
                        </div>
                        <div>
                            <label>Order No : </label>
                            <a href="/order/{{ $order->cart_id }}" target="_blank">
                                {{ $order->id }}
                            </a>
                        </div>
                    @if($order->duration)
                        <div>
                            <label>Duration : </label>
                            {{ $order->duration }} {{ $order->duration > 1 ? $order->category->unit.'s' : '' }}
                        </div>
                    @endif
                    </div>
                    <div class="col-md-3">
            <? $products = $order->cart->products->where('product_category_id', $order->product_category_id) ?>
                @foreach($products as $product)
                        <div>
                            <label>Category : </label>
                            {{ $order->category->name or "" }}
                        </div>
                        <div>
                            <label>Product : </label>
                            {{ $product->name }}
                        </div>
                        <div>
                            <label>Quantity : </label>
                            {{ $product->pivot->quantity }}
                        </div>
                        <div>
                            <label>Duration : </label>
                            {{ $product->duration }} {{ $product->unit && $product->duration > 1 ? $order->category->unit.'s' : '' }}
                        </div>
                        <div>
                            <label>Amount : </label>
                            {{ $order->cart->currency->symbol or "" }} {{ $product->pivot->amount }} 
                        </div>
                        <hr>
                @endforeach
                    </div>
                    <div class="col-md-2">
                        <div>
                            <label>Name : </label>
                            <a href="/lead/{{ $order->cart->lead_id or '' }}/cart" target="_blank">
                                {{ $order->cart->lead->name or "" }}
                            </a>
                        </div>
                        <div>
                            <label>Country : </label>
                            {{ $order->cart->lead->m_country->country_name or "" }}
                        </div>
                        <div>
                            <label>State : </label>
                            {{ $order->cart->lead->region->region_name or "" }}
                        </div>
                        <div>
                            <label>City : </label>
                            {{ $order->cart->lead->city or "" }}
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div>
                            <label>CRE : </label>
                            {{ $order->cart->cre->employee->name or "" }}
                        </div>
                        <div>
                            <label>Lead Source : </label>
                            {{ $order->cart->cre->employee->name or "" }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div>
                            <label>Created By : </label>
                            {{ $order->creator->employee->name or "" }}
                        </div>
                        <div>
                            <label>Created At : </label>
                            {{ $order->created_at->format('jS M, Y h:i A') }}
                        </div>
                        <div>
                            <label>Updated At : </label>
                            {{ $order->updated_at->format('jS M, Y h:i A')  }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        </div>
    </div>
</div>
<script type="text/javascript">
    $("#category").on('change', function(){
        $("#form").submit();
    });
</script>