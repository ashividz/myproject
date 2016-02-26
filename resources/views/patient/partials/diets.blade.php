@extends('patient.index')

@section('main')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Diets</h3>
	</div>
	<div class="panel-body">
		<table class="table table-bordered blocked">
			<thead>
				<tr>
					<th>#</th>					
					<th width="10%">Date</th>
					<th>Breakfast</th><!--
					<td>Mid Morning</td>-->
					<th>Lunch</th>
					<th>Evening</th>
					<th>Dinner</th>
					<th>Herbs (&#8478;)</th>
					<th>Remarks/Deviations</th>
					<th>Weight</th>
				</tr>
			</thead>
			<tbody>
				
		@foreach($diets as $diet)
				<tr>
					<td>{{$i++}}</td>
					<td>{{date('jS M, Y', strtotime($diet->date_assign))}}</td>
					<td>
						<div class="breakfast">{{$diet->breakfast}}</div></i>
					</td><!--
					<td>
						<div class="mid_morning">{{$diet->mid_morning or ""}}</div>
						<i class="fa fa-copy pull-right blue" title="mid_morning"></i>
					</td>-->
					<td>
						<div class="lunch">{{$diet->lunch}}</div></i>
					</td>
					<td>
						<div class="evening">{{$diet->evening}}</div></i>
					</td>
					<td>
						<div class="dinner">{{$diet->dinner}}</div></i>
					</td>
					<td>
						<div class="herbs">{{$diet->herbs}}</div></i>
					</td>
					<td>
						<div class="remark">{{$diet->rem_dev}}</div></i>
					</td>
					<td>{{$diet->weight}}</td>
				</tr>
		@endforeach

			</tbody>
		</table>
	</div>
</div>
@endsection