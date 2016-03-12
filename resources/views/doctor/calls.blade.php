<div class="container">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('/doctor/partials/users')
				</div>
				<h4>Call Dispositions</h4>
			</div>	
			<div class="panel-body">

				<table id="leads" class="table table-bordered">
					<thead>
						<tr>
							<td>#</td>
							<td>Name</td>
							<td>Date</td>
							<td>Disposition</td>
							<td width="40%">Remarks</td>
							<td>Nutritionist</td>
						</tr>
					</thead>
					<tbody>

				@foreach($calls AS $call)
				<?php
					$i++;
				?>
						<tr>
							<td>
								{{$i}}
							</td>
							<td>
								<a href="/lead/{{$call->lead->id}}/viewDetails" target="_blank">{{ trim($call->lead->name) <> "" ? $call->lead->name : "No Name"}}</a>
							</td>
							<td>
								{{date('jS M, Y h:i A', strtotime($call->created_at))}}
							</td>
							<td>
								{{$call->master->disposition}}
							</td>
							<td>
								{{$call->remarks}}
							</td>
							<td>
								{{$call->lead->patient->nutritionist or ""}}
							</td>
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
});
</script>