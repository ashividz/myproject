<div class="container1">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/daterange')
				</div>
				<h4>Lead Distribution</h4>
			</div>	
			<div class="panel-body">

				<form id="form" class="form-inline" action="/marketing/saveRejoin" method="post">
					<table id="leads" class="table table-bordered">
						<thead>
							<tr>
								<th>Date</th>
				
							@foreach($sources AS $source)

								<td title="{{$source->source}}">{{substr($source->source, 0, 4)}}</td>

							@endforeach

								<th>Total</th>

							</tr>
						</thead>
						<tbody>
<?php
	$channels = json_decode($channels);
?>

			@foreach($channels AS $channel)
<?php
	$total = 0;
?>

							<tr>
								<td>
									{{date('M j, Y', $channel->date)}}
								</td>
							@foreach($channel->counts AS $count)
<?php
	$total += $count;
?>
								<td align="center">
									{{$count}}
								</td>
							@endforeach
								<th>
									{{$total}}
								</th>
							</tr>
			@endforeach

						</tbody>
					</table>
			</div>			
	</div>
	
</div>