@extends('master')

@section('content')
<div class="container" id="app">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Name :</label>
                    {{$cart->lead->name or ""}}                    
                </div>
                <div class="form-group">
                    <label>Lead Id :</label>
                    <a href="/lead/{{$cart->lead->id or ""}}/cart" target="_blank">{{$cart->lead_id or ""}}</a>                       
                </div>
                <div class="form-group">
                    <label>Patient Id :</label>
                    {{$cart->lead->patient->id or ""}}                 
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Location :</label>
                    {{ $cart->lead->country or ""}}, {{ $cart->lead->region->region_name or ""}}, {{ $cart->lead->city or ""}}
                </div>
                <div class="form-group">
                    <label>DOB :</label>
                    {{ $cart->lead->dob <> '' ? $cart->lead->dob->format('jS M, Y') : '' }}
                </div>
                <div class="form-group">
                    <label>Gender :</label>
                    {{ $cart->lead->gender or "" }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Amount :</label>
                    {{$cart->currency->symbol or ""}} {{$cart->amount or ""}}
                </div>
                <div class="form-group">
                    <label>Payment :</label>
                    {{$cart->currency->symbol or ""}} {{$cart->payment}}
                </div>
                <div class="form-group">
                    <label>Balance :</label>
                    {{$cart->currency->symbol or ""}} {{ $cart->amount- $cart->payment}}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Product Category :</label>
                    {{ $cart->category->name or "" }}
                </div>
                <div class="form-group">
                    <label>Programs : </label> <a href="/lead/{{ $cart->lead_id }}/program" target="_blank"><i class="fa fa-edit"></i></a>
                    <ul>
                    @foreach($cart->lead->programs as $program)
                        <li>{{ $program->name }}</li>    
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('cart.partials.workflow')
    <div class="panel panel-default">
        <div class="panel-heading">
            Product Details
        </div>
        <div class="panel-body">
            <form id="form-product" action="/cart/{{$cart->id}}/product/delete" method="post" class="form-inline">
                <table class="table table-bordered">
                    <tr>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Description</th>
                        <th>Duration</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Coupon</th>
                        <th>Discount (%)</th>
                        <th>Total</th>
                        <th></th>
                        <th></th>
                    </tr>
                @foreach($cart->products as $product)
                    <tr class="{{$product->pivot->product_offer_id?'offer':''}}">
                        <td>{{ $product->category->name or "" }}</td>
                        <td>{{ $product->name }}
                        </td>
                        <td>{{ $product->description or "" }}</td>
                        <td>{{ $product->duration }} {{ $product->category->unit->name or "" }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>
                    @if($product->pivot->product_offer_id)
                            <label>FREE</label>
                    @else
                            <label>{{$cart->currency->symbol or ""}}</label> {{$product->pivot->price}}
                    @endif
                        </td>
                        <td>
                            {{$product->pivot->coupon or ""}}
                        </td>
                        <td>
                    @if(!$product->pivot->product_offer_id)
                            {{$product->pivot->discount}}
                    @endif
                        </td>
                        <td>
                    @if(!$product->pivot->product_offer_id)
                            <label>{{$cart->currency->symbol or ""}}</label> {{$product->pivot->amount}}
                    @endif
                        </td>
                        <td>
                    @if(!$product->pivot->product_offer_id && ($cart->status_id == 1 || $cart->state_id == 2))
                            <a data-toggle="modal" data-target="#myModal" href="/cart/product/{{$product->pivot->id}}/edit" class="primary">
                                <i class="fa fa-edit"></i>{{$product->pivot->product_offer_id}}
                            </a>
                    @endif
                        </td>
                        <td>
                    @if($cart->status_id == 1 || $cart->state_id == 2)
                            <a href="#" onclick="deleteProduct({{$product->pivot->id}})" class="danger"><i class="fa fa-close"></i></a>
                    @endif
                        </td>
                    </tr>
                @endforeach
                    <tr>
                        <th colspan="8" style="text-align: right;">Grand Total : </th>
                        <th colspan="3"><label>{{$cart->currency->symbol or ""}}</label> {{$cart->amount}}</th>
                    </tr>
                </table>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <!-- Payment Details Begin -->
    @if(!$cart->payments->isEmpty())
    <div class="panel panel-default">
        <div class="panel-heading">
            Payment Details
        </div>
        <div class="panel-body">
            <form id="form-payment" action="/cart/{{$cart->id}}/payment/delete" method="post" class="form-inline">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Remark</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                @foreach($cart->payments as $payment)
                        <tr>
                            <td>{{$cart->currency->symbol}} {{$payment->amount}}</td>
                            <td>{{$payment->method->name or ""}}</td>
                            <td>{{$payment->remark}}</td>
                            <td>
                                {{$payment->user or ""}}
                                {{$payment->created_at->format('jS M, Y h:i A')}}
                            </td>
                            <td>
                        @if($cart->status_id == 1 || $cart->state_id == 2)
                                <a href="#" onclick="deletePayment({{$payment->id}})" class="red"><i class="fa fa-close"></i></a>
                        @endif
                            </td>
                        </tr>
                @endforeach

                    </tbody>
                </table>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    @endif
    <!-- Payment Details End -->

@if($cart->status_id == 1 || $cart->state_id == 2)
    
    @if($cart->product_category_id == 1 && $cart->programs->isEmpty())
        <a data-toggle="modal" data-target="#sModal" href="/cart/{{$cart->id}}/program/add" class="btn btn-success">Add Program</a>
    @else
        <a data-toggle="modal" data-target="#myModal" href="/cart/{{$cart->id}}/product/add" class="btn btn-success">Add Product</a>

    @endif
    
        
    @if(!$cart->products->isEmpty() && ($cart->amount - $cart->payment) <> 0)
        <a data-toggle="modal" data-target="#myModal" href="/cart/{{$cart->id}}/payment/add" class="btn btn-primary">Add Payment</a>
    @endif
    
    @if(!$cart->payments->isEmpty() && $cart->state_id == 2) 
        <a data-toggle="modal" data-target="#sModal" href="/cart/{{$cart->id}}/approval/update" class="btn btn-danger">Update Order</a>
    @endif

        
    @if(!$cart->payments->isEmpty() && $cart->status_id == 1) 
        <form method="post" action="/cart/{{$cart->id}}/process" class="form-inline" style="display: inline;">       
            {{ csrf_field() }}
            <button type="submit" class="btn btn-danger">Process Order</button> 
        </form>
    @endif
@endif
</div>
<!-- Modal Template-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Modal title</h4>

            </div>
            <div class="modal-body"><div class="te"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Modal Template-->
<div class="modal fade" id="sModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Modal title</h4>

            </div>
            <div class="modal-body"><div class="te"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<style type="text/css">
    #myModal .modal-dialog {
        /* new custom width */
        width: 95%;
    }
    table tr td, table tr td input {
        font-size: 90% !important;
    }
    .offer {
        background-color: #fff4c5;
        border: 1px solid #e4c94b;
    }
    .form-group {
        margin-bottom: 0px;
    }
</style>

<script type="text/javascript">
    $('body').on('hidden.bs.modal', '.modal', function () {
      $(this).removeData('bs.modal');
    });

    function deleteProduct(id) {

        var r=confirm("Are you sure you want to delete?");
        if (r==true){

            var input = $("<input>").attr("type", "hidden").attr("name", "id").val(id);
            $('#form-product').append($(input));
            $('#form-product').submit();
        };
    };


    function deletePayment(id) {

        var r=confirm("Are you sure you want to delete?");
        if (r==true){

            var input = $("<input>").attr("type", "hidden").attr("name", "id").val(id);
            $('#form-payment').append($(input));
            $('#form-payment').submit();
        };
    };
</script>
@endsection