    @extends('lead.index')

@section('top')

<div class="" id="order">
    <div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="panel-title">Add Cart</div>
        </div>
        <div class="panel-body">
            <validator name="validation">
                <form id="form-order" class="form-inline" method="post">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                
                                <td width="25%">
                                    <label>DOB <div class='asterix'>*</div> :</label>   
                                    {!!$lead->dob == '' ? '<a href="/lead/'.$lead->id.'/viewPersonalDetails" target="_blank" class="required"></a>' : $lead->dob->format('jS M, Y') !!}
                                    </div>
                                </td>
                                <td>
                                    <label>Gender <div class='asterix'>*</div> : </label>   
                                    {!!$lead->gender == '' ? '<a href="/lead/'.$lead->id.'/viewPersonalDetails" target="_blank" class="required"></a>' : $lead->gender!!}
                                </td>
                                <td>
                                    <label>Email <div class='asterix'>*</div> : </label>  
                                    {!!$lead->email == '' ? '<a href="/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="required"></a>' : $lead->email!!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Phone <div class='asterix'>*</div> :</label>
                                    <a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank" class="{{ $lead->phone == '' ? 'required' : 'available' }}"> {{ $lead->phone }} </a>
                                </td>
                                <td>
                                    <label>Mobile : </label> 
                                    <a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank" class="{{ $lead->mobile == '' ? 'warning' : 'available' }}"> {{ $lead->mobile }} </a> 
                                </td>
                                <td>
                                    <label>Address :</label>  
                                @if($lead->country == "IN") 
                                    <a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank" class="{{ $lead->address == '' ? "required" : "" }}"> 

                                @else
                                     <a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank" class="{{ $lead->address == '' ? "warning" : "" }}"> 
                                @endif
                                        {{ $lead->address }} 
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Country <div class='asterix'>*</div> :</label>   
                                    <a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank" class="{{ $lead->country ? '' : 'required' }}">
                                        {!! $lead->m_country->country_name or "" !!}
                                    </a>
                                </td>
                                <td>
                                    <label>Region/State <div class='asterix'>*</div> :</label>  
                                    <a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank" class="{{ $lead->state ? '' : 'required' }}">
                                        {!! $lead->region->region_name or $lead->state !!}
                                    </a>                                    
                                </td>
                                <td>
                                    <label>City <div class='asterix'>*</div> :</label>   
                                    {!! $lead->city == '' ? '<a href="/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="required"></a>' : $lead->city !!}
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <label>PIN/ZIP :</label> 
                                @if($lead->country == "IN") 
                                    <a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank" class="{{ $lead->zip == '' ? "required" : "" }}"> 

                                @else
                                     <a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank" class="{{ $lead->zip == '' ? "warning" : "" }}"> 
                                @endif 
                                        {{$lead->zip}} 
                                    </a>
                                </td>
                                <td>
                                    <label>Source <div class='asterix'>*</div> :</label> 

                                    <a href="/lead/{{ $lead->id }}/viewDetails" target="_blank" class="{{ $lead->sources->isEmpty() ?'required' : '' }}">
                                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
                                        {!!  $lead->source->master->source_name or ""!!}
                                @else
                                        {{ $lead->source->master->channel->name or "" }}
                                @endif 
                                    </a>
                                    
                                </td>
                                <td>
                                    <label>CRE <div class='asterix'>*</div> :</label>
                            @if(Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('service') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing')) 

                                    <select name="cre">                  
                                @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $lead->cre_name == $user->name ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                @endforeach
                                    </select>
                            @else
                                    <input type="hidden" name="cre" value="{{ $lead->cres->first()->user_id }}">
                                    {!! $lead->cres->isEmpty() ? '<a href="/lead/'.$lead->id.'/viewDetails" target="_blank" class="required"></a>' : $lead->cres->first()->cre !!}
                            @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1">
                                    <label>Currency :</label>
                                    <select class="form-control" name="currency" required>
                                        <option value="">Select Currency</option>
                                    @foreach($currencies as $currency)
                                        @if($currency->id == 1 && $lead->country == 'IN')
                                            <option value="{{$currency->id}}" selected>
                                            {{ $currency->name }} ({{ $currency->symbol }})
                                        </option>
                                        @else
                                        <option value="{{$currency->id}}">
                                            {{ $currency->name }} ({{ $currency->symbol }})
                                        </option>
                                        @endif
                                    @endforeach
                                    </select>
                                </td>
                                <td colspan="2">
                                    <div class="col-sm-6">
                                    <label>Shipping Address :</label>
                                    <select class="form-control" name="shipping_address_id" id="shipping_address">
                                        <option value="">same as billing address</option>
                                        @foreach($lead->addresses as $address)
                                            <option value="{{$address->id}}">{{$address->address_type}}</option>
                                        @endforeach                                    
                                    </select>
                                    </div>
                                    <div class="col-sm-6">
                                    <b><i>Shipping Address</i></b><br/>
                                    <span id="shipping_address_string">Same as billing address</span>
                                    </div>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            @if($lead->dob <> '' && $lead->gender <> '' && $lead->email <> '' && $lead->phone <> '' && $lead->country <> '' && $lead->state <> '' && $lead->city <> '' && $lead->source_id <> '')
                    <button type="submit" class="btn btn-primary">Add Cart</button>
            @endif
                    {{ csrf_field() }}
                </form>
            </validator>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function() {
    $( "#datepicker" ).datepicker({  
        maxDate: 0,
        dateFormat: 'dd-mm-yy' 
    });
});
</script>
<style type="text/css">
    .available {
        display: inline;
    }
    .asterix {
        display: inline;
        color: #D43F3A;
    }
    .required {
        display: inline;
    }
    .required:before {
        content: "This field is required";
        color: #D43F3A;
        font-weight: 700;
    }
    .warning {
        display: inline;
    }
    .warning:before {
        content: "This field is important";
        color: #2E6DA4;
        font-weight: 700;
    }
</style>
@endsection

@section('main')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            
        @foreach($lead->carts as $cart)
            <div class="panel panel-warning">
                <div class="panel-heading"></div>
                <div class="panel-body">
                    <div class="container"> 
                        <div class="col-md-4">
                            <div>
                                <label>Cart Id :</label> <a href="/cart/{{$cart->id}}" target="_blank">{{ $cart->id}}</a>
                            </div>
                            <div>
                                <label>Created By :</label> {{ $cart->creator->employee->name or "" }}
                            </div>
                            <div>
                                <label>Created At :</label> {{ $cart->created_at->format('jS M Y h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label>Amount :</label> {{ $cart->currency->symbol }} {{ $cart->amount }}
                            </div>
                            <div>
                                <label>Payment :</label> {{ $cart->currency->symbol }} {{ $cart->payment }}
                            </div>
                            <div>
                                <label>Balance :</label> {{ $cart->currency->symbol }} {{ $cart->amount - $cart->payment }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Programs</label>
                            @foreach($cart->lead->programs as $program)
                                <li>{{ $program->name }}</li>
                            @endforeach
                        </div>                   
                        
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">            
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="col-md-2"><label>Name</label></div>
                                <div class="col-md-4"><label>Description</label></div>
                                <div class="col-md-1"><label>Duration</label></div>
                                <div class="col-md-1"><label>Quantity</label></div>
                                <div class="col-md-1"><label>Price</label></div>
                                <div class="col-md-1"><label>Discount</label></div>
                                <div class="col-md-1"><label>Coupon</label></div>
                                <div class="col-md-1"><label>Amount</label></div>
                            </div>
                
                @foreach($cart->products as $product)
                            <div class="cart-content">
                                <div class="col-md-2">{{$product->name or "" }}</div>
                                <div class="col-md-4">{{$product->description or "" }}</div>
                                <div class="col-md-1">{{$product->duration or "" }} {{$product->unit->name or "" }}</div>
                                <div class="col-md-1">{{$product->pivot->quantity or "" }}</div>
                                <div class="col-md-1">{{$product->pivot->price or "" }}</div>
                                <div class="col-md-1">{{$product->pivot->discount or "" }}% <em><small></small></em></div>
                                <div class="col-md-1">{{$product->pivot->coupon or "" }}</div>
                                <div class="col-md-1">{{$product->pivot->amount or "" }}</div>
                            </div>
                @endforeach

                        </div>
                    </div>
                    <div>
                        @include('cart.partials.workflow')
                    </div>
                </div>
            </div>

        @endforeach
        </div>
    </div>
    
</div><!-- Modal Template-->
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
<style type="text/css">
    .modal-dialog {
        /* new custom width */
        width: 90%;
    }
    .cart-content {
        font-size: .90em;
    }
</style>
<script type="text/javascript">
    $('body').on('hidden.bs.modal', '.modal', function () {
      $(this).removeData('bs.modal');
    });
</script>
<script>
var shipping_addresses ={}; 
<?php
    foreach($lead->addresses as $address){
        $shippingAddress = '<b>Name</b> : '.$address->name.', <br><b>Address</b> : '.$address->address.', '.$address->city.', '.$regions->where('region_code',$address->state)->first()->region_name.', '.$countries->where('country_code',$address->country)->first()->country_name.' - '.$address->zip;
        $cod_status = '<div class=\"col-sm-12\" style=\"border:solid 1px #e4c94b;background-color:#fff4c5;\">'.$address->cod.'</div>';
        echo 'shipping_addresses['.$address->id.'] = "'.$shippingAddress.$cod_status.'";'  ;        
    }
    
?>
$("#shipping_address").change(function() {
    address_value = this.value;
    if( address_value==''){
        $("#shipping_address_string").html('same as billing address');    
    }
    $("#shipping_address_string").html(shipping_addresses[address_value]);
});
</script>
@endsection 