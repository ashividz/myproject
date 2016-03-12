<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
      <div class="pull-right" style="">
        <a name="download" id="downloadCSV" class="btn btn-primary" download="filename.csv">Download Csv</a>   
        <input type="hidden" name="csv_text" id="csv_text"> 
        <button type="button" id="edit" class="btn btn-success">Edit</button>        
      </div>
			<h4>Assign Nutritionist</h4>			
		</div>
		<div class="panel-body">
			<div class="container">
				<form id="form" method="post">
					<table id="table" class="table striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Primary Nutritionist</th>
								<th>Start Date</th>
								<th>Secondary Nutritionist</th>
								<th>Start Date</th>
							</tr>
						</thead>
						<tbody>

						@foreach ($patients as $patient)
							<tr>
								<td>
                  <a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank">{{ $patient->lead->name }}</a>
                </td>
								<td>
                  {{ $patient->fees ? date('M j, Y',strtotime($patient->fees->last()->start_date)) : "" }}
                </td>
								<td>
                  {{ $patient->fees ? date('M j, Y',strtotime($patient->fees->last()->end_date )) : "" }}
                </td>
                <td>
                  <span class='editable_primary' id='{{ $patient->id }}'>
                    {{ $patient->primaryNtr->last()->nutritionist or "" }}
                  </span>
                </td>
                <td>
                  {{ !$patient->primaryNtr->isEmpty() ? date('M j, Y',strtotime($patient->primaryNtr->last()->created_at)) : "" }}
                </td>
								<td>
									<span class='editable_secondary' id='{{ $patient->id }}'>
										{{ $patient->secondaryNtr->last()->nutritionist or "" }}
									</span>
								</td>
								<td>
                  {{ !$patient->secondaryNtr->isEmpty() ? date('M j, Y',strtotime($patient->secondaryNtr->last()->created_at)) : "" }}
                </td>
							</tr>
						@endforeach

						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#table').dataTable({
      "iDisplayLength": 100,
      "aaSorting": []
  	}); 

  //Initialize Editable Plugin on Paginate

  $( "#edit" ).on( "click", function() {
 
    editableInit();
   
  });

  $( "#downloadCSV" ).bind( "click", function() 
  {
    var csv_value = $('#table').table2CSV({
                delivery: 'value'
            });
    downloadFile('patient_nutritionists.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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

  function editableInit() {
    $(".editable_primary").editable("/service/savePrimaryNutritionist", { 
      loadurl   : "/api/getNutritionists",
      type      : "select",
      submit    : "OK",
      cancel    : "Cancel",
      tooltip   : "Click to edit..."
  });

  $(".editable_secondary").editable("/service/saveSecondaryNutritionist", { 
      loadurl   : "/api/getNutritionists",
      type      : "select",
      submit    : "OK",
      cancel    : "Cancel",
      tooltip   : "Click to edit..."
  });
  }

  

});
</script>