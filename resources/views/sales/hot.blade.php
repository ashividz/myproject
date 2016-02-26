<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			@include('../partials/daterange')
		</div>
		<div class="panel-body">
			<table class="table table-bordered">
				<tr>
					<th>#</th>
					<th>Date</th>
					<th>CRE</th>
					<th>Name</th>
					<th>Remarks</th>
					<th width="10%">Status</th>
				</tr>
			<?php $i = 0; ?>
			@foreach($leads as $lead)
			<?php 

				$i++;
				
					$hot = "<i class='fa fa-fire' title='HOT' style='color:red'></i>";

					$converted = "<i class='fa fa-check' title='CONVERTED' style='color:green'></i>";
					

			?>
				<tr>
					<td>{{ $i }}</td>
					<td>{{ date('M j, Y h:i A',strtotime($lead->created_at)) }}</td>
					<td>{{ $lead->cre }}</td>
					<td><a href='{{ $url }}{{ $lead->id }}/viewDetails' target="_blank">{{ $lead->name }}</a></td>
					<td>{{ $lead->remarks }}</td>
					<td style='text-align:center'>
						@if(isset($lead->patient->fees))
							@foreach($lead->patient->fees AS $fee)
								@if($fee->entry_date >= $lead->created_at)
									<i class='fa fa-check fa-2x' title='CONVERTED : {{date('M j, Y',strtotime($fee->entry_date))}}' style='color:green'></i>
								@else
									<i class='fa fa-certificate' title='Past Payment : {{date('M j, Y',strtotime($fee->entry_date))}}' style='color:blue'></i>
								@endif	
							@endforeach
						@else
							<i class='fa fa-fire fa-2x' title='HOT' style='color:red'></i>
						@endif
					</td>
				</tr>
			@endforeach
				
			</table>
		</div>
	</div>
</div>


