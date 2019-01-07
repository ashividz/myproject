<div class="jumbotron" style="margin:0;padding:0;">
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
                <h4>Weight Loss / Weight Gain</h4>
            </div>            
        </div>
        <div class="panel-body">

            <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#weightLoss" aria-controls="weightLoss" role="tab" data-toggle="tab">Weight Loss</a></li>
            <li role="presentation"><a href="#weightGain" aria-controls="weightGain" role="tab" data-toggle="tab">Weight Gain</a></li>
            </ul>
            <br>


            <div class="tab-content">

                <div role="tabpanel" class="tab-pane  active" id="weightLoss">
                
            
                    <table id="table-weightLoss" class="table table-bordered">

                    
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
                                <th>Duration </th>
                                <th>Avrg. weight lose </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($weightLoss as $patient)
                            <?php
                                $average_weight_lose = 0;
                                if( $patient->initialWeight && $patient->finalWeight)
                                {
                                   $weight_diff = number_format(($patient->initialWeight->weight - $patient->finalWeight->weight),2);
                                    $month = Carbon::parse($patient->finalWeight->date)
                                    ->diffInDays(Carbon::parse($patient->initialWeight->date)) / 30 ;
                                    if($month > 1)
                                    {
                                         $average_weight_lose =number_format(($weight_diff/$month),2);
                                    } 
                                    else
                                    {
                                        $average_weight_lose = $weight_diff;
                                    }

                                }
                             ?>
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

                                <td>{{$average_weight_lose or " "}}</td>
                                
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                
                <div role="tabpanel" class="tab-pane" id="weightGain">
                          
                    <table id="table-weightGain" class="table table-bordered">

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
                                <th>weight Gain</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($weightGain as $patient)
                            <tr>
                                <td>{{$y++}}</td>
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
                                    ->diffInDays(Carbon::parse($patient->initialWeight->date)) : ''}}
                                </td>
                                
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/buttons/1.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.flash.min.js"></script>
<script type="text/javascript" src = "//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.print.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#table-weightGain').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    } );
} );
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#table-weightLoss').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
             'csv'
        ]
    } );
} );
</script>
<style>
table{
    table-layout:fixed;
    width: 100%;
}
</style>