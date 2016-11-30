<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('sales'))
				@include('../partials/users')
			@endif
		</div>
		<div class="panel-body">
			@if(!$patients->isEmpty())
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Source</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th width="5%">Days Remaining</th>
						<th>Nutritionist</th>
						<th width="30%">Last Disposition</th>
					</tr>
				</thead>
				<tbody>
					
			@foreach($patients AS $patient)

					<tr>
						<td>
							<a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank">{{$patient->lead->name or ""}}</a>
						</td>
						<td>
							{{$patient->lead->source->master->source or ""}}
							<small class="pull-right">
								<em>{{$patient->lead->source->sourced_by or ""}}</em>
							</small>
						</td>
						<td>
							{{date('jS M, Y', strtotime($patient->fee->start_date))}}
						</td>
						<td>
							{{date('jS M, Y', strtotime($patient->fee->end_date))}}
						</td>
						<td style="text-align:center">
							{{$patient->days or ""}}
						</td>
						<td>
							{{$patient->nutritionist or ""}}
						</td>
						<td>
							@if(isset($patient->lead->disposition))
								<b>{{$patient->lead->disposition->master->disposition or ""}}</b> : 
								{{$patient->lead->disposition->remarks or ""}}
								<small class="pull-right">
									<em>
										[{{$patient->lead->disposition->created_at or ""}}]
									</em>
								</small>
							@endif
						</td>
					</tr>


			@endforeach

				</tbody>
			</table>
			@endif
		</div>
	</div>
</div>