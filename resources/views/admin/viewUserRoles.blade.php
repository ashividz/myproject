<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			Roles
		</div>
		<div class="panel-body">
			<div class="container">
				<form id="form" method="post">
					<button type="button" id="add_user" onclick="location.href='/admin/addUserRole';" class="btn btn-success">Add</button>

					<button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
					<hr>
					<table class="table striped">
						<thead>
							<tr>
								<th>Role Name</th>
								<th>Display Name</th>
								<th>Description</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>

						@foreach ($roles as $role)
							<tr>
								<td>{{ $role->name }}</td>
								<td>{{ $role->display_name }}</td>
								<td>{{ $role->description }}</td>
								<td> <i class="{{ $role->deleted_at ? 'fa fa-remove red' : 'fa fa-check green' }}"></i></td>
							</tr>
						@endforeach

						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>