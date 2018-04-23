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
                </tr>
            </thead>
            <tbody>

            @foreach($patients AS $l)
                 <?php  
                        $now = time(); // or your date as well
                        $your_date = strtotime($l->patient->cfee->start_date);
                        $enddate = strtotime($l->patient->cfee->end_date);
                        $datediff = $now - $your_date;
                        $daydiff = $enddate - $now ;
                        $dayrem  =  floor($daydiff / (60 * 60 * 24));
                        $daycount  = floor($datediff / (60 * 60 * 24));
                        $totaldays = $enddate - $your_date;
                        $totaldaycount =  floor($totaldays / (60 * 60 * 24));
                        $x ;

                        if($totaldaycount >= 60 && $totaldaycount < 90)
                        {
                            $x = 20;
                        }
                        elseif($totaldaycount >= 90 && $totaldaycount < 180)
                        {
                            $x = 45;
                        }
                        elseif($totaldaycount >= 180 && $totaldaycount < 365)
                        {
                            $x = 60;
                        }
                        else
                        {
                            $x = 120;
                        }

                    ?>
                @if($daycount > 20 &&  $dayrem >= $x)
                    @if($daycount % 10 == 0)
                        <tr>
                            <td>{{$i++}}</td>
                            <td><a href="/cre/{{$l->patient->id}}/survey" target="_blank">{{$l->name or ' '}}</a></td>
                            <td>{{$l->cre_name}}</td>
                            <td>{{$l->patient->nutritionist}}</td>
                            <td>{{$l->patient->cfee->start_date}}</td>
                           
                            <td>{{ $daycount }}</td>
                        </tr>
                    @endif
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