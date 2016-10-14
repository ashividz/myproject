@extends('lead.index')
@section('main')
<!-- Emails Sent Details -->
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			
		</div>
		<div class="panel-body">		
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th width="10%">Sender</th>
						<th width="65%">Email</th>
						<th>SMS</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>

			@foreach($emails AS $email)
					<tr>
						<td>{{$i++}}</td>
						<td>
							{$email->user ? $email->user->employee->name : 'cron'}}
						</td>
						<td>
							<pre>{!!$email->email!!}</pre>
						</td>
						<td>
							{!!$email->sms_response!!}
						</td>
						<td>
							{{date('jS M, Y h:i A', strtotime($email->created_at))}}
						</td>						
					</tr>
			@endforeach

				</tbody>
				
			</table>
		</div>
	</div>
</div>
@endsection
@section('top')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">			
		</div>
		<div class="panel-body">
		 @if(!$lead->dnc)
			<form action="" method="POST" class="form-inline">
				<div class="form-group">
					<select name="template_id">
						<option>Select Template</option>

					@foreach($templates AS $template)
						<option value="{{$template->id}}">{{$template->subject}}</option>
					@endforeach

					</select>
					<button class="btn btn-primary">Send</button>
				</div>

				<div class="container">

				@foreach($templates AS $template)
					<div id="{{$template->id}}" class="template" style="display:none;">
						<div style="border:1px solid #aaa;padding:5px">
							<div>
								{!!str_replace('$customer', $lead->name, Helper::nl2p($template->email))!!}
							</div>
							<ul>
							@foreach($template->attachments AS $attachment)
								<li><img class="attachment" src="/images/cleardot.gif"> {{$attachment->name}}</li>
							@endforeach
							</ul>
						</div>
						<hr>
						<div style="border:1px solid #aaa;padding:5px;background-color:#f9f9f9" title="SMS Content">
							{{$template->sms}}
						</div>
					</div>
				@endforeach

				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
		@else
			<div class="blacklisted"></div>
		@endif
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("select").change(function(){
			$('.template').hide();
			$('#' + this.value).show();
		});

	});
</script>
@endsection