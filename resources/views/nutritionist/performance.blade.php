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
	<?php
		$Weight = 0 ;
		$count = 0;
	?>
	<div class="panel-body">
    	<table id="table" class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>initial weight</th>
                    <th>initial bmi</th>
                    <th>initial weight date</th>
                    <th>final weight</th>
                    <th>final bmi</th>
                    <th>final weight date</th>
                    <th>weight loss</th>	
				</tr>
			</thead>
			<tbody>

	@foreach($patients AS $patient)
				<tr> 
					<?php
						if($patient->initialBMI)
						{
							if($patient->initialBMI > 23)
							{
							    $weightlose = $patient->initialWeight && $patient->finalWeight  ?
				                        (number_format(($patient->initialWeight->weight - $patient->finalWeight->weight),2)) : 0;
				                $Weight = $Weight +  $weightlose ;
				                $count++;
			            	}
			            }
					?>
					<td>{{$i++}}</td>
					<td><a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank"> {{$patient->lead->name or ""}} </a></td>	
					<td>{{$patient->initialWeight->weight or ''}}</td>
                    <td>{{$patient->initialBMI or ''}}</td>
                    <td>{{$patient->initialWeight->date or ''}}</td>
                    <td>{{$patient->finalWeight->weight or ''}}</td>
                    <td>{{$patient->finalBMI or ''}}</td>
                    <td>{{$patient->finalWeight->date or ''}}</td>
                    <td>{{ ( $patient->initialWeight && $patient->finalWeight ) ?
                                (number_format(($patient->initialWeight->weight - $patient->finalWeight->weight),2)) : ''}}</td>
				</tr>
	@endforeach
			</tbody>
		</table>
	</div>
	<div>
		<td> <strong> Average Weight Lose </strong></td>
			@if($count)
				<td style="margin-left: 100%">  <strong> {{ $Weight/$count }}  </strong> </td>
			@endif
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