<style type="text/css">
	.audit {
		font-size: 9px;
	}
	.red {
		background-color: red;
		text-align: center;
	}
	.yellow {
		background-color: yellow;
		text-align: center;
	}
	.green {
		background-color: green;
		text-align: center;
	}
	h3 {
		margin-top: 5px;
		color: #fff;
	}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Csv</a>
		<h4>Audit</h4>
	</div>
	<div class="panel-body">
    	<table id="table" class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Nutritionist</th>
					<th>Last Diet</th>
					<th>DOB</th>
					<th>Email</th>
					<th>Phone</th> 
					<th>Blood Group</th>
					<th>Medical</th>
					<th>Blood Test</th>
					<th>Fitness</th>
					<th>Prakriti</th>
				</tr>
			</thead>
			<tbody>

	@foreach($patients AS $patient)
				<tr>
					<td>{{$i++}}</td>
					<td><a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank"> {{$patient->name or ""}} </a></td>
					<td>{{$patient->start_date or ""}}</td>	
					<td>{{$patient->end_date or ""}}</td>	
					<td>{{$patient->nutritionist or ""}}</td>
					<td>{{$patient->diet->date_assign or ''}}</td>	

				@if(trim($patient->dob) == '')
					<td class="red">N</td>
				@elseif(!Helper::validateDate($patient->dob))
					<td class="yellow" title="{{$patient->dob or ''}}"><span style="color:yellow">X</span></td>	
				@else
					<td class="green" title="{{$patient->dob or ''}}"><span style="color:green">Y</span></td>		
				@endif

				@if(trim($patient->email) == '')
					<td class="red" title="Email">N</td>
				@else
					<td class="green" title="{{$patient->email or ''}}"><span style="color:green">Y</span></td>
				@endif					

				@if(trim($patient->phone) == '')
					<td class="red" title="Phone}}">N</td>
				@else
					<td class="green" title="{{$patient->phone or ''}}"><span style="color:green">Y</span></td>
				@endif

					<td class="{{trim($patient->blood_group) == '' ? 'red' : 'green'}}">{{$patient->blood_group}}</td>

				@if ($patient->constipation == NULL || $patient->gas == NULL || $patient->water_retention == NULL || $patient->digestion_type == NULL || $patient->allergic == NULL || $patient->wheezing == NULL || $patient->acidity == NULL || $patient->diseases_history == NULL || $patient->energy_level == NULL || $patient->diagnosis == NULL || $patient->medical_problem == NULL || $patient->previous_weight_loss == NULL || $patient->medical_history == NULL || $patient->sweet_tooth == NULL || $patient->routine_diet == NULL || $patient->special_food_remark == NULL)
					<td class='red' title='Medical Details'>N</td>
				@else	
					<td class='green'  title='Medical Details'><span style="color:green">Y</span></td>
				@endif

				@if(isset($patient->medical_date))
					<td class="{{$patient->medical_date >= $patient->fee_date ? 'green' : 'yellow'}}" title="{{$patient->medical_date or ''}}">{{date('Y-m-d', strtotime($patient->medical_date))}}</td>
				@else
					<td class='red'  title='Blood Test'></td>
				@endif

				@if(isset($patient->fitness_date))
					<td class="{{$patient->fitness_date >= $patient->fee_date ? 'green' : 'yellow'}}" title="{{$patient->fitness_date or ''}}">{{date('Y-m-d', strtotime($patient->fitness_date))}}</td>
				@else
					<td class='red'  title='Fitness Details'></td>
				@endif

					<td class="{{$patient->prakritis->isEmpty() ? 'red' :'green'}}" title="Prakriti"></td>	

				</tr>
	@endforeach

			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#table').dataTable({

	    "sPaginationType": "full_numbers",
	    "iDisplayLength": 100,
	    "bPaginate": false
  	}); 


  $( "#downloadCSV" ).bind( "click", function() 
  {
    var csv_value = $('#table').table2CSV({
                delivery: 'value'
            });
    downloadFile('audit.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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