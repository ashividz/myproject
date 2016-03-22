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
					<th>Measurement</th>
					<th>Prakriti</th>
				</tr>
			</thead>
			<tbody>

	@foreach($patients AS $patient)
				<tr>
					<td>{{$i++}}</td>
					<td><a href="/lead/{{ $patient->lead_id }}/viewDetails" target="_blank"> {{$patient->lead->name or "" }} </a></td>
					<td>{{ $patient->fee ? $patient->fee->start_date->format('jS M Y') : "" }}</td>	
					<td>{{ $patient->fee ? $patient->fee->end_date->format('jS M Y') : "" }}</td>	
					<td>{{ $patient->nutritionist or "" }}</td>
					<td>{{ $patient->diet ? $patient->diet->date_assign->format('jS M Y') : "" }}</td>	

				@if(trim($patient->lead->dob) == '')
					<td class="red">N</td>
				@elseif(!Helper::validateDate($patient->lead->dob->format('Y-m-d')))
					<td class="yellow" title="{{$patient->lead->dob->format('jS M, Y') }}"><span style="color:yellow">X</span></td>	
				@else
					<td class="green" title="{{$patient->lead->dob->format('jS M, Y')}}"><span style="color:green">Y</span></td>		
				@endif

				@if(trim($patient->lead->email) == '')
					<td class="red" title="Email">N</td>
				@else
					<td class="green" title="{{$patient->lead->email or ''}}"><span style="color:green">Y</span></td>
				@endif					

				@if(trim($patient->lead->phone) == '')
					<td class="red" title="Phone}}">N</td>
				@else
					<td class="green" title="{{$patient->lead->phone or ''}}"><span style="color:green">Y</span></td>
				@endif

					<td class="{{ $patient->blood_group_id == '' || $patient->rh_factor_id == '' ? 'red' : 'green'}}">{{$patient->blood_type->name or "" }} {{$patient->rh_factor->code or ""}}</td>

				@if ($patient->constipation == NULL || $patient->gas == NULL || $patient->water_retention == NULL || $patient->digestion_type == NULL || $patient->allergic == NULL || $patient->wheezing == NULL || $patient->acidity == NULL || $patient->diseases_history == NULL || $patient->energy_level == NULL || $patient->diagnosis == NULL || $patient->medical_problem == NULL || $patient->previous_weight_loss == NULL || $patient->medical_history == NULL || $patient->sweet_tooth == NULL || $patient->routine_diet == NULL || $patient->special_food_remark == NULL)
					<td class='red' title='Medical Details'>N</td>
				@else	
					<td class='green'  title='Medical Details'><span style="color:green">Y</span></td>
				@endif

				@if(isset($patient->medical))
					<td class="{{$patient->medical->created_at >= $patient->fee->fee_date ? 'green' : 'yellow'}}" title="Medical on {{$patient->medical->created_at->format('j M, Y') }}">{{ $patient->medical->created_at->format('j M, Y') }}</td>
				@else
					<td class='red'  title='Blood Test'></td>
				@endif

				@if(isset($patient->measurement))
					<td class="{{$patient->measurement->created_at >= $patient->fee->created_at ? 'green' : 'yellow'}}" title="Measurement on {{ $patient->measurement->created_at->format('j M, Y') }}">{{ $patient->measurement->created_at->format('j M, Y') }}</td>
				@else
					<td class='red'  title='Measurement Details'></td>
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