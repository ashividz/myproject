<div class="container1">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/daterange')
				</div>
				<h4>Lead Distribution</h4>
			</div>	
			<div class="panel-body">

				<form id="form" class="form-inline" action="/marketing/saveRejoin" method="post">
					<table id="leads" class="table table-bordered">
						<thead>
							<tr>
								<th>Date</th>
				
							@foreach($sources AS $source)

								<td title="{{$source->source}}">{{substr($source->source, 0, 4)}}</td>

							@endforeach

								<th>Total</th>

							</tr>
						</thead>
						<tbody>
<?php
	$channels = json_decode($channels);
?>

					@foreach($channels AS $channel)
							<tr>
								<td>
									{{date('M j, Y', $channel->date)}}
								</td>

							@foreach($channel->counts AS $count)							
								<td align="center">
									{{$count}}
								</td>
							@endforeach

								<th>
									{{$channel->total}}
								</th>
							</tr>
					@endforeach

						</tbody>
					</table>
			</div>			
	</div>
	
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#leads1').dataTable({
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
</script>