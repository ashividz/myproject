<div class="jumbotron" style="margin:0;padding:0;">
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
                <h4>Nutritionist wise appointments</h4>                
            </div>            
                
        </div>
        <div class="panel-body">
                <a id="downloadCSV" class="btn btn-primary pull-right" style="margin-bottom:2em;">download appointments</a>
                <form action="{{url('nutritionist/patients')}}" method="post" target="_blank" id="formx">
                    {{csrf_field()}}
                    <input type="hidden" name="user" value="" id="user"><br>
                </form>
                <table id="appointments" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Nutritionist</th>
                            <th>Total Patients</th>                            
                            <th>Today's Appointments</th>                            
                            <th>Remaining Appointments</th>                            
                            <th>total diets(no of patients)</th> 
                            <th>diet not sent for 7 days</th> 
                            <th>diet not started</th> 
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $i=0;
                        $totalPatients            = 0;
                        $totalTodaysAppointments  = 0;
                        $totalCurrentAppointments = 0;
                        $totalDiets               = 0;
                        $totalBrakes              = 0;
                        $totalDietNotStarted           = 0;
                    ?>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{++$i}}</td>
                            <td><a href="{{url('nutritionist/patients')}}" id="{{$appointment->nutritionist}}" class="nutritionist">{{$appointment->nutritionist}}</td>
                            <td>{{$appointment->totalPatients}}</td>
                            <td>{{$appointment->todaysAppointments}}</td>
                            <td>{{$appointment->currentAppointments}}</td>
                            <td>{{$appointment->diets}}</td>
                            <td>{{$appointment->brakes}}</td>
                            <td>{{$appointment->dietNotStarted}}</td>
                        </tr>
                    <?php 
                        $totalPatients              += $appointment->totalPatients;
                        $totalTodaysAppointments    += $appointment->todaysAppointments;
                        $totalCurrentAppointments   += $appointment->currentAppointments;
                        $totalDiets                 += $appointment->diets;
                        $totalBrakes                += $appointment->brakes;
                        $totalDietNotStarted        += $appointment->dietNotStarted;
                    ?>
                    @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td>Total</td>
                            <td>{{$totalPatients}}</td>
                            <td>{{$totalTodaysAppointments}}</td>
                            <td>{{$totalCurrentAppointments}}</td>
                            <td>{{$totalDiets}}</td>
                            <td>{{$totalBrakes}}</td>
                            <td>{{$totalDietNotStarted}}</td>
                        </tr>
                    </tfoot>
                </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('#appointments').dataTable({
        bPaginate : false,
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
        },
    });
});
</script>
<script type="text/javascript">
$(document).ready(function() 
{
    

    $( "#downloadCSV" ).bind( "click", function() 
    {
        var csv_value = $('#appointments').table2CSV({
                delivery: 'value'
            });
        downloadFile('appointments.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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
$(".nutritionist").click(function( event ) {
  event.preventDefault();
  $("#user").val($(this).attr('id'));
  $( "#formx" ).submit();
});
</script>
