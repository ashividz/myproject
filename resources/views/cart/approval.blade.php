@extends('master')

@section('content')
<div class="container">
    <form id="form" method="post" class="form">

@foreach($carts as $cart)
<?php
    $payments = '';
    $steps = '';
    foreach ($cart->payments as $payment) {
        $payments .= "<li>".$cart->currency->symbol.$payment->amount." <small>via</small> <span class='primary'><b>".$payment->method->name."</b></span> <small>on <em>".$payment->created_at->format('jS M, Y, h:i:A')."</em></small>";
        $payments .= $payment->remark <> '' ? " <small>(".$payment->remark.")</small>" : "";
        $payments .= "</li>";
    }

    foreach ($cart->steps as $step) {
        $steps .= "<li><b>Cart ".$step->status->name." <span class='".$step->state->css_class."'>".$step->state->name."</span></b> <small>by</small> ".$step->user->employee->name." <small>on <em>".$step->created_at->format('jS M, Y, h:i:A')."</em></small>";
        $steps .= $step->remark <> '' ? " <small>(".$step->remark.")</small>" : "";
        $steps .= "</li>";
    }

    /* Check Payment Approver */

    $disabled = '';
    $maxDiscount = null;
    $discount = null;
    if($cart->status_id == 2) {
        $maxDiscount = $cart->products->isEmpty() ? 0 : max(array_pluck($cart->products, 'pivot.discount'));
        if ($maxDiscount > 0) {
            $discount_id = $cart->step->discount_id + 1;
            //var_dump($cart->step->discount_id); 
            $discount = Discount::where('id', $discount_id)->first();

            if(!Helper::approveCartDiscount($maxDiscount)) {
                $disabled = 'disabled';
            }
        }
        
    } else if ($cart->status_id == 3) {
        $cart_payments = array_pluck($cart->payments, 'payment_method_id');

        if(!Helper::approveCartPaymentMethod($cart_payments)) {            
            $disabled = 'disabled';
        }
    }
?>
        <div class="panel panel-default">
            <div class="panel-heading">
            
            </div>
            <div class="panel-body">            
                <div class="container1">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cart Details</th>
                                <th>
                                    Lead Details
                                    <div style="display: inline;" data-html="true" data-toggle="popover" title="Lead Details" data-content="<li><b>DOB : </b>{{$cart->lead->dob->format('jS M, Y')}}</li><li><b>Gender : </b>{{$cart->lead->gender}}</li><li><b>Email : </b>{{$cart->lead->email}}</li><li><b>Phone : </b>{{$cart->lead->phone}}</li><li><b>Mobile : </b>{{$cart->lead->mobile}}</li><li><b>Address : </b>{{$cart->lead->address}}</li><li><b>Country : </b>{{$cart->lead->m_country->country_name or ''}}</li><li><b>State : </b>{{$cart->lead->region->region_name or ''}}</li><li><b>City : </b>{{$cart->lead->city}}</li><li><b>ZIP/PIN : </b>{{$cart->lead->zip}}</li>"><i class="fa fa-info-circle"></i></div>
                                </th>
                                <th>
                                    Payment Details
                                    <div style="display: inline;" data-html="true" data-toggle="popover" title="Payment Details" data-content="{!! $payments !!}"><i class="fa fa-info-circle"></i></div>
                                </th>
                                <th>Product Details</th>
                                <th>
                                    <div class="pull-right" data-html="true" data-toggle="popover" title="Cart Steps" data-content="{!! $steps !!}" data-placement="left"><i class="fa fa-info-circle"></i></div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            <tr>
                                <td>
                                    <div>
                                        <label>Cart Id :</label> <a href="/cart/{{$cart->id}}" target="_blank"> {{$cart->id}}</a>
                                    </div>
                                        <label>Created By :</label> {{$cart->user}}
                                    </div>
                                    <div>
                                        <label>Created At :</label> {{$cart->created_at->format('jS M Y, h:i A')}}
                                    </div>                         
                                    
                                </td>
                                <td>  
                                    <div>
                                        <label>Name :</label>
                                        {{$cart->lead->name}}
                                    </div>   
                                    <div>
                                        <label>Lead Id :</label> <a href="/lead/{{$cart->lead->id}}/viewDetails" target="_blank">{{$cart->lead->id}}</a>
                                    </div> 
                                @if($cart->lead->patient)  
                                    <div>
                                        <label>Patient Id :</label> <a href="/patient/{{ $cart->lead->patient->id }}/viewDetails" target="_blank">{{ $cart->lead->patient->id }}</a>
                                    </div>
                                @endif 
                                    <div>
                                        <label>Lead Source :</label> {{$cart->source->source_name}}
                                    </div>

                                    <div>
                                        <label>CRE :</label> {{ $cart->cre->employee->name or '' }}
                                    </div>

                                    <div>
                                        <label>TL :</label> {!! $cart->cre->employee->sup->pivot or '' !!}
                                    </div>                             
                                    
                                </td>
                                <td>
                                    <div><label>Amount :</label> {{$cart->currency->symbol}} {{$cart->amount}}</div>
                                    <div>
                                        <label>Payment :</label> {{$cart->currency->symbol}} {{$cart->payment}}
                                    </div>
                                    <div><label>Balance :</label> {{$cart->currency->symbol}} {{$cart->amount- $cart->payment}}</div>
                                </td>

                                <td>
                                    <table class="table table-condensed">
                                @foreach($cart->products as $product)
                                        <tr>
                                            <td>{{$product->name}}</td>
                                            <td>{{$product->pivot->quantity or ""}}</td>
                                            <td>
                                        @if($product->pivot->product_offer_id)
                                                FREE OFFER
                                        @else
                                            {{$cart->currency->symbol}} {{$product->pivot->price}}
                                        @endif
                                            </td>
                                            <td>{{$product->pivot->coupon}} {!!$product->pivot->discount ? " <small>(".$product->pivot->discount."%)</small>" : '' !!}</td>
                                        </tr>
                                @endforeach
                                    </table>
                                </td>
                                <td align="center">
                            @if($cart->status_id <> 4)
                                    <div class="form-group">
                                        <input type="radio" id="state[]" name="state[{{$cart->id}}]" value="{{ $maxDiscount > 0 ? 4 : 3 }}" checked> Approve
                                        <input type="radio" id="state[]" name="state[{{$cart->id}}]" value="2"> Reject                               
                                    </div> 
                                    <div class="form-group">    
                                       
                                        <textarea name="remark[{{$cart->id}}]" class="form-control">@if($discount)Discount approved upto {{ $discount->value }} %@endif
                                        </textarea>
                                    </div>
                                    <div class="form-group"> 
                                        <input type="hidden" name="cart[{{$cart->id}}]" value="{{$cart->id}}">
                                        <input type="hidden" name="discount_id" value=""></input>
                                        <button type="submit" class="btn btn-primary" {{ $disabled }}>Save</button>
                                    </div>  
                            @elseif($cart->status_id == 4 && $cart->state_id <> 3)
                                    <a href="#" onclick="order({{$cart->id}})" class="btn btn-primary">{{ $cart->lead->patient ? '' : 'Register Patient & '}}Place Order</a>
                            @endif                              
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="padding: 10px;">
                    @include('cart.partials.workflow')
                </div>
                
        </div>
    </div>
    @endforeach
        {{ csrf_field() }}

    </form>
</div>
<script type="text/javascript">
    function approve(id)
    {
        var r=confirm("Are you sure you want to Approve?");
        if (r==true){
            var input = $("<input>").attr("type", "hidden").attr("name", "id").val(id);
            $('#form').append($(input));
            $("#form").submit();
        }
    }

    function order(id)
    {
        var r=confirm("Are you sure you want to place Order?");
        if (r==true){
            var input = $("<input>").attr("type", "hidden").attr("name", "id").val(id);
            $('#form').append($(input));
            $("#form").submit();
        }
    }
</script>
<script type="text/javascript">   

    $('[data-toggle="popover"]').popover(); 
    
    $('body').on('click', function (e) {
        //did not click a popover toggle, or icon in popover toggle, or popover
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('[data-toggle="popover"]').length === 0
            && $(e.target).parents('.popover.in').length === 0) { 
            $('[data-toggle="popover"]').popover('hide');
        }
    })
</script>
<style type="text/css">
    .popover {
        max-width: 1200px;
        color: #111;
    }
    table {
        font-size: 12px;
        width: 100%;
    }
    table>thead {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
    }
    table>thead>tr>th {
        font-size: 14px;
        text-transform: uppercase;
        padding: 5px;
    }
    table>tbody>tr>td {
        padding: 5px;
    }
</style>
@endsection