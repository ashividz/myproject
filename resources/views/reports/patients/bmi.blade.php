<div class="container">  
  <div class="panel panel-default">
    <div class="panel-heading">             
            <div class="pull-left">
                @include('partials/daterange')
            </div>
            <h4 style="margin-left:400px">Weight Loss/BMI</h4>
    </div>
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#age" aria-controls="age" role="tab" data-toggle="tab">Patients</a></li>
            <li role="presentation"><a href="#active" aria-controls="active" role="tab" data-toggle="tab">Active Patients</a></li>
        </ul>
        <br>

        <!-- Tab panes -->
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane  active" id="age">
                
            
                <table id="table-age" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patients Id</th>
                            <th>Patients Name</th>
                            <th>Country</th>
                            <th>Nutritionist</th>
                            <th>Initial weight</th>
                            <th>Initial BMI</th>
                            <th>Final Weight</th>
                            <th>Final BMI</th>
                            <th>Total Weighloss</th>
                            <th>Duration</th>

                            
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($patients AS $patient)
                    <?php
                        $from = new DateTime($patient->lead->dob);
                        $to = new DateTime('today');
                        $dob = $from->diff($to)->y;

                    ?>
                        <tr>
                            <td>{{$i++}}</td>
                             <td>{{$patient->id}}</td>
                            <td><a href="/lead/{{$patient->lead_id}}/viewPersonalDetails" target="_blank">{{$patient->lead->name or ""}}</a></td>
                            <td>{{$patient->lead->country or ""}}</td>
                            <td>{{$patient->nutritionist or ""}}</td>
                            <?php
                                 $initialBMI = null;
    
    
                                 $upgradeDuration = \App\Models\Fee::getUpgradeDuration();
                                $endFee          = $patient->fees->sortByDesc('end_date')->first();
                                $startFee        = $endFee; 
                                $fees            = $patient->fees->sortByDesc('end_date');

                                foreach ($fees as $f ) {
                                    $diffInDays = $f->end_date->diffInDays($startFee->start_date,false);
                                    if ( ($diffInDays <= $upgradeDuration))
                                        $startFee = $f;
                                    else
                                        break;
                                }
                                $initialWeight =  \App\Models\PatientWeight::where('patient_id',$patient->id)
                                 ->where('weight','>',0)
                                 ->where('date','>=',$startFee->start_date)
                                 ->orderBy('date')
                                 ->first();

                                 $init = null;
                                 if($initialWeight)
                                 {
                                    $init = $initialWeight->date;
                                 }
                                 else
                                 {
                                    $init = null;
                                 }

                                 if ( $patient->lead->height >0 ) {
                                    $initialBMI = $initialWeight ? number_format($initialWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;
                                 }
                            ?>
                              <td>{{$initialWeight ? $initialWeight->weight:''}}</td>
                               <td>{{$initialBMI ? $initialBMI :''}}</td>
                            <?php
                                $latestBMI  = null;
                                $latestWeight  =  \App\Models\PatientWeight::where('patient_id',$patient->id)
                                ->where('weight','>',0)
                                ->where('date','>=',$startFee->start_date)
                                ->orderBy('date','desc')
                                ->first();
                                 $fin = null;
                                 if($latestWeight)
                                 {
                                    $fin =  $latestWeight->date;
                                 }
                                 else
                                 {
                                    $fin = null;
                                 }
                                if ( $patient->lead->height >0 ) {
                                     $latestBMI = $latestWeight ? number_format($latestWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;
                                }  
                            ?>
                            <td>{{$latestWeight ? $latestWeight->weight:''}}</td>
                            <td>{{$latestBMI ? $latestBMI :''}}</td>

                            <?php 
                                $change = null;
                                if($initialWeight && $latestWeight )
                                {
                                    $change = $initialWeight->weight - $latestWeight->weight ; 
                                }

                            ?>
                            <?php 
                                $days = null;
                                if($init && $fin )
                                { 
                                   $days = strtotime($fin)- strtotime($init);

                                   $days = floor($days / (60 * 60 * 24));
                                   $days =  number_format($days/30 ,2);
                                    
                                }

                            ?>
                            <td>{{$change ? $change :''}}</td>
                            <td>{{$days ? $days : ' '}}</td>


                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div role="tabpanel" class="tab-pane" id="active">
                
                
                <table id="table-active" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patients Id</th>
                            <th>Patients Name</th>
                            <th>Country</th>
                            <th>Nutritionist</th>
                            <th>Initial weight</th>
                            <th>Initial BMI</th>
                            <th>Final Weight</th>
                            <th>Final BMI</th>
                            <th>Total Weighloss</th>
                            <th>Duration</th>

                            
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($activePatients AS $patient)
                    <?php
                        $from = new DateTime($patient->lead->dob);
                        $to = new DateTime('today');
                        $dob = $from->diff($to)->y;

                    ?>
                        <tr>
                            <td>{{$i++}}</td>
                             <td>{{$patient->id}}</td>
                            <td><a href="/lead/{{$patient->lead_id}}/viewPersonalDetails" target="_blank">{{$patient->lead->name or ""}}</a></td>
                            <td>{{$patient->lead->country or ""}}</td>
                            <td>{{$patient->nutritionist or ""}}</td>
                            <?php
                                 $initialBMI = null;
    
    
                                 $upgradeDuration = \App\Models\Fee::getUpgradeDuration();
                                $endFee          = $patient->fees->sortByDesc('end_date')->first();
                                $startFee        = $endFee; 
                                $fees            = $patient->fees->sortByDesc('end_date');

                                foreach ($fees as $f ) {
                                    $diffInDays = $f->end_date->diffInDays($startFee->start_date,false);
                                    if ( ($diffInDays <= $upgradeDuration))
                                        $startFee = $f;
                                    else
                                        break;
                                }
                                $initialWeight =  \App\Models\PatientWeight::where('patient_id',$patient->id)
                                 ->where('weight','>',0)
                                 ->where('date','>=',$startFee->start_date)
                                 ->orderBy('date')
                                 ->first();

                                 $init = null;
                                 if($initialWeight)
                                 {
                                    $init = $initialWeight->date;
                                 }
                                 else
                                 {
                                    $init = null;
                                 }

                                 if ( $patient->lead->height >0 ) {
                                    $initialBMI = $initialWeight ? number_format($initialWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;
                                 }
                            ?>
                              <td>{{$initialWeight ? $initialWeight->weight:''}}</td>
                               <td>{{$initialBMI ? $initialBMI :''}}</td>
                            <?php
                                $latestBMI  = null;
                                $latestWeight  =  \App\Models\PatientWeight::where('patient_id',$patient->id)
                                ->where('weight','>',0)
                                ->where('date','>=',$startFee->start_date)
                                ->orderBy('date','desc')
                                ->first();
                                 $fin = null;
                                 if($latestWeight)
                                 {
                                    $fin =  $latestWeight->date;
                                 }
                                 else
                                 {
                                    $fin = null;
                                 }
                                if ( $patient->lead->height >0 ) {
                                     $latestBMI = $latestWeight ? number_format($latestWeight->weight*100*100/pow($patient->lead->height,2) ,2):null ;
                                }  
                            ?>
                            <td>{{$latestWeight ? $latestWeight->weight:''}}</td>
                            <td>{{$latestBMI ? $latestBMI :''}}</td>

                            <?php 
                                $change = null;
                                if($initialWeight && $latestWeight )
                                {
                                    $change = $initialWeight->weight - $latestWeight->weight ; 
                                }

                            ?>
                            <?php 
                                $days = null;
                                if($init && $fin )
                                { 
                                   $days = strtotime($fin)- strtotime($init);

                                   $days = floor($days / (60 * 60 * 24));
                                   $days =  number_format($days/30 ,2);
                                    
                                }

                            ?>
                            <td>{{$change ? $change :''}}</td>
                            <td>{{$days ? $days : ' '}}</td>


                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>                
  </div>
</div>
<!-- 
<script type="text/javascript">
  $(document).ready(function(){
    $('#table-region').dataTable({

      "iDisplayLength": 100

    });
});
</script> -->

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
    $('#table-active').DataTable( {
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
    $('#table-age').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
             'csv'
        ]
    } );
} );
</script>