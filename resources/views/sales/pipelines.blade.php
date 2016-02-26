<div class="container">
	<div class="panel">
		<div class="panel-heading">
			<div class="pull-left">
				@include('partials.daterange')
			</div>	
		</div>
		<div class="panel-body">
			<div class="container">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Name</th>

						@foreach($statuses AS $status)
							<th width="10%">{{$status->name}}</th>
						@endforeach

							<th width="12%">Total</th>

						</tr>
					</thead>
					<tbody>

			@foreach (json_decode($pipelines) as $pipeline) 
						<tr>
							<td style="background-color:#f2f2f2; text-align:right; padding-right:20px;">
								<strong>{{$pipeline->name}}</strong>
							</td>

				@foreach($statuses AS $status)
					<?php $i = 0 ;?>				
					@foreach($pipeline->counts AS $count)
						@if($count->status_id == $status->id)
							<td>
								{{$count->cnt}}
								<small class="pull-right">
									<em>({{round($count->cnt/$pipeline->total*100, 2)}} %)</em>
								</small>
							</td>							
						<?php  $i=1 ; continue;?>
						@endif
					@endforeach

				<!-- Draw td if no status count -->
					@if($i==0)
						<td></td>
					@endif

				@endforeach
							<th>
								{{$pipeline->total}}
							</th>
						</tr>
			@endforeach

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	table th, td  {
		text-align: center
	}
	small {
		font-size: 70%;
		color: #777;
	}
</style>
		