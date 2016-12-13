<div class="jumbotron" style="margin:0;padding:0;">
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
                <h4>Weight Loss</h4>
            </div>            
        </div>
        <div class="panel-body">
                <table id="weightLoss" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Patient id</th>
                            <th>Name</th>
                            <th>height(cm)</th>
                            <th>Nutritionist</th>
                            <th>start date</th>
                            <th>end date</th>
                            <th>duration</th>
                            <th>initial weight</th>
                            <th>initial bmi</th>
                            <th>initial weight date</th>
                            <th>final weight</th>
                            <th>final bmi</th>
                            <th>final weight date</th>
                            <th>weight loss</th>
                            <th>weight loss/gain duration</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <td>{{$x++}}</td>
                            <td><a href="/patient/{{$patient->id}}/weight" target="_blank">{{$patient->id}}</a></td>
                            <td><a href="/patient/{{$patient->id}}/weight" target="_blank">{{$patient->lead->name}}</a></td>
                            <td>{{$patient->lead->height}}({{$patient->lead->feet}} ft {{$patient->lead->inches}} ins)</td>
                            <td>{{$patient->nutritionist}}</td>
                            <td>{{$patient->startDate->toDateString()}}</td>
                            <td>{{$patient->endDate->toDateString()}}</td>
                            <td><a href="/lead/{{$patient->lead->id}}/viewDetails" target="_blank">{{$patient->duration}}</td>
                            <td>{{$patient->initialWeight->weight or ''}}</td>
                            <td>{{$patient->initialBMI or ''}}</td>
                            <td>{{$patient->initialWeight->date or ''}}</td>
                            <td>{{$patient->finalWeight->weight or ''}}</td>
                            <td>{{$patient->finalBMI or ''}}</td>
                            <td>{{$patient->finalWeight->date or ''}}</td>
                            <td>{{ ( $patient->initialWeight && $patient->finalWeight ) ?
                            (number_format(($patient->initialWeight->weight - $patient->finalWeight->weight),2)) : ''}}</td>
                            <td>{{
                                ( ( $patient->initialWeight && $patient->finalWeight ) ) ?
                                Carbon::parse($patient->finalWeight->date)
                                ->diffInDays(Carbon::parse($patient->initialWeight->date)) : ''
                            }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
        </div>
    </div>
</div>
<script type="text/javascript" src = "/js/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "/js/datatables/dataTables.buttons.min.js"></script>
<script type="text/javascript" src = "/js/datatables/buttons.flash.min.js"></script>
<script type="text/javascript" src = "/js/datatables/jszip.min.js"></script>
<script type="text/javascript" src = "/js/datatables/pdfmake.min.js"></script>
<script type="text/javascript" src = "/js/datatables/vfs_fonts.js"></script>
<script type="text/javascript" src = "/js/datatables/buttons.html5.min.js"></script>
<script type="text/javascript" src = "/js/datatables/buttons.print.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#weightLoss').DataTable( {
        "iDisplayLength": 5000,
        dom: 'Bfrtip',
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
        },
        buttons: [
            'csv'
        ],        
    } );
} );
</script>