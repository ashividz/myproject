<?php 
	$channels = json_decode($channels);
	$leads = 0;
	$patients = 0; 
	$amount =0;
;?>
<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
		    		@include('cre.partials.index')
				</div>
				<h4>Performance</h4>
			</div>	
			<div class="panel-body">
				<table id="leads" class="table table-bordered blocked">
					<thead>
						<tr>
							
					@foreach($channels AS $channel)
							<th title="{{$channel->source_name}}" colspan="4" style="text-align:center">
								{{substr($channel->source, 0, 15)}}
							</th>
					@endforeach
							<th colspan="4">Total</th>
						</tr>
					</thead>
					<tbody>

						<tr>
					@foreach($channels AS $channel)
<?php 
	$leads += $channel->leads ;
	$patients += $channel->patients;
	$amount += $channel->amount;
?>
							<td>
								{{$channel->leads}}
							</td>
							<td>
								<b>{{$channel->patients}}</b>
							</td>
							<td style="min-width:60px">
								{{$channel->leads > 0 ? number_format($channel->patients/$channel->leads*100, 2) : "0"}} %
							</td>
							<td style="background-color:#ddd; min-width:80px">
								₹ {{number_format($channel->amount, 2)}}
							</td>
					@endforeach
							<td>{{$leads}}</td>
							<td>
								<b>{{$patients}}</b>
							</td>
							<td style="min-width:60px">
								{{$leads > 0 ? number_format($patients/$leads*100, 2) : "0"}} %
							</td>
							<td style="background-color:#ddd; min-width:100px">
								<b>₹ {{number_format($amount)}}</b>
							</td>
						</tr>

					</tbody>
				</table>
			</div>			
	</div>