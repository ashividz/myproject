@include('partials.daterange')

<div class="container">
    <div class="panel panel-default">
            <div class="panel-heading">
               <h4 style='margin-right: 20px;'>Converted Leads <a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="float: right" download="filename.csv">Download Leads Csv</a></h4>
                
               
            </div>  
            <div class="panel-body">
            
            {!! csrf_field() !!}                     
            <table id="leads1" class="table table-bordered">
                        <thead>
                                <th>#</th>
                                <th>CRE</th>
                                <th>TL</th>
                                <th>Lead Name</th>
                                <th>Remarks</th>
                                <th>Disposition_date</th>
                                <th>Conversion Date</th>
                                 <th>Time Taken</th>
                        </thead>
                        <tbody>
                        <?php
                        $count = 0;
                        ?>
                        @if($leads)
                            @foreach($leads AS $lead)

                            @if($lead)
                                 <tr><td>{{ $i++ }}</td>
                                     <td> @if($lead->cre){{$lead->cre->cre}}@endif</td>
                                     <td>@if($lead->employee && $lead->employee->supervisor){{ $lead->employee->supervisor->employee->name }}@endif
                                        </td>
                                         <td><a href="/lead/{{$lead->id}}/viewDispositions" target="_blank">{{$lead->name}}</a></td>
                                          
                                         <?php 
                                          $interested_disposition = null;
                                          if($lead->dispositions)
                                          { 
                                            foreach($lead->dispositions AS $disposition)
                                              if($disposition->disposition_id=="9" and ($disposition->created_at >= $start_date and $disposition->created_at<= $end_date))
                                                  $interested_disposition = $disposition;

                                                if($interested_disposition)
                                                  $count++;
                                          }
                                          ?>
                                          <td>@if($interested_disposition)<b>{{ $interested_disposition->master->disposition_code or "" }} : </b><i>{{ $interested_disposition->remarks }}</i>@endif</td>
                                          <td>@if($interested_disposition){{ $interested_disposition->created_at }}@endif</td>
                                         <?php 
                                              $fees = null;
                                              $conversion_fee = null;
                                              if($lead->patient && $lead->patient->fees)
                                              { 
                                                $fees = $lead->patient->fees;
                                                if($fees)
                                                foreach($fees AS $fee)
                                                  if($fee->created_at == $lead->conversion_date)
                                                  {
                                                      $conversion_fee = $fee;
                                                      break;
                                                  }
                                                }

                                                if($interested_disposition)
                                                  $days_between = ceil(abs(strtotime($conversion_fee->created_at) - strtotime($interested_disposition->created_at)) / 86400);
                                              ?>
                                            <td>@if($conversion_fee){{$conversion_fee->created_at?$conversion_fee->created_at:""}}@endif</td>
                                            <td>@if($interested_disposition){{$days_between}} @endif</td>
                                       </tr>
                                    @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                     
                    <h3 id='csv_text'>@if($leads->count()) Interested {{$count}}/{{$leads->count()}} &nbsp;&nbsp;<span style='color: #666'>{{round(($count*100)/$leads->count(),2)}}% @endif</span></h3>
                    

            </div>          
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#leads').dataTable({
      "iDisplayLength" : 100,
      "bPaginate": false,
        "bInfo" : true
    });

   
  $( "#downloadCSV" ).bind( "click", function() 
  {
 
    var csv_value = $('#leads1').table2CSV({
                delivery: 'value'
            });
   
    downloadFile('leads.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
      
  });

        
});


 function downloadFile(fileName, urlData){

    var aLink = document.createElement('a');
    var evt = document.createEvent("HTMLEvents");
    evt.initEvent("click");
    aLink.download = fileName;
    aLink.href = urlData ;
    aLink.dispatchEvent(evt);
}
</script>