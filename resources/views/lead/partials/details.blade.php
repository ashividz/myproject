@extends('lead.index')

@section('top')
@if(!$lead->cres->isEmpty())
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2 class="panel-title">CRE</h2>
			</div>
			<div class="panel-body">
				<label>{{$lead->cre->cre or ""}}</label><br>
				<em class='pull-right'><small>since {{$lead->cre->created_at->format('jS M, Y') }}</small></em>
			</div>
		</div>
	</div>
@endif

@if($lead->patient)

	@if(!$lead->patient->primaryNtr->isEmpty())
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Nutritionist</h2>
				</div>
				<div class="panel-body">
					@foreach($lead->patient->primaryNtr as $nutritionist)
						<label>{{$nutritionist->nutritionist or ""}}</label><br>
                    <em class='pull-right'><small>since {{date('jS M, Y', strtotime($nutritionist->created_at))}}</small></em>
					@endforeach
				</div>
			</div>
		</div>
	@endif

	@if(!$lead->patient->doctors->isEmpty())
		<?php  $doctor = $lead->patient->doctors->sortByDesc('id')->first();?>
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Doctor</h2>
				</div>
				<div class="panel-body">
					<label>Dr. {{$doctor->name or ""}}</label><br>
                    <em class='pull-right'><small>since {{date('jS M, Y', strtotime($doctor->created_at))}}</small></em>
				</div>
			</div>
		</div>
	@endif	
@endif
@if(!$lead->sources->isEmpty())
		<div class="col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Source</h2>
				</div>
				<div class="panel-body">
				@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
					<label>{{$lead->source->master->source_name or ""}}</label>
				<?php 
					//show lead source if it is corporate,events or Times Jobs
				?>
				@elseif($lead->source->master && ($lead->source->master->channel->id==5 || $lead->source->master->channel->id ==6 || $lead->source->master->id ==57))
					<label>{{ $lead->source->master->channel->name or "" }}:{{$lead->source->master->source_name or ""}}</label>
				@else
					<label>{{ $lead->source->master->channel->name or "" }}</label>
				@endif
				<br>
                <small><em class='pull-right'>since {{$lead->source->created_at->format('jS M, Y')}}</em></small>
				</div>
			</div>
		</div>
	@endif			
@endsection

@section('main')
<script type="text/javascript" src="/js/modals/cre.js"></script>
<script type="text/javascript" src="/js/modals/source.js"></script>
<?php	
	$fee=null;
	if($lead->patient)
		$fee = $lead->patient->cfee ? $lead->patient->cfee : $lead->patient->fee;
	
	if(isset($fee)) {
		$remaining_days = 0;
		$remaining_days = date('Y/m/d') < date('Y/m/d', strtotime($fee->end_date)) ? "<strong>" . floor((strtotime($fee->end_date) - strtotime(date('Y/m/d')))/(60*60*24)) . "</strong> days remaining" : "Program has finished";

		$now = date('Y-m-d') > $fee->end_date ? $fee->end_date : date('Y-m-d');            
		$days = floor((strtotime(date($now)) - strtotime($fee->start_date))/(60*60*24));
		$totalDays = floor((strtotime($fee->end_date) - strtotime($fee->start_date))/(60*60*24));
		$progressPercentage = $totalDays > 0 ? floor((($days)/$totalDays)*100) : 0;
	}
		
?>

@if($fee)
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">Lead Details</h2>
		</div>
		<div class="panel-body">
			<table class="table">
				<tr>
					<td width="33%">
						{!! $remaining_days !!}
					</td>
					<td width="33%">
						{!! $lead->patient ? "<b>Nutritionist : </b>" . $lead->patient->nutritionist : "" !!}
					</td>
					<td>
						
					</td>
				</tr>

	<?php
		$days = floor((strtotime($fee->end_date) - strtotime($fee->start_date))/(60*60*24));

		$diet = 0; //Diet Count

	?>
				<tr>
					<td>
						<h6>{{ $fee->start_date ? date('l\, jS F\, Y', strtotime($fee->start_date)) : "" }}</h6>
					</td>
					<td>
						<h6 align="center">Total days : {{$days}}</h6>
					</td>
					<td>
						<h6 class="pull-right">{{ $fee->end_date ? date('l\, jS F\, Y', strtotime($fee->end_date)) : "" }}</h6>
					</td>
				</tr>

		<!-- Diet Progress Graph-->
				<tr>
					<td colspan="3" class="progress">
						<table class="table" width="auto">
							<tr>
						
						@for($i = 0; $i < $days; $i++)
	<?php
		$color = '#ca4e4e';
		$dt = date('Y-m-d', strtotime('+ '.$i.' days', strtotime($fee->start_date)));
		if($lead->patient->diets->where('date_assign', $dt)->first())
		{
			$color = '#addf58';
			$diet++;
		}
	?>						
							@if($dt < date('Y-m-d'))							
								<td title="{{date('jS M, Y', strtotime($dt))}}" style="background-color:{{$color}}"></td>
							@elseif($dt == date('Y-m-d'))
								<td title="Today : {{date('jS M, Y', strtotime($dt))}}" style="background-color:#4D8FC5"></td>
							@elseif($dt >= date('Y-m-d'))
								<td title="{{date('jS M, Y', strtotime($dt))}}" style="background-color:grey"></td>
							@endif
						@endfor		
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3">Diet sent : {{$diet}} days</td>				
				</tr>

				<tr>
					<td colspan="3">
						<div class="progress">
						  	<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{$days}}" aria-valuemin="0" aria-valuemax="{{$totalDays}}" style="width: {{$progressPercentage}}%">{!! $days . " days (". $progressPercentage . ")" !!} 
						 	</div>
						</div>
					</td>
				</tr>
		
			</table>
		</div>
	</div> <!-- End panel -->	
</div>
@endif


@if($lead->patient)
	<!-- Fees -->
	@if(!$lead->patient->fees->isEmpty())
		<div class="container">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Fee Details</h2>
				</div>
				<div class="panel-body">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th>Duration</th>
								<th>Fees Entry Date</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Amount</th>
								<th>CRE</th>
								<th>Source</th>
							</tr>
						</thead>
						<tbody>
					@foreach($lead->patient->fees as $fee)
						<?php
							$fee_changes  = '<table border=1 padding=2>';
							$fee_changes .= '<thead><tr><td>adjusted on</td><td>old start date</td><td>new start date</td><td>old end date</td><td>new end date</td><td>first diet after old start date</td></tr></thead><tbody>';
							foreach ($fee->logs->sortByDesc('created_at') as $log) {
								$fee_changes .= 
									'<tr><td>'
									.$log->created_at->toDayDateTimeString().'</td><td>'
									.Carbon::parse($log->old_value->start_date)->toFormattedDateString().'</td><td>'
									.Carbon::parse($log->new_value->start_date)->toFormattedDateString().'</td><td>'
									.Carbon::parse($log->old_value->end_date)->toFormattedDateString().'</td><td>'
									.Carbon::parse($log->new_value->end_date)->toFormattedDateString().'</td>';
									$first_diet= $lead->patient->diets->filter(function($item) use($log){
										return ($item->email==1) && (Carbon::parse($item->date_assign)->gte(Carbon::parse($log->old_value->start_date)));	
									})->sortBy('date_assign')->first();
									$fee_changes .= '<td>';
									$fee_changes .= $first_diet ? Carbon::parse($first_diet->date_assign)->toFormattedDateString() :'';
									$fee_changes .= '</td>';
									$fee_changes .= '</tr>';
							}
							$fee_changes .= '</tbody></table>';
						?>	

							<tr>
								<td>
							@if(trim($fee->duration) <> '')
								{{ $fee->duration > 1 ? $fee->duration . " days" : $fee->duration . " day" }}
							@else
								{{ $fee->valid_months > 1 ? $fee->valid_months . " months" : $fee->valid_months . " month" }}
							@endif
							</td>
								<td>{{ date('jS M, Y', strtotime($fee->entry_date)) }}</td>
								<td>
									{{ date('jS M, Y', strtotime($fee->start_date)) }}
									@if(!$fee->logs->isEmpty())
										<span data-toggle="popover" data-html="true" title="late start adjust" data-content="{{$fee_changes}}" data-placement="top"><i class="fa fa-info-circle"></i></span>
									@endif
								</td>
								<td>{{ date('jS M, Y', strtotime($fee->end_date)) }}</td>
								<td>{{ $fee->currency->symbol }} {{ $fee->total_amount }}</td>
								<td>{{ $fee->cre }}</td>
								<td>{{ $fee->source->source_name or "" }}</td>
							</tr>
					@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>	<!-- Fee Details End -->
	@endif
	<!-- Fees end -->
@endif

<div class="container">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2 class="panel-title">Lead CRE
				@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
					<span class="pull-right glyphicon glyphicon-plus" id="addCre" value="{{$lead->id}}"></span>
				@endif
				</h2>
			</div>
			<div class="panel-body">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>CRE</th>
							<th>Start Date</th>
						</tr>
					</thead>
					<tbody>
				@foreach($lead->cres as $cre)
						<tr>
							<td>{{ $cre->cre }}</td>
							<td>
								{{ date('jS M, Y', strtotime($cre->created_at)) }}
								@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
									<div class="pull-right">
										<a href="#" id="{{$cre->id}}" onclick="deleteCre(this.id)"><i class="glyphicon glyphicon-remove red"></i></a>
									</div>
								@endif
							</td>
						</tr>
				@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2 class="panel-title">Lead Source
				@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
					<span class="pull-right glyphicon glyphicon-plus" id="addSource" value="{{$lead->id}}"></span>
				@endif
				</h2>
			</div>
			<div class="panel-body">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>Source</th>
							<th>Sourced By</th>
							<th>Remarks</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
				@foreach($lead->sources as $source)
						<tr>					
							<td>
						@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
								{{ $source->master->source_name or ""}}
								
								<div class="pull-right">
									<a href="#" id="{{$source->id}}" onclick="deleteSource(this.id)"><i class="fa fa-close red"></i></a>
								</div>

						@else
								{{ $source->master->channel->name or "" }}
						@endif
								{!! $source->referrer_id ? " (<a href='/lead/" . $source->referrer_id . "/viewReferences' target='_blank'>" . $source->referrer->name . "</a>)" : "" !!}
							</td>
							<td>
								{!! $source->sourced_by !!}
							</td>

							<td>{{$source->remarks or ""}}</td>
							<td>{{ $source->created_at ? date('jS M, Y', strtotime($source->created_at)) : ""}}
							</td>

							
						</tr>
				@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
@endsection

@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
<script type="text/javascript">
	function deleteSource(id) {

	    var r=confirm("Are you sure you want to delete?");
    	if (r==true){
            var url = "/lead/deleteSource"; //
	        $.ajax(
	        {
	           type: "POST",
	           url: url,
	           data: {id : id}, // send Source Id.
	           success: function(data)
	           {
	               $('#alert').show();
	               $('#alert').empty().append(data);
	               setTimeout(function()
	                {
	                    $('#alert').slideUp('slow').fadeOut(function() 
	                    {
	                        location.reload();
	                     });
	                }, 3000);
	           }
	        });
        };
	};

	function deleteCre(id) {

	    var r=confirm("Are you sure you want to delete?");
    	if (r==true){
            var url = "/lead/deleteCre"; //
	        $.ajax(
	        {
	           type: "POST",
	           url: url,
	           data: {id : id}, // send Source Id.
	           success: function(data)
	           {
	               $('#alert').show();
	               $('#alert').empty().append(data);
	               setTimeout(function()
	                {
	                    $('#alert').slideUp('slow').fadeOut(function() 
	                    {
	                        location.reload();
	                     });
	                }, 3000);
	           }
	        });
        };
	};
</script>
@endif
<style type="text/css">
	.progress td {
		padding: 8px 1px !important;
	}
</style>