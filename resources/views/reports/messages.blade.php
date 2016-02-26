
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="panel-title">Outbox </span>
		</div>	
		<div class="panel-body">
			<table class="table table-striped" id="messages">
				<thead>
					<tr>
						<th width="15%">From</th>
						<th width="15%">To</th>
						<th width="15%">Subject</th>
						<th>Body</th>
						<th width="10%">Date</th>
					</tr>
				</thead>
				<tbody>

			@foreach($messages AS $message)
	<?php
		$dt = date('Y-m-d H:i:s', strtotime($message->created_at));

		$date = $dt > date('Y-m-d') ? date('h:i A', strtotime($dt)) : date('d M Y, h:i A', strtotime($dt));

	?>
					<tr>
						<td>{{$message->from}}</td>
						<td>
						@foreach($message->recipients AS $recipient)<i class="fa fa-dot-circle-o"></i> {{$recipient->name}}
								@if($recipient->read_at)
									<i class="green fa fa-check" title="{{date('jS M Y, h:i A', strtotime($recipient->read_at))}}"></i>
								@endif
								<p>
						@endforeach
						</td>
						<td>{{$message->subject}}</td>
						<td>{{$message->body}}</td>
						<td><div class="pull-right">{{$date}}</div></td>
					</tr>
					
			@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>