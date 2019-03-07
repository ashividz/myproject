<div class="container1">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/daterange')
				</div>
				<h4>Unicommerece Cart</h4>
			</div>	
			<div class="panel-body">
				<form id="form" class="form-inline" action="/marketing/saveRejoin" method="post">
					<table id="table" class="table table-bordered">
						<thead>
							<tr>
								<td>#</td>
								<td>Name</td>
								<td>Adderss</td>
								<td>Contact No</td>
								<td>City</td>
								<td>State</td>
								<td>Pincode</td>
								<td>Email</td>
								<td>Cart id</td>
								<td>Payment Mode</td>
								<td>Sku Code</td>
								<td>Order Id</td>
								<td>Discount</td>
								<td>Amount</td>
							</tr>
						</thead>
						<tbody>

				@foreach($carts AS $cart)

					@if($cart->status_id == 4)

					    @foreach($cart->products As $product)

								<?php 
	                              $ModeOfPayment = "";
							        foreach ($cart->payments as $payment) {
							            $ModeOfPayment = $ModeOfPayment.'/'.$payment->method->name;
							        }
								?>

								<tr>
								<td>{{$i++}}</td>
								
								<td><a href="/lead/{{$cart->lead->id}}/viewDetails" target="_blank">{{$cart->lead->name or ""}}</a></td>
								@if($cart->address)
									<td>{{$cart->address->address .','.$cart->address->city . ',' . $cart->address->region->region_name . ',' . $cart->address->zip . ',' . $cart->address->country}}</td>
								@else
									<td>{{$shippingAddress = $cart->lead->address .','.$cart->lead->city . ',' . $cart->lead->zip . ',' . $cart->lead->state . ',' . $cart->lead->country}}</td>
								@endif

								<td>{{$cart->lead->phone or " "}}</td>
								<td>{{$cart->address->city or $cart->lead->city}}</td>
								<td>{{$cart->address->region->region_name or $cart->lead->state}}</td>
								<td>{{$cart->address->zip or $cart->lead->zip}}</td>
								<td>{{$cart->lead->email or " "}}</td>
								<td>{{$cart->id or " "}}</td>
								<td>{{$ModeOfPayment or " "}}</td>
								<td>{{$product->productsku->sku or ""}}</td>
								<td>{{$cart->order_id or " "}}</td>
								<td>{{$product->pivot->discount or ""}}</td>
								<td>{{$product->pivot->amount or ""}}</td>
								
						@endforeach
					@endif

				@endforeach

						</tbody>
					</table>
					<div class="form-control">
						
			        	<select name="cre" id="cre" required>
			        		<option>Select User</option>

			        	</select>
						<button class="btn btn-primary">Assign Rejoin Leads</button>
					</div>
					<input type="hidden" name="source" value="23">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</form>
			</div>			
	</div>
	
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#leads').dataTable({
		"iDisplayLength": 100
	});

	$("#form").submit(function(event) {


	    event.preventDefault();
	    /* stop form from submitting normally */

        var url = $("#form").attr('action'); // the script where you handle the form input.
        $.ajax(
        {
           type: "POST",
           url: url,
           data: $("#form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               //alert(data);
               $('#alert').show();
               $('#alert').empty().append(data);
               setTimeout(function()
               	{
			     	$('#alert').slideUp('slow').fadeOut(function() 
			     	{
			         	location.reload();
			         });
				}, 10000);
           }
        });
        return false; // avoid to execute the actual submit of the form.
	});	

	$("#checkAll").change(function () {
	    $("input:checkbox").prop('checked', $(this).prop("checked"));
	});
});
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/buttons/1.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.flash.min.js"></script>
<script type="text/javascript" src = "//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.print.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    } );
} );
</script>
