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
                    <th>Last Disposition date</th>
                    <th style="width:40%;">Remark</th>
                </tr>
            </thead>
            <tbody>

            @foreach($leads AS $l)
                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="/lead/{{$l->id}}/viewDispositions" target="_blank">{{$l->name or ' '}}</a></td>
                    <td>{{$l->disposition->created_at}}</td>
                    <td>{{$l->disposition->remarks}}</td>
                  
                </tr>
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