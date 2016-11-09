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
                                <th style="width:50px">Last call</th>
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

                        //find last disposition
                        $lastDisposition  = collect([$patient->lead->dialerphonedisposition,$patient->lead->dialermobiledisposition])
                            ->sortByDesc('eventdate')->first();
                    ?>
                            <tr>
                                <td><a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank">{{ $patient->lead->name or "No Name" }}</a><div class="pull-right"><em>[{{ $fee->entry_date or ""}}]</em></div></td>
                                <td>{{$patient->id}}</td>
                                <td>{{ $patient->nutritionist}}</td>
                                <td>{{ !$patient->doctors->isEmpty() ? $patient->doctor : "" }}</td>

                                <td>{{ $patient->lead->country or "" }} - {{ $patient->lead->region->region_name or "" }} - {{ $patient->lead->city or "" }}</td>

                                <td>{{ $fee->source->source_name or "" }} </td>
                                <td>{{ $fee->entry_date}}</td>
                                <td>{{ $fee->start_date->format('d-M-Y')}} </td>

                                <td>{{ $fee->end_date->format('d-M-Y')}} </td>
                            @if($fee->start_date >= date('Y-m-d 00:00:00') &&  $fee->start_date <= date('Y-m-d 23:59:59'))
                                <td>Today</td>
                            @else
                                <td>{{ $fee->start_date->diffForHumans()}} </td>
                            @endif
                                <td>&#8377;{{$fee->total_amount}}</td>
                                <td>{!!$notes!!} {{$patient->suit->trial_plan or ""}}</td>
                                <td>@if($lastDisposition) {{date('M j, Y, g:i a',strtotime($lastDisposition->eventdate))}} <a data-toggle="modal" data-target="#disposition" href="/patient/{{$patient->id}}/calls"><i class="fa fa-mobile danger" aria-hidden="true"></i></a>@endif</td>
           
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Template-->
<div class="modal fade" id="disposition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Dispositions</h4>

            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" }); 
});

$(document).on('hidden.bs.modal', function (e) {
    $(e.target).removeData('bs.modal');
});


</script>
<style type="text/css">
    .popover {
        text-align: left;
        max-width: 1250px;
    }
</style>
<style type="text/css">
    #disposition .modal-dialog {
        /* new custom width */
        width: 95%;
    }
</style>