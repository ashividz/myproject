<div class="col-sm-10 col-sm-offset-1">
<div class="panel panel-default">
<div class="panel-heading">Search</div>
<div class="panel-body">
<form class="form" method="POST"> 
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<fieldset>
<ol>                        
<li>
<label>Cart ID *</label>
<input name="cart_id" class="form-input">
</li> 
</ol>
</fieldset> 
<div class="row">
<div class="col-md-4">
<button type="submit" name="submit" class="btn btn-success" id="showbtn">Submit</button>
<input class="btn btn-danger" type="reset" value="Clear">
</div>
</div>
</form> 
</div>
</div>

<?php $i=0;?>
<div class="panel panel-default">
<div class="panel-heading">
<div class="pull-left"><b>Search For : </b> </div>
<div style="margin-left:80px"> <i>{!! $searchFor or "Nothing To Search For" !!}</i></div>
</div>
<div class="panel-body">

@if($cart)
<table class="table table-bordered">
<thead>
<tr>
<th>#</th>
<th>Cart</th>
<th>Name</th>
<th>Mobile</th>
<th>Alt Number</th>
<th>Email</th>
<th>Pincode</th>
<th>Address</th>
<th>City</th>
<th>Price</th>
<th>Payment Method</th>
</tr>
</thead>
<tbody>
@if($cart <> NULL)
<?php $i++;?>
<tr>
<td>{{$i or ""}}</td>
<td>{{$cart->id or " "}}</td>
<td>{{$cart->lead->name or " "}}</td>
<td>{{$cart->lead->phone or " "}}</td>
<td>{{$cart->lead->mobile or " "}}</td>
<td>{{$cart->lead->email or " "}}</td>
<td>{{$pin or " "}}</td>
<td>{{$shippingAddress or " "}}</td>
<td>{{$city or " "}}</td>
<td>{{$cart->amount or " "}}</td>
<td>{{$paymentmode or " "}}</td>
</tr> 
@endif

@if(!$cart)
<tr>
<td colspan="11">No results found</td>
</tr>
@endif
</tbody>
</table>
@endif
</div>
</div>
</div>