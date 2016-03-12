<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
      <div class="pull-right" style="">
        <a name="download" id="downloadCSV" class="btn btn-primary" download="filename.csv">Download Csv</a>   
        <input type="hidden" name="csv_text" id="csv_text"> 
        <button type="button" id="edit" class="btn btn-success">Edit</button>        
      </div>
			<h4>Assign Nutritionist & Doctor</h4>			
		</div>
		<div class="panel-body">
			<div class="container1">
				<form id="form" method="post">
					<table id="table" class="table table-bordered">
						<thead>
							<tr>
								<th>Name</th>
                <th>Address</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Primary Nutritionist</th>
								<th>Secondary Nutritionist</th>
                <th>Doctor</th>
							</tr>
						</thead>
						<tbody>

						@foreach ($patients as $patient)
							<tr>
								<td><a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank">{{ $patient->lead->name or "No Name" }}</a><div class="pull-right"><em>[
                  @if($patient->cfee)
                    {{ $patient->cfee->entry_date->format('d-M-Y')}} 
                  @elseif($patient->fee)
                    {{$patient->fee->entry_date->format('d-M-Y')}}
                  @endif  
                  ]</em></div></td>

                <td>{{$patient->lead->country}}{{$patient->lead->region? '-'.$patient->lead->region->region_name : ''}}{{$patient->lead->city?'-'.$patient->lead->city:''}}</td>

								<td>
                  @if($patient->cfee)
                    {{ $patient->cfee->start_date->format('d-M-Y')}} 
                  @elseif($patient->fee)
                    {{$patient->fee->start_date->format('d-M-Y')}}
                  @endif    
                </td>

								<td>
                  @if($patient->cfee)
                    {{ $patient->cfee->end_date->format('d-M-Y')}} 
                  @elseif($patient->fee)
                    {{$patient->fee->end_date->format('d-M-Y')}}
                  @endif 
                </td>
                
                <td><span class='editable_primary' id='{{ $patient->id }}'>{{ $patient->primaryNtr->first()->nutritionist or "" }}</span><span class="pull-right"><em>{{ !$patient->primaryNtr->isEmpty() ? "[".date('Y-m-d',strtotime($patient->primaryNtr->first()->created_at))."]" : "" }}</em></span></td>

                <td><span class='editable_secondary' id='{{ $patient->id }}'>{{ $patient->secondaryNtr->first()->nutritionist or "" }}</span><span class="pull-right"><em>{{ !$patient->secondaryNtr->isEmpty() ? "[".date('Y-m-d',strtotime($patient->secondaryNtr->first()->created_at))."]" : "" }}</em></span></td>
							@if(!$patient->doctors->isEmpty())	
                <td><span class='editable_doctor' id='{{ $patient->id }}'>Dr. {{ $patient->doctors->first()->name}}</span><span class="pull-right"><em>[{{ date('Y-m-d',strtotime($patient->doctors->first()->created_at))}}]</em></span></td>
              @else
                <td><span class='editable_doctor' id='{{ $patient->id }}'></span></td>
              @endif
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
      "aaSorting": [[ 2, "desc" ]]
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
    $(".editable_primary").editable("/service/saveNutritionist", { 
      loadurl   : "/api/getNutritionists",
      type      : "select",
      submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      placeholder: '<span class="placeholder">(Edit)</span>',
    });

    $(".editable_secondary").editable("/service/saveNutritionist?secondary=1", { 
      loadurl   : "/api/getNutritionists",
      type      : "select",
      submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      placeholder: '<span class="placeholder">(Edit)</span>',
    });

    $(".editable_doctor").editable("/service/saveDoctor", { 
      loadurl   : "/api/getUsers?role=doctor",
      type      : "select",
      submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      placeholder: '<span class="placeholder">(Edit)</span>',
    });
  }

  

});
</script>