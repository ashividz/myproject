<div class="panel panel-default">
<div class="panel-heading">
	<h3 class="panel-title">Diets Sent</h3>
</div>
	<div class="panel-body">
			<table class="table table-bordered blocked">
				<thead>
					<tr>
						<td>#</td>
						<td>patient Id</td>
						<td>Prakriti</td>
						<td>Blood group</td>
						<td>BreakFast</td>
						<td>Midmorning</td>
						<td>Lunch</td>
						<td>Evening</td>
						<td>Dinner</td>
						<td>nutritionist</td>
						<td>Verify</td>
					</tr>
				</thead>
				<tbody>
					@foreach($diets as $diet)
						<tr>
							<td>{{$i++}}</td>
							<td>{{$diet->patient_id}}</td>
							<td>{{$diet->prakriti}}</td>
							<td>{{$diet->blood_group }} {{$diet->rh_factor}}</td>
							<td>{{$diet->breakfast}}</td>
							<td>{{$diet->mid_morning}}</td>
							<td>{{$diet->lunch}}</td>
							<td>{{$diet->evening}}</td>
							<td>{{$diet->dinner}}</td>
							<td>{{$diet->nutritionist}}</td>
							<form id="form-diet" action="/service/{{$diet->id}}/approved" method="post" class="form-inline">
								{{ csrf_field() }}	
								<td><button class="btn btn-primary">Approve</button></td>
							</form>
						</tr>
					@endforeach
				</tbody>
			</table>
	</div>
</div>