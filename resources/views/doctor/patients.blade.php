<?php
	$today = date('Y-m-d');	
	foreach ($patients as $patient) {							
		$fee = $patient->cfee ? $patient->cfee : $patient->fee;
		$welcomeCallDate = date('Y-m-d', strtotime('-'.'1'.' days', strtotime($fee->start_date)));
		$lastDisposition  = collect([$patient->lead->dialerphonedisposition,$patient->lead->dialermobiledisposition])
							->sortByDesc('eventdate')->first();
		$callBack 		  = collect([$patient->lead->mobilecallback,$patient->lead->phonecallback])
							->sortBy('callbackdate')->first();
		$patient->isCallBack = false;
		$patient->BTCall     = false;
		$patient->lastDisposition = $lastDisposition;
		
		$lastDispositionDate = $lastDisposition ? date('Y-m-d',strtotime($lastDisposition->eventdate)) :null;

		if ($patient->bt && $patient->medical) {
			$patient->lastBTDate = max( $patient->bt->report_date , date('Y-m-d',strtotime($patient->medical->date)) );
		} else {
			$patient->lastBTDate = $patient->bt ? $patient->bt->report_date : ($patient->medical ? date('Y-m-d',strtotime($patient->medical->date)) : null);
		}
		
		if ( $patient->lastBTDate && ( !$lastDisposition || ( $lastDisposition && strtotime($lastDisposition->eventdate) < strtotime($patient->lastBTDate) ) ) )
			$patient->BTCall =true ;

		//If it's a callback bypass all other things
		if ($callBack) {
			$nextCall = date('Y-m-d',strtotime($callBack->callbackdate));
			$patient->appointmentDate = $nextCall;
			$patient->iscallBack 	  = true;
			$patient->callbackdate    = $callBack->callbackdate;
		} elseif ( strtotime($welcomeCallDate) <= strtotime($today) ) {
			$nextCall = null;
			//If BT has been uploaded schedule a call next
			if ( $patient->lastBTDate && (!$lastDisposition || ($lastDisposition && $lastDisposition->eventdate < $patient->lastBTDate)) ) {
				$nexttCall = date('Y-m-d', strtotime('+'.'1'.' days', strtotime($patient->lastBTDate)));				
			} elseif ($lastDisposition && ( strtotime($lastDispositionDate) >= strtotime($welcomeCallDate)) ) {
				//schedule call +15 after last disposition date
				$nextCall = date('Y-m-d', strtotime('+'.$appointmentInterval.' days', strtotime($lastDisposition->eventdate)));							

			} else {
				//schedule call today
				$nextCall = $today;
			}

			
			if ($nextCall <= $fee->end_date)
				$patient->appointmentDate = $nextCall >= $today ? $nextCall : $today;
			else
				$patient->appointmentDate = null; //program ended schedule no call					
		} else {
			//schedule a welcome call if program has not started
			$nextCall = $welcomeCallDate;
			$patient->appointmentDate = $nextCall;			
		}
	}
?>							
<div class="container">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/daterange_users')
				</div>
				<h4>Patients</h4>
			</div>	
			<div class="panel-body">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
	        	<li role="presentation" class="active"><a href="#patients" aria-controls="primary" role="tab" data-toggle="tab">Patients</a></li>	        	
	        	<li role="presentation" ><a href="#appointments" aria-controls="summary	" role="tab" data-toggle="tab">Appointments</a></li>
	        </ul>

	        <!-- Tab panes -->
      		<div class="tab-content">
    		<!-- Appointments -->
        	<div role="tabpanel" class="tab-pane active" id="patients">   		
        		<table id="leads" class="table table-bordered">
					<thead>
						<tr>
							<td>#</td>
							<td>Name</td>
							<td>Start Date</td>
							<td>End Date</td>
							<td>Nutritionist</td>
							<td>Herbs</td>
							<td>Notes</td>
						</tr>
					</thead>
					<tbody>

				@foreach($patients AS $patient)
<?php
	$herbs = "";
	$tags = ""	;	
	$notes = "";
	//dd($patient->herbs);
	foreach($patient->herbs AS $herb)
	{
		if($herb->created_by != "Shilpa Kapur")
		{
			$herbs .= "<p>".$herb->herb->name." : ".$herb->quantity." ".$herb->unit->name." ".$herb->remark." - <small><em>[".date('jS M, Y', strtotime($herb->created_at))."]</em></small><p>";
		}
	
	}
	foreach($patient->tags as $tag) {
		$tags .= $tag->name . "<p>";
	}
	foreach($patient->notes as $note) {
		$notes .= $note->text . " : <b>". $note->created_by."</b><em> (".$note->created_at.")</em><p>";
	}
	
?>
						<tr>
							<td>
								{{$i++}}
							</td>
							<td><a href="/patient/{{$patient->id}}/herbs" target="_blank">{{$patient->lead->name}}</a><div class="pull-right" data-html="true" data-toggle="popover" title="Tags" data-content="{!!$tags==''?'No Tag':$tags!!}"><a href="/patient/{{$patient->id}}/tags" target="_blank"><i class="fa fa-tags fa-2x {{$patient->tags->isEmpty() ? 'red': 'green'}}"></i></a></div></td>
							<td>
								{{date('jS M, Y', strtotime($patient->fee->start_date))}}
							</td>
							<td>
								{{date('jS M, Y', strtotime($patient->fee->end_date))}}
							</td>
							<td>
								{{$patient->nutritionist or ""}}
							</td>
							<td><span data-html="true" data-toggle="popover" title="Herbs" data-content="{!!$herbs==''?'No Herb':$herbs!!}" data-placement="left"><a href="/patient/{{$patient->id}}/herbs" target="_blank"><i class="fa fa-stethoscope fa-2x {{$patient->herbs->isEmpty() ? 'red': 'green'}}"></i></a></span></td>

							<td align="center"><div data-html="true" data-toggle="popover" title="Notes" data-content="{!!$notes==''?'No Note':$notes!!}" data-placement="left"><a href="/patient/{{$patient->id}}/notes" target="_blank"><i class="fa fa-sticky-note-o fa-2x {{$patient->notes->isEmpty() ? 'red': 'green'}}"></i></a></div></td>
						</tr>

				@endforeach

					</tbody>
				</table>
			</div>
			<!--End of patients tab-->

			<!--Start of appointments tab-->
			
			<div role="tabpanel" class="tab-pane fade" id="appointments">				                    
            	<table class="table table-bordered" id="appointment_table">
					<thead>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Nuritionist</th>
							<th>Call Time</th>
							<th>Last Call Date</th>			
							<th>Next Call Date</th>
							<th>Herbs (&#8478;)</th>
							<th>Remark/Note</th>										
							<?php
							/*@for($y=0;$y<7;$y++)
								<th>{{date('j-M', strtotime('+ '.$y.' days', strtotime(date('Y-m-d'))))}}</th>
							@endfor*/
							?>
						</tr>
					</thead>
					<tbody>
						@foreach($patients AS $patient)
						<?php
							$app_date = $patient->appointmentDate;
							if ($app_date && ( strtotime($app_date)>=strtotime($start_date) && strtotime($app_date)<=strtotime($end_date) ) );
							else
								continue;
							$fee = $patient->cfee ? $patient->cfee : $patient->fee;
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
							$bt_notes = null;
							$cb_notes = null;
							if($patient->BTCall){
								$bt_notes = 'Last BT was uploaded on '.date('F j,Y',strtotime($patient->lastBTDate));
							}
							if($patient->isCallback)
								$cb_notes = 'You have a call back on '.date("F j, Y, g:i a",strtotime($patient->callbackdate));

						?>												
						<tr>
							<td>{{$x++}}</td>
							<td><a href="\patient\{!!$patient->id!!}\herbs" target="_blank">{{$patient->lead->name}}</a>
								<a href="/patient/{{$patient->id}}/tags" target="_blank" class="pull-right" data-html="true" data-toggle="popover" title="Tags" data-content="{!!$tags ==''?'No Tag':$tags!!}" ><i class="fa fa-tags fa-2x {{$patient->tags->isEmpty() ? 'red': 'green'}}"></i></a>

							</td>
							
							


							<td>{{$patient->nutritionist}}</td>

							<td>{{$patient->suit ? $patient->suit->trial_plan : ''}}</td>

							<td>
								@if($patient->lastDisposition)
								{{date("Y-m-d, g:i a",strtotime($patient->lastDisposition->eventdate))}}
								&nbsp;[{{$patient->lastDisposition->user->userfullname}}]
								
								<a data-toggle="modal" data-target="#disposition" href="/patient/{{$patient->id}}/doctordialercalls"><i class="fa fa-mobile danger" aria-hidden="true"></i></a>

								@endif
							</td>							
							
							<td>
								{{$patient->appointmentDate}}
								@if($patient->isCallback)
									<a href="#" class="pull-right"  data-html="true" data-toggle="popover" title ="Call back" data-content="{!!$cb_notes!!}"><i class="fa fa-phone danger"></i></a>
								@endif
								@if($patient->BTCall)
									<a href="#" class="pull-right" data-toggle="popover" data-html="true"  title ="Blood Test" data-content="{{$bt_notes}}" data-placement="left"><i class="fa fa-eyedropper danger"></i></a>
								@endif
							</td>							

							<td>
								<a href="/patient/{{$patient->id}}/herbs" target="_blank" data-html="true" data-toggle="popover" title="Herbs" data-content="{!!$herbs==''?'No Herb':$herbs!!}" data-placement="left"><i class="fa fa-stethoscope fa-2x {{$patient->herbs->isEmpty() ? 'red': 'green'}}"></i></a>
							</td>
							
							<td>
								<div class="pull-left" data-html="true" data-toggle="popover" title="Notes" data-content="{!!$notes==''?'No Note':$notes!!}" data-placement="left"><a href="/patient/{{$patient->id}}/notes"  target="_blank"><i class="fa fa-sticky-note fa-2x {{$patient->notes->isEmpty() ? 'red': 'green'}}"></i></a></div>

								@if($patient->notes->isEmpty())
									<div class="article"><div class="description"><p>{{$patient->remark}}</p><a href="	#more" class="more grad"></a></div></div>
								@endif
								<div class="pull-right" data-toggle="popover" data-html="true" data-content="<b>Start Date</b> : {{$patient->fee->start_date->format('d-M-Y')}}<p><b>End Date</b> : {{$patient->fee->end_date->format('d-M-Y')}}" data-placement="left"><i class="fa fa-info-circle"></i></div>
							</td>
							
							<?php
							/*@for($y=0;$y<7;$y++)
								<?php */
									//$dt = date('Y-m-d', strtotime('+ '.$y.' days', strtotime(date('Y-m-d'))));
								//
								/*<!-- <td>
									@if($dt==$patient->appointmentDate)
										<i class="fa fa-times danger" aria-hidden="true"></i>
									@endif
								</td>
							@endfor	 -->*/
							?>
						</tr>						
						@endforeach
					</tbody>					
				</table>
			</div>

			<!-- Modal Template-->
			<div class="modal fade" id="disposition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			                 <h4 class="modal-title">Dispositions</h4>

			            </div>
			            <div class="modal-body"></div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			                <button type="button" class="btn btn-primary">Save</button>
			            </div>
			        </div>
			        <!-- /.modal-content -->
			    </div>
			    <!-- /.modal-dialog -->
			</div>

		</div>
	</div>
	
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#leads').dataTable({
		"iDisplayLength": 100
	});
	$('#appointment_table').dataTable({
		"iDisplayLength": 1500,
		bPaginate : false,
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
        },
	});
	
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" }); 
});

$(document).on('hidden.bs.modal', function (e) {
    $(e.target).removeData('bs.modal');
});


</script>
<style type="text/css">
	.popover {
		text-align: left;
	    max-width: 1250px;
	}
</style>
<style type="text/css">
    #disposition .modal-dialog {
        /* new custom width */
        width: 95%;
    }
</style>