@extends('message.index')
@section('main')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="panel-title">Outbox </span>
		</div>	
		<div class="panel-body">
			<table class="table outbox" id="messages">
				<tbody>

			@foreach($messages AS $message)
	<?php
		$dt = date('Y-m-d H:i:s', strtotime($message->created_at));

		$date = $dt > date('Y-m-d') ? date('h:i A', strtotime($dt)) : date('d M Y, h:i A', strtotime($dt));

	?>
					<tr>
						<td width="20%">
							<ul>
						@foreach($message->recipients AS $recipient)
								<li><i class="fa fa-dot-circle-o"></i> {{$recipient->name}}
								@if($recipient->read_at)
									<i class="green fa fa-check" title="{{date('jS M Y, h:i A', strtotime($recipient->read_at))}}"></i>
								@endif
								</li>
						@endforeach								
							</ul>
						</td>
						<td>{{$message->subject}}</td>
						<td width="45%">{{$message->body}}</td>
						<td><div class="pull-right">{{$date}}</div></td>
					</tr>
					
			@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection