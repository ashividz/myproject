<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">Search</div>
        <div class="panel-body">
            <form class="form" method="POST"> 
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row"> 
                    <div class="col-md-2">
                        Pin
                        <input name="pin" class="form-input">
                    </div>
                </div>
                <hr>
                <div class="row">
                    
                    <div class="col-md-4">
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        <button type="submit" name="reset" class="btn btn-danger">Reset</button>
                    </div>
                </div>
            </form>                
        </div>
    </div>


<?php $i=0;?>
    <div class="panel panel-default">
        <div class="panel-heading">
        
        @if($leads)
            <div class="pull-right" style="margin-top:-8px;">
                <a name="download" id="downloadCSV" class="btn btn-primary" download="filename.csv">Download Csv</a>  
            </div>
        @endif

            <div class="pull-left"><b>Search For : </b> </div>
            <div style="margin-left:80px"> <i>{!! $searchFor or "Nothing To Search For" !!}</i></div>

        </div>
        <div class="panel-body">

                <table id="example" class="table table-bordered">
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lead Id</th>
                            <th>Patient Id</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>PIN</th>
                        </tr>
                    </thead>
                        
                    <tbody>
            @if($leads <> NULL)
                @foreach($leads as $lead)
                    <?php $i++;?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td><a href="/lead/{{ $lead->id }}/viewDispositions" target="_blank">{{ $lead->id }}</a></td>
                            <td>

                        @if(isset($lead->patient))
                                <a href="http://crm/patient.php?clinic={{ $lead->clinic }}&registration_no={{ $lead->registration_no }}" target="_blank">{{ $lead->patient->id }}</a>                        
                        @endif    
                        
                            </td>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->country }}</td>
                            <td>{{ $lead->state }}</td>
                            <td>{{ $lead->city }}</td>
                            <td>{{ $lead->zip }}</td>
                        </tr>                
                @endforeach
            @endif

                @if(!$leads)
                    <tr>
                        <td colspan="8">No results found</td>
                    </tr>
                @endif
                    </tbody>

                </table>
        </div>
    </div>


</div>
<script type="text/javascript">
$(document).ready(function() 
{

  $( "#downloadCSV" ).bind( "click", function() 
  {
    var csv_value = $('#example').table2CSV({
                delivery: 'value'
            });
    downloadFile('leads.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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