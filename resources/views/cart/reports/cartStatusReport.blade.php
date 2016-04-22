<div class="container1">
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
            <h4>Cart Status Report</h4>
            </div>
            <div class="pull-right">
                @include('partials/daterange')
            </div>
        </div>
        <div class="panel-body">
            <form id="form" method="post" class="form-inline" action="/service/diets/send">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="width:12%;">Cart Details</th>
                            <th style="width:12%;">Lead Details</th>
                            <th style="width:12%;">Products</th>
                            <th style="width:12%;">Payments</th>                           
                            <th style="width:50%;">status</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($carts as $cart)
                        <tr>
                            <td>
                                <a href="/cart/{{$cart->id}}" target="_blank"> {{$cart->id}}</a>
                            </td>
                            <td>                                
                                <div>
                                    <label>Creator : </label> {{ $cart->creator->employee->name or "" }} 
                                </div><div>
                                    <label>Date : </label> {{ $cart->created_at->format('j-M-y, h:i A') }}</span>
                                </div>
                                <div>
                                    <label>CRE : </label> {{ $cart->cre->employee->name or "" }} 
                                </div>
                                <div>
                                    <label>TL : </label> {!! $cart->cre && !$cart->cre->employee->sup->isEmpty() ? $cart->cre->employee->sup->last()->name : "XNX" !!}
                                </div>
                            </td>
                            <td>
                                <div>
                                    <label>Name : </label>
                                    {{$cart->lead->name}}
                                </div>
                                <div>
                                    <label>Lead Id : </label>
                                    <a href="/lead/{{ $cart->lead_id or ""}}/viewDetails" target="_blank">{{ $cart->lead_id or ""}}</a>
                                </div>
                                <div>
                                    <label>Patient Id : </label>
                                    <a href="/patient/{{ $cart->lead->patient->id or ""}}/viewDetails" target="_blank">{{ $cart->lead->patient->id or ""}}</a>
                                </div>
                            </td>
                            <td>
                                @foreach($cart->products as $product)
                                    <li>
                                        {{ $product->name }}
                                        {!! $product->pivot->discount > 0 ? "<small><em>(" . $product->pivot->discount."%)</em><small>" : "" !!}
                                    </li>
                                @endforeach
                            </td>
                            <td>
                                @foreach($cart->payments as $payment)
                                    <li>
                                        {{ $payment->amount }} - {{ $payment->method->name or "" }}
                                        <small>
                                            <em>{!! $payment->remark <> '' ? '<br>( '.$payment->remark.' )' : '' !!}</em>
                                        </small>
                                    </li>
                                @endforeach
                            </td>
                            <td><div>@include('cart.partials.workflow')</div></td>                             
                            

                        </tr>
                        
                @endforeach     
                    </tbody>                    
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/workflow.js"></script>