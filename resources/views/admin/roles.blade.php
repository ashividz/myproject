<table class="table table-bordered">

	@foreach ($roles as $role)
	<tr>
		<td>{{ $role->name }}</td>
		<td>{{ $role->display_name }}</td>
		<td>{{ $role->description }}</td>
	</tr>
	@endforeach
	
</table>