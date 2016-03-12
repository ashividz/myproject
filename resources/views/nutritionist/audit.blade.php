<style type="text/css">
	.audit {
		font-size: 9px;
	}
	table tr td.red {
		background-color: #ca4e4e;
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
		<div class="pull-right">
			@include('nutritionist/partials/users')
		</div>
		<h4>Audit</h4>
	</div>
	<div class="panel-body">
    	<table id="table" class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Gender</th>
					<th>DOB</th>
					<th>Address</th>
					<th>Email</th>
					<th>Phone</th> 
					<th>Mobile</th> 
					<th>Blood Group</th>
					<th>Medical</th>
					<th>Prakriti</th>
				</tr>
			</thead>
			<tbody>

	@foreach($patients AS $patient)
				<tr>
					<td>{{$i++}}</td>
					<td><a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank"> {{$patient->lead->name or ""}} </a></td>	
				
				@if(trim($patient->lead->gender) == '')
					<td class="red">N</td>
				@else
					<td class="green" title="{{$patient->lead->gender or ''}}"><i class="fa fa-2x {{$patient->lead->gender == 'F' ? 'fa-female' : 'fa-male'}}"style="color:white"></i></td>	
				@endif

				@if(trim($patient->lead->dob) == '')
					<td class="red">N</td>
				@elseif(!Helper::validateDate($patient->lead->dob) || $patient->lead->dob > date('Y-m-d', strtotime('-10 years')))
					<td class="yellow" title="{{$patient->lead->dob or ''}}"><span style="color:yellow">X</span></td>	
				@else
					<td class="green" title="{{$patient->lead->dob or ''}}"><span style="color:green">Y</span></td>		
				@endif

				@if(trim($patient->lead->country) == '' || trim($patient->lead->state) == '' || trim($patient->lead->city) == '')
					<td class="red"><span class="red">N</span></td>	
				@else					
					<td class="green" title="{{$patient->lead->country or ''}}-{{$patient->lead->state or ''}}-{{$patient->lead->city or ''}}"><span style="color:green">Y</span></td>		
				@endif

				@if(trim($patient->lead->email) == '')
					<td class="red" title="Email">N</td>
				@else
					<td class="green" title="{{$patient->lead->email or ''}}"><span style="color:green">Y</span></td>
				@endif					

				@if(trim($patient->lead->phone) == '')
					<td class="red" title="Phone">N</td>
				@else
					<td class="green" title="{{$patient->lead->phone or ''}}"><span style="color:green">Y</span></td>
				@endif

				@if(trim($patient->lead->mobile) == '')
					<td class="red" title="Mobile">N</td>
				@else
					<td class="green" title="{{$patient->lead->mobile or ''}}"><span style="color:green">Y</span></td>
				@endif

					<td class="{{$patient->blood_group_id && $patient->rh_factor_id ? 'green' : 'red'}}">{{$patient->blood_type->name or ""}}{{$patient->rh_factor->code or ""}}</td>

				@if ($patient->constipation == NULL || $patient->gas == NULL || $patient->water_retention == NULL || $patient->digestion_type == NULL || $patient->allergic == NULL || $patient->wheezing == NULL || $patient->acidity == NULL || $patient->diseases_history == NULL || $patient->energy_level == NULL || $patient->diagnosis == NULL || $patient->medical_problem == NULL || $patient->previous_weight_loss == NULL || $patient->medical_history == NULL || $patient->sweet_tooth == NULL || $patient->routine_diet == NULL || $patient->special_food_remark == NULL)
					<td class='red' title='Medical Details'>N</td>
				@else	
					<td class='green'  title='Medical Details'><span style="color:green">Y</span></td>
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