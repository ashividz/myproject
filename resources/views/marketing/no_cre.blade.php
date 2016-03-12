<div class="container1">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/daterange')
				</div>
				<h4>No CRE Assigned Report</h4>
			</div>	
			<div class="panel-body">

				<form id="form" class="form-inline" action="/marketing/saveRejoin" method="post">
					<table id="leads" class="table table-bordered">
						<thead>
							<tr>
								<td>#</td>
								<td>Query Id</td>
								<td width="15%">Name</td>
								<td>Patient Id</td>
								<td>Date</td>
								<td>Source</td>
								<td>Status</td>
								<td width="30%">Last Disposition</td>
							</tr>
						</thead>
						<tbody>

				@foreach($leads AS $lead)

							<tr>
								<td>{{$i++}}</td>
								<td>{{$lead->query_id}}</td>
								<td>
									<a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{trim($lead->name) <> '' ? $lead->name : "No Name"}}</a>
								</td>
								<td>
									{{$lead->patient->id or ""}}
								</td>
								<td>
									{{date('jS M, Y', strtotime($lead->created_at))}}
								</td>
								<td>
									{{$lead->source->master->source_name or ""}}
								</td>
								<td>
									{{$lead->status->master->status or ""}}
								</td>
								<td>
									@if(isset($lead->disposition))
										<a href="/lead/{{$lead->id}}/viewDispositions" target="_blank">
											{{$lead->disposition->name or ""}}
											</a> : <b>{{$lead->disposition->master->disposition or ""}} </b> -
										<em>
											{{$lead->disposition->remarks or ""}}
										</em> 
										<div class="pull-right">
											<small>
												<em>
													{{date('jS M, Y h:i A', strtotime($lead->disposition->created_at))}}
												</em>
											</small>
										</div>
									@endif
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