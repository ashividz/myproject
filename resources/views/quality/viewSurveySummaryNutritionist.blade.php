<div class="container">	
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-left">
				@include('/partials/daterange') 
			</div>
			<h4>Nutritionist</h4> 
		</div>
		<div class="panel-body">
			<table id="example" class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th width="5%">#</th>
				        <th width="10%">Nutritionist</th>
		                <th width="10%">Count</th>
		                <th width="15%">Total Score</th>
		                <th width="15%">Average</th>
		           </tr>
		        </thead>
		        <tbody>

		@foreach($surveys AS $survey)
					<tr>
						<td>{{$i++}}</td>
						<td>{{$survey->nutritionist}}</td>
						<td>{{$survey->total}}</td>
						<td>{{$survey->score}}</td>
						<td>{{$survey->total <> 0 ? round($survey->score/$survey->total, 2):''}}</td>
					</tr>

		@endforeach

		        </tbody>
		    </table>
		</div>
	</div>
</div>