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
          			<table id="table" class="table">
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

<script type="text/javascript">
$(document).ready(function()
{
  $('#table').dataTable({
    "bPaginate": false,
    "aaSorting": [[ 4, "desc" ]]
  });

  $('#table_summary').dataTable({
    "bPaginate": false,
    "aaSorting": [[ 4, "desc" ]]
  });
  
});
</script>