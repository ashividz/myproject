@if(!$leads->isEmpty())
	<table>
		<tr>
			<th>Transaction ID</th>
			<th>Lead ID</th>
			<th>Created</th>
			<th>Name</th>
			<th>Disposition</th>
			<th>Dialer</th>
		</tr>
		@foreach($leads as $lead)
		<tr>
			<td>{{ $lead->query1->query_id or "" }}</td>
			<td>{{ $lead->id }}</td>
			<td>{{ date('Y-m-d', strtotime($lead->created_at)) }}</td>
			<td>{{ $lead->name }}</td>
			<td>{!! $lead->dispositiontxt !!}</td>
			<td>{!! $lead->ddisposition !!}</td>	
		</tr>
		@endforeach
	</table>
@endif
	