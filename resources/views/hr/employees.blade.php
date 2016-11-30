<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			<table class="table table-bordered">
				<tr>
					<th>Name</th>
					<th>Emp No</th>
					<th>Email</th>
					<th>Mobile</th>
					<th>Dob</th>
					<th>Supervisor</th>
				</tr>

			@foreach ($employees as $employee)
				
				<tr class="{{ $employee->deleted_at? 'deleted' : ''}}">
					<td><a href="/hr/employee/{{$employee->id}}/edit">{{ $employee->name }}</a></td>
					<td>{{ $employee->emp_no }}</td>
					<td>{{ $employee->email }}</td>
					<td>{{ $employee->mobile }}</td>
					<td>{{ $employee->dob }}</td>
					<td>
						{{ $employee->supervisor->employee->name or "" }}
						<a href="/employee/{{$employee->id}}/supervisor" target="_blank"><div class="pull-right"><i class="fa fa-edit"></i></div>
					</td>
				</tr>
				
			@endforeach
			</table>
		</div>
	</div>
</div>
<style type="text/css">
    .deleted {
        color: red;
    }
</style>