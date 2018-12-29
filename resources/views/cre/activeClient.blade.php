<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-left">
			@include('cre/partials/index')
		</div>
	</div>
	<?php
	$i = 1;
	?>
	<div class="panel-body">
		<table id="table-age" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lead Name</th>
                    <th>Cre</th>
                    <th>Nutritionist</th>
                    <th>Start Date</th>
                    <th>Days</th>
                    <th>Duration</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>

            @foreach($patients AS $l)
                 <?php  
                        $now = time(); // or your date as well
                        $duration = $l->patient->cfee->duration;
                        $day = floor((strtotime(date('Y-m-d')) - strtotime($l->patient->cfee->start_date))/(60*60*24));
                        $day = $day < 0 ? 0 : $day;

                        $x ;

                        if($duration >= 30)
                        {
                            $x = 15;
                        }
                        if($duration >= 90)
                        {
                            $x = 45;
                        }
                        if($duration >= 180)
                        {
                            $x = 90;
                        }
                        if($duration >=365)
                        {
                            $x = 240;
                        }

                    ?>
                @if($day >= $x && $isupgrade==1) <!-- && $day%15==0) -->
                        <tr>
                            <td>{{$i++}}</td>
                            <td><a href="/cre/{{$l->patient->id}}/survey" target="_blank">{{$l->name or ' '}}</a></td>
                            <td>{{$l->cre_name}}</td>
                            <td>{{$l->patient->nutritionist}}</td>
                            <td>{{$l->patient->cfee->start_date}}</td>
                            <td>{{ $day }}</td>
                            <td>{{ $duration }}</td>
                            <td>
                                <ul>
                                    @foreach($l->patient->surveys as $survey)
                                        <li><a href="/patient/{{$l->patient->id}}/survey" target="_blank">
                                        {{$survey->score or ""}}
                                        </a><small><em><span class="pull-right">[{{date('M j, Y h:i A',strtotime($survey->created_at))}}]</span></em></small></li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                @elseif($isupgrade==0 && $day>=20 && $day<$x)<!-- && $day%15==0) -->
                       <tr>
                            <td>{{$i++}}</td>
                            <td><a href="/cre/{{$l->patient->id}}/survey" target="_blank">{{$l->name or ' '}}</a></td>
                            <td>{{$l->cre_name}}</td>
                            <td>{{$l->patient->nutritionist}}</td>
                            <td>{{$l->patient->cfee->start_date}}</td>
                            <td>{{ $day }}</td>
                            <td>{{ $duration }}</td>
                            <td><ul>
                                    @foreach($l->patient->surveys as $survey)
                                        <li><a href="/patient/{{$l->patient->id}}/survey" target="_blank">
                                        {{$survey->score or ""}}
                                        </a><small><em><span class="pull-right">[{{date('M j, Y h:i A',strtotime($survey->created_at))}}]</span></em></small></li>
                                    @endforeach
                            </ul></td>
                        </tr>         
                @endif
            @endforeach

            </tbody>
        </table>
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