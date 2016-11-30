<div class="container1">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/daterange')
				</div>
				<h4>Program End (Rejoin)</h4>
			</div>	
			<div class="panel-body">
			<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Csv</a>
				<form id="form" class="form-inline" action="/marketing/saveRejoin" method="post">
					<table id="table" class="table table-bordered">
						<thead>
							<tr>
								<td>#</td>
								<td><input type="checkbox" id="checkAll"></td>
								<td width="15%">Name</td>
								<td>Lead Id</td>
								<td>Start Date</td>
								<td>End Date</td>
								<td>CRE</td>
								<td>Nutritionist</td>
								<td>Source</td>
								<td>Status</td>
								<td width="20%">Last Disposition</td>
							</tr>
						</thead>
						<tbody>

				@foreach($patients AS $patient)

							<tr>
							<td>{{$i++}}</td>
							<td><input class='checkbox' type='checkbox' name='check[{{$patient->lead_id}}]' value="{{$patient->lead_id}}"></td>
							<td><a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank">{{$patient->lead->name or ""}}</a><div class="pull-right"><small><em>{{$patient->fee->cre}}</em></small></div></td>
							<td>{{$patient->lead_id}}</td><td>{{date('jS M, Y', strtotime($patient->fee->start_date))}}</td>
							<td>{{date('jS M, Y', strtotime($patient->fee->end_date))}}<input type="hidden" name="end_date[{{$patient->lead_id}}]" value="{{$patient->fee->end_date}}"></td>
							<td>{{$patient->lead->cre->cre or ""}}<div class="pull-right"><small><em>{{isset($patient->lead->cre) ? date('jS M, Y', strtotime($patient->lead->cre->created_at)) : ""}}</em></small></div></td>
							<td>{{$patient->nutritionist or ""}}</td>
							<td>{{$patient->lead->source->master->source_name or ""}}</td>
							<td><div class="status" data-score="{{ $patient->lead->status_id or "" }}" number="{{ $patient->lead && $patient->lead->status_id > 5 ? $patient->lead->status_id : 5 }}"></td>
							<td>@if(isset($patient->lead->disposition))<a href="/lead/{{$patient->lead_id}}/viewDispositions" target="_blank">{{$patient->lead->disposition->name or ""}}</a> : <b>{{$patient->lead->disposition->master->disposition or ""}} </b> -<em>{{$patient->lead->disposition->remarks or ""}}</em> <div class="pull-right"><small><em>{{date('jS M, Y h:i A', strtotime($patient->lead->disposition->created_at))}}</em></small></div>@endif</td>
							</tr>

				@endforeach

						</tbody>
					</table>
					<div class="form-control">
						
			        	<select name="cre" id="cre" required>
			        		<option>Select User</option>

			        	@foreach($users AS $user)
			                <option value="{{$user->name}}">{{$user->name}}</option>
			        	@endforeach	

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