<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
      		<div class="pull-right">
      			@include('partials/daterange_users')
			</div>
			<h4>Dead Leads</h4>
		</div>	
		<div class="panel-body">
		<form id="form2" class="form-inline" method="POST" action="">
					<b>FILTER</b>	

                    <div class="checkbox">
                        <label>
                        <input type="checkbox" id="ncr" checked="true" onchange="filter(this.id)"> Delhi NCR
                        </label>
                    </div>  	
				  	<div class="checkbox">
				    	<label>
				      	<input type="checkbox" id="pan" checked="true" onchange="filter(this.id)"> PAN India
				    	</label>
				  	</div>		
				  	<div class="checkbox">
				    	<label>
				      	<input type="checkbox" id="int" checked="true" onchange="filter(this.id)"> International
				    	</label>
				  	</div>
				</form>


			<form id="form" class="form-inline" action="/marketing/leads/dead/churn" method="post">
				<table id="leads" class="table table-striped">
					<thead>
						<tr>
							<th><input type="checkbox" id="checkAll"></th>
							<th>Name</th>
							<th>Source</th>
							<th>Lead Entry Date</th>
							<th>Lead Assign Date</th>
							<th width='40%'>CRM Disposition</th>
						</tr>
					</thead>
					<tbody>
				@foreach ($leads as $lead)
				   <?php
								$filter = "";
									if ($lead->state == 'IN.07') {
										$filter = "ncr";
										$checkboxclass = "ncrcheck";
									}else if (($lead->country == 'IN' || trim($lead->country) == '' || !$lead->country) && $lead->state <> 'IN.07') {
                                        $filter = "pan";
                                        $checkboxclass = "pancheck";
                                    }
									else  if ($lead->country != 'IN'){
										$filter = "int";
										$checkboxclass = "intcheck";
									}
							?>

					     @if($lead->cre->cre == $name)
						 <tr class = "{{$filter}}">
					    	<td>
								<input class='checkbox{{$checkboxclass}}' type='checkbox' name='check[{{$lead->id}}]' value="{{$lead->id}}">
							</td>
					    	<td><a href="/lead/{{$lead->id}}/viewDispositions" target="_blank">{{ trim($lead->name) <> "" ? $lead->name : "No Name" }}</a></td>
					    	<td>{{$lead->source->master->source_name or ''}}</td>
					    	<td>{{ date('M j, Y h:i A',strtotime($lead->created_at)) }}</td>
					    	<td>{{ date('M j, Y',strtotime($lead->cre->created_at)) }}</td>
					    	
					    	<td>
					    		[ {{ $lead->dispositions()->where('name', $name)->count() . "/" . $lead->dispositions()->count()}} ] 
					    		<b>{{ $lead->disposition->master->disposition or "NA" }} : </b> 
					    		{{ $lead->disposition->remarks or "" }}
					    		<small class="pull-right">

					    			{{ isset($lead->disposition) ? date('M j, Y h:i A',strtotime($lead->disposition->created_at))  : ""}}
					    		</small>
					    	</td>

					    </tr>
					@endif
				@endforeach
					</tbody>
				</table>

				<div class="form-control">
					
		        	<select name="cre" id="cre">
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
		"bSort" : false,
		"aaSorting": [[ 2, "desc" ]]
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
		if($("#ncr").is(':checked'))
            $(".ncrcheck").prop('checked', $(this).prop("checked"));
        if($("#pan").is(':checked'))
	    	$(".pancheck").prop('checked', $(this).prop("checked"));
	    if($("#int").is(':checked'))
	    	$(".intcheck").prop('checked', $(this).prop("checked"));
	    //$("input:checkbox").prop('checked', $(this).prop("checked"));
	});
});
</script>
<script type="text/javascript">
	function filter(id)
	{
		if($("#" + id).is(':checked'))
	    	$('tr.' + id).show();  // checked
		else{
	    	$('tr.' + id).hide();// unchecked
		    if(id=="ncr")
                $(".ncrcheck").prop('checked', false);
            if(id=="pan")
		    	$(".pancheck").prop('checked', false);
		    if(id=="int")
		    	$(".intcheck").prop('checked', false);
		}
	}
</script>