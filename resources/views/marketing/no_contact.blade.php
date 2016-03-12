<div class="container1">
	<div class="panel panel-default">
			<div class="panel-heading">
				<h4>No Contact Details</h4>
			</div>	
			<div class="panel-body">

				<form id="form" class="form-inline" action="/marketing/saveRejoin" method="post">
					<table id="leads" class="table table-bordered">
						<thead>
							<tr>
								<td>#</td>
								<td width="15%">Name</td>
								<td>Date</td>
								<td>CRE</td>
								<td>Source</td>
								<td>Status</td>
								<td width="30%">Last Disposition</td>
							</tr>
						</thead>
						<tbody>

				@foreach($leads AS $lead)

							<tr>
								<td>{{$i++}}</td>
								<td>
									<a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{trim($lead->name) <> '' ? $lead->name : "No Name"}}</a>
								</td>
								<td>
									{{date('jS M, Y', strtotime($lead->created_at))}}
								</td>
								<td>
									{{$lead->cre or ""}}
								</td>
								<td>
									{{$lead->source->source_name or ""}}
								</td>
								<td>
									{{$lead->status->master->status or ""}}
								</td>
								<td>
									<a href="/lead/{{$lead->id}}/viewDispositions" target="_blank">
										{{$lead->disposition->name or ""}}
										</a> : <b>{{$lead->disposition->master->disposition or ""}} </b> -
									<em>
										{{$lead->disposition->remarks or ""}}
									</em> 
									<div class="pull-right">
										<small>
											<em>
												{{$lead->disposition->created_at or ""}}
											</em>
										</small>
									</div>
								</td>
							</tr>

				@endforeach

						</tbody>
					</table>
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
</script>