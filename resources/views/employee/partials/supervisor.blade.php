@extends('employee.index')
@section('main')
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">Add Supervisor</div>
	</div>
	<div class="panel-body">
		<form id="form" action="/employee/{{$employee->id}}/supervisor/add" method="POST" class="form-inline">
			<div class="form-group">
				<select name="supervisor">
					<option value="">Select Supervisor</option>

				@foreach($employees as $emp)
					<option value="{{$emp->id}}">{{$emp->name}}</option>
				@endforeach

				</select>
			</div>

			<div class="form-group">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</form>
	</div>
</div>

<iv class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">Supervisors</div>
	</div>
	<div class="panel-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				
		@foreach($employee->supervisors as $supervisor)
				<tr>
					<td>{{$i++}}</td>
					<td>{{$supervisor->employee->name or ""}}</td>
					<td>{{$supervisor->created_at}}</td>
				</tr>
		@endforeach

			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript" src="/js/form-ajax.js"></script>
@endsection