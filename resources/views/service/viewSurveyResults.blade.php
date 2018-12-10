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
				                <th>Name</th>
						        <th>Nutritionist</th>
				                <th>Score</th>
				                <th>Created By</th>
				                <th>Source</th>
				                <th>Date</th>
				           	</tr>
				        </thead>
				        <tbody>

				@foreach($surveys AS $survey)
							<tr>
								<td>{{$i++}}</td>
								<td><a href="/patient/{{$survey->patient->id}}/survey" target="_blank">{{$survey->patient->lead->name or ""}}</a></td>
								<td>{{$survey->nutritionist}}</td>
								<td>{{$survey->score}}</td>
								<td>{{$survey->created_by}}</td>
								<td>{{$survey->source}}</td>
								<td>{{date('M j, Y h:i A',strtotime($survey->created_at))}}</td>
							</tr>
				@endforeach

						</tbody>
					</table>
          		</div>

        	<!-- Nutritionist Summary Report -->
        	<div role="tabpanel" class="tab-pane fade" id="summary">        
          		<div class="container">
          			<!--<a name="download" id="downloadSummary" class="btn btn-primary pull-right" style="margin:10px" download="summary.csv">Download Summary Csv</a>  -->
					<table id="table_summary" class="table table-striped table-bordered">
						<caption>Average Score: {{$average_csat}}</caption>
						<thead>
				            <tr>
						        <th >Nutritionist</th>
				                <th >Count</th>
				                <th >Total Score</th>
				                <th >Average</th>
				           </tr>
				        </thead>
				        <tbody>

				@foreach($summaries AS $summary)
							<tr>								
								<td>{{$summary->nutritionist}}</td>
								<td>{{$summary->total}}</td>
								<td>{{$summary->score}}</td>
								<td>{{$summary->total <> 0 ? round($summary->score/$summary->total, 2):''}}</td>
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