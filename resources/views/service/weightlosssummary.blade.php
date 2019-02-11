<div class="container">	
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-left">
				@include('/partials/daterange') 
			</div>
			<h4>Survey Results</h4> 
		</div>
		<div class="panel-body">
    		<!-- Nav tabs -->
      		<ul class="nav nav-tabs" role="tablist">
        		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
        		<li role="presentation"><a href="#summary" aria-controls="summary" role="tab" data-toggle="tab">Nutritionist Summary</a></li>
      		</ul>

      		<!-- Tab panes -->
     		<div class="tab-content">
        		<div role="tabpanel" class="tab-pane active" id="home">
          			<!--<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Results Csv</a> -->
          			<table id="table" class="table table-bordered">
				        <thead>
				            <tr>
				                <th>#</th>
				                <th>Nutritionist Name</th>
						        <th>No of clients</th>
						        <th>wt loss > 2 kg</th>
				                <th>% wt loss > 2 kg</th>
				                <th>wt loss 1-2 kg</th>
				                <th>%wt loss 1-2 kg</th>
				                <th>wt loss 0-1 kg</th>
				                <th> %wt loss 0-1 kg</th>
				                <th>wt loss < 1 kg</th>
				                <th>% wt loss < 1 kg</th>
				           	</tr>
				        </thead>
				        <tbody>
				 @foreach($users as $key=>$user)
				 	@if($user->patients > 0)
							<tr>
								<td>{{$key++}}</td>
								<td>{{$user->name}}</a></td>
								<td>{{$user->patients}}</td>
								<td>{{$user->g2kg}}</td>
								<td>{{round(($user->g2kg*100)/$user->patients , 2)}}</td>
								<td>{{$user->g1kg}}</td>
								<td>{{round(($user->g1kg*100)/$user->patients , 2)}}</td>
								<td>{{$user->g0kg}}</td>
								<td>{{round(($user->g0kg*100)/$user->patients , 2)}}</td>
								<td>{{$user->l0kg}}</td>
								<td>{{round(($user->l0kg*100)/$user->patients , 2)}}</td>
							</tr>
					@endif
				@endforeach

						</tbody>
					</table>
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
    $('#table').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    } );
	$('#table_summary').DataTable({
		"iDisplayLength": 100,
		dom: 'Bfrtip',
		buttons: ['csv']
	});
} );
</script>  -->
<style>
caption {
    text-align: center;
    margin-bottom: 5px;
    padding: 5px;
	font-size: 160%;	
    font-weight: bold;
}
</style>