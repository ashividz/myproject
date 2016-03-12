<table class="table table-bordered">
	<tr>
		<th>Name</th>
		<th>Emp No</th>
		<th>Email</th>
		<th>Mobile</th>
		<th>Dob</th>
	</tr>

@foreach ($employees as $employee)
	
	<tr>
		<td><a href="/hr/employee/{{$employee->id}}/edit">{{ $employee->name }}</a></td>
		<td>{{ $employee->emp_no }}</td>
		<td>{{ $employee->email }}</td>
		<td>{{ $employee->mobile }}</td>
		<td>{{ $employee->dob }}</td>
	</tr>
	
@endforeach
</table>