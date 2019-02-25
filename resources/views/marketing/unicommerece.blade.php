<div class="container1">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/daterange')
				</div>
				<h4>Unicommerece Cart</h4>
			</div>	
			<div class="panel-body">
			<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Csv</a>
				<form id="form" class="form-inline" action="/marketing/saveRejoin" method="post">
					<table id="table" class="table table-bordered">
						<thead>
							<tr>
								<td>#</td>
								<td>Name</td>
								<td>Adderss</td>
								<td>ContactNo</td>
								<td>Email</td>
								<td>Cart id</td>
								<td>Payment Mode</td>
								<td>Sku Code</td>
								<td>Discount</td>
								<td>Amount</td>
							</tr>
						</thead>
						<tbody>

				@foreach($carts AS $cart)

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
							<td>{{$cart->lead->email or " "}}</td>
							<td>{{$cart->id or " "}}</td>
							<td>{{$ModeOfPayment or " "}}</td>
							<td>{{$product->productsku->sku or ""}}</td>
							<td>{{$product->pivot->discount or ""}}</td>
							<td>{{$product->pivot->amount or ""}}</td>
							
					@endforeach

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
</script><script type="text/javascript">
$(document).ready(function() 
{
	
  	$( "#downloadCSV" ).bind( "click", function() 
  	{
    	var csv_value = $('#table').table2CSV({
                delivery: 'value'
            });
    	downloadFile('program_end.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  });

  	function downloadFile(fileName, urlData){
    	var aLink = document.createElement('a');
    	var evt = document.createEvent("HTMLEvents");
    	evt.initEvent("click");
    	aLink.download = fileName;
    	aLink.href = urlData ;
    	aLink.dispatchEvent(evt);
	}
});
</script>
<script type="text/javascript">
	$('.status').raty({ 
		readOnly: true,
		hints : ['New', 'Explanined', 'Follow Up', 'Hot', 'Converted'],
		score: function() {
            return $(this).attr('data-score');
        },
        number: function() {
            return $(this).attr('number');
        },
	});
</script>