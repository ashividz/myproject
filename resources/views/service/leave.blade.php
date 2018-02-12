<div class="panel panel-default">
<div class="panel-heading">
	<h3 class="panel-title">Leave </h3>
</div>
	<div class="panel-body">
			<table class="table table-bordered ">
				<thead>
					<tr>
						<td >#</td>
						<td >User id</td>
						<td >Name</td>
						<td >Verify</td>
					</tr>
				</thead>
				<tbody>
					@foreach($users	 as $user)
						<tr>
							<td>{{$i++}}</td>
							<td>{{$user->id}}</td>
							<td>{{$user->name}}</td>
							<form id="form-diet" action="/service/{{$user->id}}/leave" method="post" class="form-inline">
								{{ csrf_field() }}	
								<td><button class="btn btn-primary">Approve</button></td>
							</form>
						</tr>
					@endforeach
				</tbody>
			</table>
	</div>

</div>