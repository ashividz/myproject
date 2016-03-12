<div class="container1">
    <div class="panel panel-default">
        <div class="panel-heading">
      <div class="pull-right" style="">
        <a name="download" id="downloadCSV" class="btn btn-primary" download="filename.csv">Download Csv</a>        
      </div>
            <h4>Diet not started</h4>           
        </div>
        <div class="panel-body">
            <div class="container1">
                <form id="form" method="post">
                    <table id="table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Patient Id</th>
                                <th>Nutritionist</th>
                                <th>Doctor</th>
                                <th width="10%">Address</th>
                                <th>Source</th>
                                <th>Entry Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th style="width:50px">Remark</th>
                            </tr>
                        </thead>
                        <tbody>

                    @foreach ($patients as $patient)
                    <?php
                        $fee = isset($patient->cfee) ? $patient->cfee : $patient->fee;
                        $notes = "";
                        foreach ($patient->notes as $note) {
                            $notes .= $note->text;
                            $notes .= "<span class='pull-right'><em><small>".$note->created_by." [".$note->created_at->format('jS M Y')."]"."</small></em></span><p>";
                        }
                    ?>
                            <tr>
                                <td><a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank">{{ $patient->lead->name or "No Name" }}</a><div class="pull-right"><em>[{{ $fee->entry_date or ""}}]</em></div></td>
                                <td>{{$patient->id}}</td>
                                <td>{{ $patient->nutritionist}}</td>
                                <td>{{ !$patient->doctors->isEmpty() ? $patient->doctor : "" }}</td>

                                <td>{{$patient->lead->country or ""}}{{$patient->lead->region? '-'.$patient->lead->region->region_name : ''}}{{$patient->lead->city?'-'.$patient->lead->city:''}}</td>

                                <td>{{ $fee->source->source_name or "" }} </td>
                                <td>{{ $fee->created_at->format('d-M-Y h:i A')}} </td>
                                <td>{{ $fee->start_date->format('d-M-Y')}} </td>

                                <td>{{ $fee->end_date->format('d-M-Y')}} </td>
                            @if($fee->start_date >= date('Y-m-d 00:00:00') &&  $fee->start_date <= date('Y-m-d 23:59:59'))
                                <td>Today</td>
                            @else
                                <td>{{ $fee->start_date->diffForHumans()}} </td>
                            @endif
                                <td>&#8377;{{$fee->total_amount}}</td>
                                <td>{!!$notes!!} {{$patient->suit->trial_plan or ""}}</td>
           
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
      "aaSorting": [[ 5, "desc" ]],
      "bPaginate": false
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

});
</script>