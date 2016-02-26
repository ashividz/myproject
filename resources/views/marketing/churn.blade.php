<div class="container">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/daterange_users')
				</div>
				<h4>Churn Leads</h4>
			</div>	
			<div class="panel-body">

				<form id="form" class="form-inline" action="/marketing/leads/churn/save" method="post">
					<table id="leads" class="table table-bordered">
						<thead>
							<tr>
								<td><input type="checkbox" id="checkAll"></td>
								<td>Name</td>
								<td>CRE Assigned Date</td>
								<td>Source</td>
								<td>Status</td>
								<td width="30%">Last Call</td>
								<td>Last Call (Days)</td>
							</tr>
						</thead>
						<tbody>

					@foreach($leads AS $lead)

							<tr>
								<td>
									<input class='checkbox' type='checkbox' name='check[{{$lead->id}}]' value="{{$lead->id}}">
								</td>
								<td>
									<a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{ trim($lead->name) <> "" ? $lead->name : "No Name"}}</a>
								</td>
								<td>
									{{date('jS M Y', strtotime($lead->cre->created_at))}}
								</td>
								<td>
									{{$lead->source->master->source_name or ""}}
								</td>
								<td>
									{{$lead->status->name or ""}}
								</td>
								<td>
									@if(isset($lead->disposition))
										<b>{{$lead->disposition->master->disposition}} : </b>
										{{$lead->disposition->remarks}}

										<small class="pull-right">
											<em>[
											{{date('jS M Y, h:i A', strtotime($lead->disposition->created_at))}}
											]</em>
										</small>										

										<small>
											{!! $lead->disposition->callback ? "<br><b>Callback : </b>" . date('jS M Y, h:i A', strtotime($lead->disposition->callback)) : "" !!}
										</small>
									@endif

								</td>
								<td style="text-align:center">
									@if(isset($lead->disposition))
										{{floor((strtotime(date('Y/m/d')) - strtotime($lead->disposition->created_at))/(60*60*24)) + 1}}
									@endif
								</td>
							</tr>

					@endforeach

						</tbody>
					</table>
					<div class="form-control">
						
			        	<select name="cre" id="cre" required>
			        		<option value="">Select User</option>

			        	@foreach($users AS $user)
			                <option value="{{$user->name}}">{{$user->name}}</option>
			        	@endforeach	

			        	</select>
						<button class="btn btn-primary">Churn Leads</button>
					</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</form>
			</div>			
	</div>
	
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#leads').dataTable({
		"iDisplayLength": 100,
		"aaSorting": [[ 6, "desc" ]]
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