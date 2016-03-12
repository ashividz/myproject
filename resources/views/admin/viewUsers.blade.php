<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			Users
		</div>
		<div class="panel-body">
			<div class="container">
				<form>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>User Name</th>
								<th>Email</th>
								<th>Roles</th>
							</tr>	
						</thead>
						<tbody>
					@foreach ($employees as $employee)
							<tr>	
								<td><a href="/admin/user/{{$employee->id}}">{{ $employee->name }}</a></td>
								<td>{{ $employee->user->username or "" }}</td>
								<td>{{ $employee->user->email or ""}}</td>
								<td>
									@if($employee->user)
										<a href="/admin/user/{{ $employee->user->id }}/viewRole">Edit</a> 
									<div class="pull-right">
										@foreach($employee->user->roles AS $role)
											{{$role->display_name}}
										@endforeach
									</div>
									@endif
								</td>
							</tr>
					@endforeach
						</tbody>
					</table>
				</form>			
			</div>			
		</div>
	</div>
</div>