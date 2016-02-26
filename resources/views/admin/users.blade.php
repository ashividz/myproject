{{ isset($message) ? $message : "" }}
<table class="table">
	<tr>
		<th>Name</th>
		<th>User Name</th>
		<th>Email</th>
	</tr>	

@foreach ($users as $user)
	<tr>	
		<td><a href="/admin/user/{{ $user->username ? '' : 'add/'}}{{$user->id}}">{{ $user->name }}</a></td>
		<td>{{ $user->username }}</td>
		<td>{{ $user->email }}</td>
	</tr>
@endforeach
	
</table>