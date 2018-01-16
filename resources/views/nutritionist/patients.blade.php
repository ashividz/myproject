<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right">
			@include('nutritionist/partials/users')
		</div>
		<h4>My Patients</h4>
	</div>
	<div class="panel-body">
	<!-- Nav tabs -->
      	<ul class="nav nav-tabs" role="tablist">
        	<li role="presentation" class="active"><a href="#appointment" aria-controls="appointment" role="tab" data-toggle="tab">Appointments</a></li>
        	<li role="presentation"><a href="#primary" aria-controls="primary" role="tab" data-toggle="tab">Primary</a></li>
        	<li role="presentation"><a href="#secondary" aria-controls="summary" role="tab" data-toggle="tab">Secondary</a></li>
      	</ul>

      	<!-- Tab panes -->
      	<div class="tab-content">
    	<!-- Appointments -->
        	<div role="tabpanel" class="tab-pane active" id="appointment">
    @if(!$patients->isEmpty())    	
          	<a name="download" id="app" class="btn btn-primary pull-right" style="margin:10px"><i class="fa fa-download fa-2x" title="Download Appointments as CSV"></i></a>
				<table id="appointment_table" class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th width="13%">Name</th>
							<th width="10%">Upcoming Birthday</th>
							<th>Tags</th>										
							<th>Doctor</th>
							<th>Herbs (&#8478;)</th>
							<th>Call Time</th>
							<th>Remark/Note</th>
							<th>Last Diet</th>

					@for($j=0; $j<$days; $j++)
							<th>{{date('j-M', strtotime('+ '.$j.' days', strtotime($start_date)))}}</th>
					@endfor

						</tr>
					</thead>
					<tbody>

@foreach($patients as $patient)	

<?php 
	$carbon = new Carbon();
	$date = $patient->advance_diet ? $carbon->today()->addDays($patient->advance_diet) : $carbon->today();
	$date = $date->addDay(1)->format('Y-m-d');
	//$diet = $patient->diets->where('date_assign', $date)->where('email', '1')->first();
	$diet = $patient->diets->filter(function ($item) use ($date) {
    	return ( ($item->date_assign == $date) && (($item->email == 1) || ($item->sms_response == 200)) );
	})->first();	
	$fee  = $patient->cfee ? $patient->cfee : $patient->fee;
?>

	@if(!$diet)				
<?php
	$herbs = "";
	$tags = ""	;	
	$notes = "";
	foreach($patient->herbs AS $herb)
	{
		$herbs .= "<p>".$herb->herb->name." : ".$herb->quantity." ";
        $herbs .= $herb->unit ? $herb->unit->name : '';
        $herbs .= " ".$herb->remark." - <small><em>[".date('jS M, Y', strtotime($herb->created_at))."]</em></small><p>";
	}
	foreach($patient->tags as $tag) {
		$tags .= $tag->name . "<p>";
	}
	foreach($patient->notes as $note) {
		$notes .= $note->text . " : <b>". $note->created_by."</b><em> (".$note->created_at.")</em><p>";
	}
	
?>	
			@if($patient->lead->source_id == 99)
				<?php 
							$now = time(); // or your date as well
							$your_date = strtotime($patient->fee->start_date);
							$datediff = $now - $your_date;

							$days_count = floor($datediff / (60 * 60 * 24));
				?>
				@if($days_count%30 == 0)



					<tr style="background-color: #f4ad42" >
								<td>{{$x++}}</td>
								<td><a href="/patient/{{$patient->id}}/diet" target="_blank">{{$patient->lead->name}}</a></td>
								<?php
									$Birthday = false;
									if(in_array($patient->lead->id, $dob))
									{
										$Birthday = true;
									} 
								?>
								@if($Birthday)
									<td>{{ date('jS F Y',strtotime($patient->lead->dob))}} <i class="fa fa-birthday-cake" aria-hidden="true"></i></td>

								@else
									<td></td>
								@endif

								<td><div class="pull-right" data-html="true" data-toggle="popover" title="Tags" data-content="{!!$tags ==''?'No Tag':$tags!!}"><a href="/patient/{{$patient->id}}/tags" target="_blank"><i class="fa fa-tags fa-2x {{$patient->tags->isEmpty() ? 'red': 'green'}}"></i></a></div></td>


								<td>{{$patient->doctor ? "Dr. ".$patient->doctor : ''}}</td>

								<td align="center"><div data-html="true" data-toggle="popover" title="Herbs" data-content="{!!$herbs==''?'No Herb':$herbs!!}"><a href="/patient/{{$patient->id}}/herbs" target="_blank"><i class="fa fa-stethoscope fa-2x {{$patient->herbs->isEmpty() ? 'red': 'green'}}"></i></a></div></td>

							
								<td><div class="article"><div class="description"><p>{{$patient->suit->trial_plan or ""}}</p><a href="#more" class="more grad"></a></div></div></td>

								<td><div class="pull-left" data-html="true" data-toggle="popover" title="Notes" data-content="{!!$notes==''?'No Note':$notes!!}"><a href="/patient/{{$patient->id}}/notes" target="_blank"><i class="fa fa-sticky-note fa-2x {{$patient->notes->isEmpty() ? 'red': 'green'}}"></i></a></div>@if($patient->notes->isEmpty())<div class="article"><div class="description"><p>{{$patient->remark}}</p><a href="#more" class="more grad"></a></div></div>@endif
								<div class="pull-right" data-toggle="popover" data-html="true" data-content="<b>Start Date</b> : {{$fee->start_date->format('d-M-Y')}}<p><b>End Date</b> : {{$fee->end_date->format('d-M-Y')}}"><i class="fa fa-info-circle"></i></div></td>

								<td>{{!$patient->diets->isEmpty()?date('d-m-Y', strtotime($patient->diets->first()->date_assign)) :''}}</td>

					@for($j=0; $j<$days; $j++)

					<?php 
							$dt = date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date)));
							$today = "";
							if(date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date))) == date('Y-m-d')) {
								$today = "today";
							}
					?>
						
						@if($dt < $fee->start_date->format('Y-m-d'))

							<td></td>

						@elseif($dt == $fee->start_date->format('Y-m-d'))

							<td title="Program Start" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}""><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-play"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

						@elseif($dt == $fee->end_date->format('Y-m-d'))

							<td title="Program End" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-stop"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

						@elseif($patient->diets->where('date_assign', $dt)->first())
							
							@if($dt < date('Y-m-d'))
								<td align="center" class="{{$today}}"><span class="label label-primary"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
							@elseif($dt == date('Y-m-d'))
								<td align="center" class="{{$today}}"><span class="label label-success"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
							@elseif($dt > date('Y-m-d'))
								<td align="center" class="{{$today}}"><span class="label label-info"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
							@endif

						@elseif($dt >= $patient->start_date && $dt <= date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-danger"><span class="fa fa-close"></span></span><span class="hide">N</span></td>
						@else
							<td class="{{$today}}"></td>	
						@endif

					@endfor

				</tr>
			@endif
		@elseif ($patient->break)
			@if($patient->break->start_date <= date('Y-m-d') &&  $patient->break->end_date > date('Y-m-d'))
				<tr style="background-color: #C0C0C0" >
								<td>{{$x++}}</td>
								<td><a href="/patient/{{$patient->id}}/diet" target="_blank">{{$patient->lead->name}}</a></td>
								<?php
									$Birthday = false;
									if(in_array($patient->lead->id, $dob))
									{
										$Birthday = true;
									} 
								?>
								@if($Birthday)
									<td>{{ date('jS F Y',strtotime($patient->lead->dob))}} <i class="fa fa-birthday-cake" aria-hidden="true"></i></td>

								@else
									<td></td>
								@endif

								<td><div class="pull-right" data-html="true" data-toggle="popover" title="Tags" data-content="{!!$tags ==''?'No Tag':$tags!!}"><a href="/patient/{{$patient->id}}/tags" target="_blank"><i class="fa fa-tags fa-2x {{$patient->tags->isEmpty() ? 'red': 'green'}}"></i></a></div></td>


								<td>{{$patient->doctor ? "Dr. ".$patient->doctor : ''}}</td>

								<td align="center"><div data-html="true" data-toggle="popover" title="Herbs" data-content="{!!$herbs==''?'No Herb':$herbs!!}"><a href="/patient/{{$patient->id}}/herbs" target="_blank"><i class="fa fa-stethoscope fa-2x {{$patient->herbs->isEmpty() ? 'red': 'green'}}"></i></a></div></td>

							
								<td><div class="article"><div class="description"><p>{{$patient->suit->trial_plan or ""}}</p><a href="#more" class="more grad"></a></div></div></td>

								<td><div class="pull-left" data-html="true" data-toggle="popover" title="Notes" data-content="{!!$notes==''?'No Note':$notes!!}"><a href="/patient/{{$patient->id}}/notes" target="_blank"><i class="fa fa-sticky-note fa-2x {{$patient->notes->isEmpty() ? 'red': 'green'}}"></i></a></div>@if($patient->notes->isEmpty())<div class="article"><div class="description"><p>{{$patient->remark}}</p><a href="#more" class="more grad"></a></div></div>@endif
								<div class="pull-right" data-toggle="popover" data-html="true" data-content="<b>Start Date</b> : {{$fee->start_date->format('d-M-Y')}}<p><b>End Date</b> : {{$fee->end_date->format('d-M-Y')}}"><i class="fa fa-info-circle"></i></div></td>

								<td>{{!$patient->diets->isEmpty()?date('d-m-Y', strtotime($patient->diets->first()->date_assign)) :''}}</td>

					@for($j=0; $j<$days; $j++)

					<?php 
							$dt = date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date)));
							$today = "";
							if(date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date))) == date('Y-m-d')) {
								$today = "today";
							}
					?>
						
						@if($dt < $fee->start_date->format('Y-m-d'))

							<td></td>

						@elseif($dt == $fee->start_date->format('Y-m-d'))

							<td title="Program Start" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}""><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-play"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

						@elseif($dt == $fee->end_date->format('Y-m-d'))

							<td title="Program End" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-stop"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

						@elseif($patient->diets->where('date_assign', $dt)->first())
							
							@if($dt < date('Y-m-d'))
								<td align="center" class="{{$today}}"><span class="label label-primary"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
							@elseif($dt == date('Y-m-d'))
								<td align="center" class="{{$today}}"><span class="label label-success"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
							@elseif($dt > date('Y-m-d'))
								<td align="center" class="{{$today}}"><span class="label label-info"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
							@endif

						@elseif($dt >= $patient->start_date && $dt <= date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-danger"><span class="fa fa-close"></span></span><span class="hide">N</span></td>
						@else
							<td class="{{$today}}"></td>	
						@endif

					@endfor

				</tr>
			@endif

		@else
		<tr>
		

			
							<td>{{$x++}}</td>
							<td><a href="/patient/{{$patient->id}}/diet" target="_blank">{{$patient->lead->name}}</a></td>
							<?php
								$Birthday = false;
								if(in_array($patient->lead->id, $dob))
								{
									$Birthday = true;
								} 
							?>
							@if($Birthday)
								<td>{{ date('jS F Y',strtotime($patient->lead->dob))}} <i class="fa fa-birthday-cake" aria-hidden="true"></i></td>

							@else
								<td></td>
							@endif

							<td><div class="pull-right" data-html="true" data-toggle="popover" title="Tags" data-content="{!!$tags ==''?'No Tag':$tags!!}"><a href="/patient/{{$patient->id}}/tags" target="_blank"><i class="fa fa-tags fa-2x {{$patient->tags->isEmpty() ? 'red': 'green'}}"></i></a></div></td>


							<td>{{$patient->doctor ? "Dr. ".$patient->doctor : ''}}</td>

							<td align="center"><div data-html="true" data-toggle="popover" title="Herbs" data-content="{!!$herbs==''?'No Herb':$herbs!!}"><a href="/patient/{{$patient->id}}/herbs" target="_blank"><i class="fa fa-stethoscope fa-2x {{$patient->herbs->isEmpty() ? 'red': 'green'}}"></i></a></div></td>

						
							<td><div class="article"><div class="description"><p>{{$patient->suit->trial_plan or ""}}</p><a href="#more" class="more grad"></a></div></div></td>

							<td><div class="pull-left" data-html="true" data-toggle="popover" title="Notes" data-content="{!!$notes==''?'No Note':$notes!!}"><a href="/patient/{{$patient->id}}/notes" target="_blank"><i class="fa fa-sticky-note fa-2x {{$patient->notes->isEmpty() ? 'red': 'green'}}"></i></a></div>@if($patient->notes->isEmpty())<div class="article"><div class="description"><p>{{$patient->remark}}</p><a href="#more" class="more grad"></a></div></div>@endif
							<div class="pull-right" data-toggle="popover" data-html="true" data-content="<b>Start Date</b> : {{$fee->start_date->format('d-M-Y')}}<p><b>End Date</b> : {{$fee->end_date->format('d-M-Y')}}"><i class="fa fa-info-circle"></i></div></td>

							<td>{{!$patient->diets->isEmpty()?date('d-m-Y', strtotime($patient->diets->first()->date_assign)) :''}}</td>

				@for($j=0; $j<$days; $j++)

				<?php 
						$dt = date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date)));
						$today = "";
						if(date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date))) == date('Y-m-d')) {
							$today = "today";
						}
				?>
					
					@if($dt < $fee->start_date->format('Y-m-d'))

						<td></td>

					@elseif($dt == $fee->start_date->format('Y-m-d'))

						<td title="Program Start" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}""><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-play"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

					@elseif($dt == $fee->end_date->format('Y-m-d'))

						<td title="Program End" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-stop"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

					@elseif($patient->diets->where('date_assign', $dt)->first())
						
						@if($dt < date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-primary"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@elseif($dt == date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-success"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@elseif($dt > date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-info"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@endif

					@elseif($dt >= $patient->start_date && $dt <= date('Y-m-d'))
						<td align="center" class="{{$today}}"><span class="label label-danger"><span class="fa fa-close"></span></span><span class="hide">N</span></td>
					@else
						<td class="{{$today}}"></td>	
					@endif

				@endfor
				</tr>
		@endif
	@endif	
@endforeach

					</tbody>
				</table>
	@endif
        	</div>
    	<!-- Appointments End -->

      	<!-- Primary Nutritionist Start -->
        	<div role="tabpanel" class="tab-pane" id="primary">
    @if(!$patients->isEmpty())    	
          	<a name="download" id="pri" class="btn btn-primary pull-right" style="margin:10px"><i class="fa fa-download fa-2x" title="Download Primary Nutritionist as CSV"></i></a>
				<table id="primary_table" class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Patient Id</th>
							<th width="13%">Name</th>
							<th width="10%">Upcoming Birthday</th>
							<th>Tags</th>										
							<th>Doctor</th>
							<th>Herbs (&#8478;)</th>
							<th>Call Time</th>
							<th>Remark/Note</th>
							<th>Last Diet</th>
							<th>Advance Diet</th>

					@for($j=0; $j<$days; $j++)
							<th>{{date('j-M', strtotime('+ '.$j.' days', strtotime($start_date)))}}</th>
					@endfor

						</tr>
					</thead>
					<tbody>

			@foreach($patients as $patient)					
<?php
	$herbs = "";
	$tags = ""	;	
	$notes = "";

	foreach($patient->herbs AS $herb)
	{
		$herbs .= "<p>".$herb->herb->name;
	}
	
	foreach($patient->tags as $tag) {
		$tags .= $tag->name . "<p>";
	}
	foreach($patient->notes as $note) {
		$notes .= $note->text . " : <b>". $note->created_by."</b><em> (".$note->created_at.")</em><p>";
	}
	$fee  = $patient->cfee ? $patient->cfee : $patient->fee;
	
?>	<tr>
							<td>{{$y++}}</td>
							<td>{{$patient->id}}</td>
							<td><a href="/patient/{{$patient->id}}/diet" target="_blank">{{$patient->lead->name}}</a></td>

							<?php
								$Birthday = false;
								if(in_array($patient->lead->id, $dob))
								{
									$Birthday = true;
								} 
							?>
							@if($Birthday)
								<td>{{ date('jS F Y',strtotime($patient->lead->dob))}} <i class="fa fa-birthday-cake" aria-hidden="true"></i></td>

							@else
								<td></td>
							@endif

							<td><div class="pull-left" data-html="true" data-toggle="popover" title="Tags" data-content="{!!$tags ==''?'No Tag':$tags!!}"><a href="/patient/{{$patient->id}}/tags" target="_blank"><i class="fa fa-tags fa-2x {{$patient->tags->isEmpty() ? 'red': 'green'}}"></i></a></div>
							</td>


							<td>{{$patient->doctor ? "Dr. ".$patient->doctor : ''}}</td>

							<td align="center"><div data-html="true" data-toggle="popover" title="Herbs" data-content="{!!$herbs==''?'No Herb':$herbs!!}"><a href="/patient/{{$patient->id}}/herbs" target="_blank"><i class="fa fa-stethoscope fa-2x {{$patient->herbs->isEmpty() ? 'red': 'green'}}"></i></a></div></td>

							<td><div class="article"><div class="description"><p>{{$patient->suit->trial_plan or ""}}</p><a href="#more" class="more grad"></a></div></div></td>

							<td><div class="pull-left" data-html="true" data-toggle="popover" title="Notes" data-content="{!!$notes==''?'No Note':$notes!!}"><a href="/patient/{{$patient->id}}/notes" target="_blank"><i class="fa fa-sticky-note fa-2x {{$patient->notes->isEmpty() ? 'red': 'green'}}"></i></a></div>@if($patient->notes->isEmpty())<div class="article"><div class="description"><p>{{$patient->remark}}</p><a href="#more" class="more grad"></a></div></div>@endif
							<div class="pull-right" data-toggle="popover" data-html="true" data-content="<b>Start Date</b> : {{$fee->start_date->format('d-M-Y')}}<p><b>End Date</b> : {{$fee->end_date->format('d-M-Y')}}"><i class="fa fa-info-circle"></i></div></td>

							<td>{{!$patient->diets->isEmpty()?date('d-m-Y', strtotime($patient->diets->first()->date_assign)) :''}}	</td>
							<td style="text-align:center"><input type="checkbox" name="advance_diet" id="{{$patient->id}}" class="advance" {{$patient->advance_diet ? 'checked' :''}}  ></td>

				@for($j=0; $j<$days; $j++)

				<?php 
						$dt = date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date)));
						$today = "";
						if(date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date))) == date('Y-m-d')) {
							$today = "today";
						}
				?>
					
					@if($dt < $fee->start_date->format('Y-m-d'))

						<td></td>

					@elseif($dt == $fee->start_date->format('Y-m-d'))

						<td title="Program Start" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}""><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-play"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

					@elseif($dt == $fee->end_date->format('Y-m-d'))

						<td title="Program End" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-stop"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

					@elseif($patient->diets->where('date_assign', $dt)->first())
						
						@if($dt < date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-primary"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@elseif($dt == date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-success"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@elseif($dt > date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-info"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@endif

					@elseif($dt >= $patient->start_date && $dt <= date('Y-m-d'))
						<td align="center" class="{{$today}}"><span class="label label-danger"><span class="fa fa-close"></span></span><span class="hide">N</span></td>
					@else
						<td class="{{$today}}"></td>	
					@endif

				@endfor

						</tr>
			@endforeach

					</tbody>
				</table>
	@endif
        	</div>
        	<!-- Primary Nutritionist End -->

        	<!-- Secondary Nutritionist Begin -->
        	<div role="tabpanel" class="tab-pane fade" id="secondary">
    @if(!$secondaryPatients->isEmpty())
          		<a name="download" id="sec" class="btn btn-primary pull-right" style="margin:10px"><i class="fa fa-download fa-2x" title="Download Secondary Nutritionist as CSV"></i></a>
				<table id="secondary_table" class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>		
							<th>Name</th>
							<th width="10%">Upcoming Birthday</th>	
							<th>Tags</th>
							<th>Primary Nutritionist</th>					
							<th>Herbs (&#8478;)</th>					
							<th>Call Time</th>
							<th>Remark</th>
							<th>Last Diet</th>

					@for($j=0; $j<$days; $j++)
							<th>{{date('j-M', strtotime('+ '.$j.' days', strtotime($start_date)))}}</th>
					@endfor

						</tr>
					</thead>
					<tbody>
<?php $i = 1 ?>
			@foreach($secondaryPatients as $patient)
<?php
	$herbs = "";
	$tags = ""	;	
	$notes = "";
	foreach($patient->herbs AS $herb)
	{
		$herbs .= "<p>".$herb->herb->name." : ".$herb->quantity." ".$herb->unit->name." ".$herb->remark." - <small><em>[".date('jS M, Y', strtotime($herb->created_at))."]</em></small><p>";
	}
	foreach($patient->tags as $tag) {
		$tags .= $tag->name . "<p>";
	}
	foreach($patient->notes as $note) {
		$notes .= $note->text . " : <b>". $note->created_by."</b><em> (".$note->created_at.")</em><p>";
	}
	$fee  = $patient->cfee ? $patient->cfee : $patient->fee;
?>
						<tr>
							<td></td>
							<td><a href="/lead/{{$patient->lead->id}}/viewDetails" target="_blank">{{$patient->lead->name}}</a></td>
							<?php
								$Birthday = false;
								if(in_array($patient->lead->id, $dob))
								{
									$Birthday = true;
								} 
							?>
							@if($Birthday)
								<td>{{ date('jS F Y',strtotime($patient->lead->dob))}} <i class="fa fa-birthday-cake" aria-hidden="true"></i></td>

							@else
								<td></td>
							@endif
							<td><div class="pull-right" data-html="true" data-toggle="popover" title="Tags" data-content="{!!$tags ==''?'No Tag':$tags!!}"><a href="/patient/{{$patient->id}}/tags" target="_blank"><i class="fa fa-tags fa-2x {{$patient->tags->isEmpty() ? 'red': 'green'}}"></i></a></div></td>

							<td>{{$patient->nutritionist}}</td>

							<td align="center"><div data-html="true" data-toggle="popover" title="Herbs" data-content="{!!$herbs==''?'No Herb':$herbs!!}"><a href="/patient/{{$patient->id}}/herbs" target="_blank"><i class="fa fa-stethoscope fa-2x {{$patient->herbs->isEmpty() ? 'red': 'green'}}"></i></a></div></td>
							<td><div class="article"><div class="description"><p>{{$patient->suit->trial_plan or ''}}</p><a href="#more" class="more grad"></a></div></div></td>

							<td><div class="pull-left" data-html="true" data-toggle="popover" title="Notes" data-content="{!!$notes==''?'No Note':$notes!!}"><a href="/patient/{{$patient->id}}/notes" target="_blank"><i class="fa fa-sticky-note fa-2x {{$patient->notes->isEmpty() ? 'red': 'green'}}"></i></a></div>@if($patient->notes->isEmpty())<div class="article"><div class="description"><p>{{$patient->remark}}</p><a href="#more" class="more grad"></a></div></div>@endif
							<div class="pull-right" data-toggle="popover" data-html="true" data-content="<b>Start Date</b> : {{$fee->start_date->format('d-M-Y')}}<p><b>End Date</b> : {{$fee->end_date->format('d-M-Y')}}"><i class="fa fa-info-circle"></i></div></td>
						
						@if(!$patient->diets->isEmpty())
							<td>{{date('d-m-Y',strtotime($patient->diet->date_assign))}}</td>
						@else
							<td></td>
						@endif


				@for($j=0; $j<$days; $j++)

			<?php 
						$dt = date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date)));
						$today = "";
						if(date('Y-m-d', strtotime('+ '.$j.' days', strtotime($start_date))) == date('Y-m-d')) {
							$today = "today";
						}
				?>
					
					@if($dt < $fee->start_date->format('Y-m-d'))

						<td></td>

					@elseif($dt == $fee->start_date->format('Y-m-d'))

						<td title="Program Start" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}""><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-play"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

					@elseif($dt == $fee->end_date->format('Y-m-d'))

						<td title="Program End" style="text-align:center" class="{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><span class="label label-{{$patient->diets->where('date_assign', $dt)->first() ? 'success' : 'danger'}}"><i class="fa fa-stop"></i></span><span class="hide">{{$patient->diets->where('date_assign', $dt)->first() ? 'Y' : 'N'}}</span></td>

					@elseif($patient->diets->where('date_assign', $dt)->first())
						
						@if($dt < date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-primary"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@elseif($dt == date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-success"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@elseif($dt > date('Y-m-d'))
							<td align="center" class="{{$today}}"><span class="label label-info"><i class="fa fa-check"></i></span><span class="hide">Y</span></td>
						@endif

					@elseif($dt >= $patient->start_date && $dt <= date('Y-m-d'))
						<td align="center" class="{{$today}}"><span class="label label-danger"><span class="fa fa-close"></span></span><span class="hide">N</span></td>
					@else
						<td class="{{$today}}"></td>	
					@endif

				@endfor

						</tr>
			@endforeach

					</tbody>
				</table>
	@endif
			</div>
			<!-- Secondary Nutritionist End -->
		</div>
	</div>	
</div>

<script type="text/javascript">
	function checkLength() {
	    this.showing = new Array();
	}

	checkLength.prototype.check = function() {
	    var that = this;
	    $('.article').each(function (index) {
	        var article = $(this);
	        var theP = article.find('p');
	        var theMore = article.find('.more');
	        if (theP.width() > article.width()) {
	            theMore.show();
	            that.showing[index] = true;
	        } else {
	            if (!article.hasClass('active')) {
	                theMore.hide();
	                that.showing[index] = false;
	            } else {
	                that.showing[index] = false;
	            }
	        }
	        theMore.text(that.showing[index] ? ">>" : "<<");
	    });
	};

	$(function () {
	    var checker = new checkLength();
	    checker.check();
	    $('.more').each(function () {

	        $(this).on('click', function (e) {
	            $(this).closest('.article').toggleClass('active');
	            checker.check();
	        });
	    });

	    $(window).resize(function() {
	        checker.check()
	    });
	});
</script>
<style type="text/css">
	.today {
		background-color:#DFF0D8;
		color: #333;
		/*background-image: -webkit-linear-gradient(top, #5d9ed2, #4386bc);*/
	}
	.hide {
		display: none;
	}
	.article {
    max-width:11em;
    font-size: 10px;
}
.description {
    position: relative;
    overflow:hidden;
}
.more {
    position: absolute;
    bottom:0;
    right:0;
    padding-left:2em;
}
.article p {
    padding:0;
    margin:0;
    white-space:nowrap;
    float:left;
}
.active.article p {
    white-space:normal;
}
.active.article .more {
    position: static;
    padding:0;
}
/* long messy gradient background code */
 .grad {
    background: -moz-linear-gradient(left, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(241, 241, 241, 0)), color-stop(19%, rgba(241, 241, 241, 0.53)), color-stop(36%, rgba(241, 241, 241, 1)));
    /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(left, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(left, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* Opera 11.10+ */
    background: -ms-linear-gradient(left, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* IE10+ */
    background: linear-gradient(to right, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00f1f1f1', endColorstr='#f1f1f1', GradientType=1);
    /* IE6-9 */
}
</style>
<script type="text/javascript">
$(document).ready(function() 
{
	$( "#app" ).bind( "click", function() 
  	{
    	var csv_value = $('#appointment_table').table2CSV({
                delivery: 'value'
            });
    	downloadFile('appointment.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  	});
  	
  	$( "#pri" ).bind( "click", function() 
  	{
    	var csv_value = $('#primary_table').table2CSV({
                delivery: 'value'
            });
    	downloadFile('primary.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  	});

  $( "#sec" ).bind( "click", function() 
  {
    var csv_value = $('#secondary_table').table2CSV({
                delivery: 'value'
            });
    downloadFile('secondary.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    $("#csv_text").val(csv_value);  
  });

  function downloadFile(fileName, urlData){
    var aLink = document.createElement('a');
    var evt = document.createEvent("HTMLEvents");
    evt.initEvent("click");
    aLink.download = fileName;
    aLink.href = urlData ;
    aLink.dispatchEvent(evt);
}
});
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" }); 
    $("[name='advance_diet1']").bootstrapSwitch();

    $('.advance').on('click', function() {
    	var id = this.id;
    	var state = 0;
    	if (this.checked) {state = 1};
    	
    	//var r=confirm("Are you sure you want to delete?");
    	//if (r==true){
            var url = "/patient/" + id + "/advance_diet"; //
	        $.ajax(
	        {
	           type: "POST",
	           url: url,
	           data: {state : state, "_token" : "{{ csrf_token()}}"}, // send Source Id.
	           success: function(data)
	           {
	               $('#alert').show();
	               $('#alert').empty().append(data);
	               setTimeout(function()
	                {
	                    $('#alert').slideUp('slow').fadeOut(function() 
	                    {
	                        //location.reload();
	                     });
	                }, 3000);
	           }
	        });
        //};
	});
});
</script>