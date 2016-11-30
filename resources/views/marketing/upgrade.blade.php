<?php
	$i = 0;
?>
<div class="container">
	<div class="panel">
		<div class="panel-heading">
			<div class="pull-right">
				<form method="post" class="form-inline" role="form">
					Days : 
					<div class="form-group">
						<input type="text" size="3" name="days" value="{{$days}}">
					</div>
					<div class="form-group">
						<button class="btn btn-primary">Submit</button>
					</div>
	        		<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</form>
			</div>
			<h4>Upgrade Leads</h4>
		</div>
		<div class="panel-body">
			<form id="form" method="post" action="/marketing/saveUpgradeLeads" class="form-inline">
			<table id="table" class="table table-bordered">
				<thead>
					<tr>
						<th style="width:5%;"><input type="checkbox" id="checkAll"></th>
						<th>Name</th>
						<th>Source</th>
						<th>CRE</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Days Left</th>
						<th>Nutritionist</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
			
			@foreach ($patients as $patient)

					<tr>
						<td><input type="checkbox" name='check[{{$patient->lead_id}}]' value='{{$patient->lead_id}}' class="checkLead"></td>
						<td>
							<a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank">
								{{$patient->lead->name}}
							</a>
						</td>
						<td>
							@if(isset($patient->lead->source))
								{{$patient->lead->source->source_name or ""}}
							@endif
						</td>
						<td>
							@if( isset($patient->lead->cre))
								{{$patient->lead->cre->cre}}
								<em class="pull-right">
									[{{date('jS M, Y', strtotime($patient->lead->cre->created_at))}}]
								</em>
							@endif
						</td>

						<td>
							{{$patient->fee->start_date or ""}}
						</td>
						<td>{{$patient->fee->end_date or ""}}</td>
						<td>
							{{(strtotime($patient->fee->end_date) - strtotime(date('Y/m/d')))/(60*60*24)}}
						</td>
						<td>
							{{$patient->nutritionist}}
						</td>
						<td>{{$patient->fee->total_amount or ""}}</td>
					</tr>

			@endforeach

				</tbody>
				
			</table>
			<div class="form-control">
				<select name="cre" id="cre">
	        		<option>Select CRE</option>

	        	@foreach($users AS $user)
	        		<option value="{{$user->name}}">{{$user->name}}</option>
	        	@endforeach	

        		</select>
			</div>
			<button class="btn btn-primary">Save</button>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="source" value="22">
		</form>
		</div>
	</div>		
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#table').dataTable({
		"bPaginate": false,
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
});
</script>
<script>
$("#checkAll").change(function () {
	$(".checkLead").prop('checked', $(this).prop("checked"));	
});
</script>
