@extends('master')

@section('content')

<script type="text/javascript" src="/js/modals/workflow.js"></script>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading" style="text-align:center">
			<div class="pull-left">
				@include('partials/daterange')
			</div>
			<h4>{{ $workflow->name }}</h4>
		</div>
		<div class="panel-body">
			
			<table class="table" id="workflow-table">
				<thead>
					<tr>
						<th>Task</th>
						<th>Name</th>	
					@foreach($steps AS $step)
						<th style="text-align:center">{{$step->name}}</th>
					@endforeach
					</tr>
				</thead>
				<tbody>

			@foreach($tasks AS $task)
					<tr>
						<td>
							<strong>{{ $workflow->name }}</strong>
							<p>
								<a href="/lead/{{$task->lead->id}}/viewDetails" target="_blank">{{ $task->lead->name }}</a>
			@if($task->registration)
								<a role="button" data-toggle="popover" href="#" id="task{{$task->id}}" class="pull-right">
								<i class="fa fa-info-circle fa-2x"></i>
								</a>
							</p>

							<script type="text/javascript">
								$('#task{{$task->id}}').webuiPopover({
									title:'Registration details', 
									trigger:'hover',
									content:'<b>Duration :</b> {!!$task->registration->duration!!} month{{$task->registration->duration > 1 ? "s" : ""}}<p><b>Amount :</b> ₹ {!!money_format($task->registration->amount, 2)!!}<p><b>Discount : </b>{!!$task->registration->discount!!} %<p><b>Payment Mode : </b>{!!$task->registration->mode->name!!}<p><b>Remark : </b>{!!$task->registration->remark!!}<p>'
								});
							</script>
			@endif
						</td>
						<td>
							<strong>{{ $task->user->employee->name or "" }}</strong>
							<p>
								<small>
									<em>{{ date('jS M, Y h:i A', strtotime($task->created_at)) }}</em>
								</small>
							</p>
						</td>
					<?php $i = 0; ?> 
					@foreach($task->steps AS $step)
						<?php
								$i++;
								$css = "";
								$content ="<p><b>Name</b> : " . $step->user->employee->name;
								$content .= "<p><b>Status</b> : " . $step->state->name;
								$content .="<p><b>Created At</b> : ";
								$content .= date('d M Y, h:i A', strtotime($step->created_at)); 
								$content .="<p><b>Updated At</b> : ";
								$content .= date('d M Y, h:i A', strtotime($step->updated_at));
								$content .= "<p><b>Remark</b> : ";
								$content .= $step->remark;

								if(null != Auth::user()->isApprover($step->step_id) && $step->state_id <> 3)
								{
									$css = "action";
								}
						?>

						<td style="padding:0; vertical-align:middle; text-align:center">
							<div class="base">

								<a role="button" class="{{$css}}" data-toggle="popover" href="#" id="{{$step->id}}">
									<span class="step {{ strtolower($step->state->name) }}"></span>
								</a>

							
								<script type="text/javascript">
									$('#{{$step->id}}').webuiPopover({
										title:'{{$step->step->name}}', 
										trigger:'hover',
										content:'{!!$content!!}'
									});
								</script>
							
							</div>
						</td>
					@endforeach

					@for($j = $i; $j < $count; $j++)
						<td style="padding:0; vertical-align:middle; text-align:center">
							<div class="base">
								<span class="step progress"></span>
							</div>
						</td>
					@endfor

					</tr>				
			@endforeach

				</tbody>
				
			</table>
			<input type="hidden" id="workflow" value="{{ $workflow->id }}"></div>
		</div>
	</div>
</div>

@endsection