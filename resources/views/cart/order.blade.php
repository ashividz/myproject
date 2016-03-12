<div class="container">  
    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
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
                        <div>
                            <label>Category : </label>
                            <a href="/lead/{{ $order->cart->lead_id or '' }}" target="_blank">
                                {{ $order->category->name or "" }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div><label>Name : {{ $order->cart->lead->name or "" }}</div>
                        <div><label>Country : </label>{{ $order->cart->lead->m_country->country_name or "" }}</div>
                        <div><label>State : </label>{{ $order->cart->lead->region->region_name or "" }}</div>
                        <div><label>City : </label>{{ $order->cart->lead->city or "" }}</div>

                    </div>
                    <div class="col-md-2">
                        <div><label>CRE : </label>{{ $order->cart->cre->employee->name or "" }}</div>
                        <div><label>Lead Source : </label>{{ $order->cart->cre->employee->name or "" }}</div>
                    </div>
                    <div class="col-md-3">
                        <div><label>Created By : </label>{{ $order->creator->employee->name or "" }}</div>
                        <div><label>Created At : </label>{{ $order->created_at->format('jS M, Y h:i A') }}</div>
                        <div><label>Updated At : </label>{{ $order->updated_at->format('jS M, Y h:i A')  }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>