<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading"></div>
		<div class="panel-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Entry Date</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Amount</th>
						<th>Source</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>

			@foreach($fees AS $fee)
					<tr>
						<td>{{$i++}}</td>
						<td>{{$fee->patient->lead->name or ""}}</td>
						<td>{{$fee->entry_date}}</td>
						<td>{{$fee->start_date}}</td>
						<td>{{$fee->end_date}}</td>
						<td>{{$fee->total_amount}}</td>
						<td>{{$fee->source->source_name}}</td>
					</tr>

			@endforeach		

				</tbody>
			</table>
		</div>
	</div>
</div>