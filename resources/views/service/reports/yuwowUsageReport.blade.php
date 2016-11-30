<div class="container">     
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
                <h4>YuWoW Usage Report</h4>
            </div>
        </div>
                <a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Results Csv</a>
                <form action="{{url('yuwow/yuwowUsers')}}" method="post" target="_blank" id="formx">
                    {{csrf_field()}}
                    <input type="hidden" name="user" value="" id="user"><br>
                </form>    
                <table id="table_yuwow_usage" class="table table-striped table-bordered">
                        
                        <thead>
                            <tr>
                                <th>Nutritionist</th>
                                <th>Clients serviced as on {{date("F j, Y")}}</th>
                                <th>using yuwow</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>

                <?php
                    $totalOnDateServicedClients = 0;
                    $totalYuWoWUsers            = 0;
                ?>
                
                @foreach($yuwowUsage AS $yuwow)

                            @if(in_array($yuwow->nutritionist,$serviceTLs))
                            <tr>
                                <td><a href="{{url('yuwow/yuwowUsers')}}" id="{{$yuwow->nutritionist}}" class="nutritionist" target="_blank">{{$yuwow->nutritionist}}</a></td>
                                <td>{{$yuwow->onDateServicedClients}}</td>
                                <td>{{$yuwow->yuwowUsers}}</td>
                                <td></td>
                            </tr>
                            @else
                            <tr>
                                <td><a href="{{url('yuwow/yuwowUsers')}}" id="{{$yuwow->nutritionist}}" class="nutritionist" target="_blank">{{$yuwow->nutritionist}}</a></td>
                                <td>{{$yuwow->onDateServicedClients}}</td>
                                <td>{{$yuwow->yuwowUsers}}</td>
                                <td>{{number_format($yuwow->yuwowUsers/$yuwow->onDateServicedClients*100,2)}}</td>
                            </tr>
                            @endif
                <?php
                    $totalOnDateServicedClients += $yuwow->onDateServicedClients;
                    $totalYuWoWUsers            += $yuwow->yuwowUsers;
                ?>
                @endforeach
                


                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td>{{$totalOnDateServicedClients}}</td>
                                <td>{{$totalYuWoWUsers}}</td>
                                <td>{{number_format($totalYuWoWUsers/$totalOnDateServicedClients*100,2)}}</td>
                            </tr>
                        </tfoot>
                    </table>
    </div>

</div>
<script type="text/javascript">
$(document).ready(function() 
{
    
    $( "#downloadCSV" ).bind( "click", function() 
    {
        var csv_value = $('#table_yuwow_usage').table2CSV({
                delivery: 'value'
            });
        downloadFile('yuwowUsage.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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

    $('#table_yuwow_usage').dataTable({
        bPaginate : false
    });
});
</script>

<script>
$(".nutritionist").click(function( event ) {
  event.preventDefault();
  $("#user").val($(this).attr('id'));
  $( "#formx" ).submit();
});
</script>

