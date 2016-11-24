@if($lead->cre && Auth::user()->hasRole('cre') && !(Auth::user()->roles->count()>1) && trim($lead->cre->cre) <> trim(Auth::user()->employee->name) && (!$lead->dialer || $lead->hideDisposition))
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="alert alert-danger warning">
					<h4><b>{{ $lead->name }} ({{ $lead->id }})</b> belongs to </h4><strong><h2>{{$lead->cre->cre}}</h2></strong>
                    from {{$lead->cre->created_at->format('jS M, Y')}}
                    <h5>Please contact your Senior or the Marketing Department. </h5>
                    
                    <a href="/modal/{{ $lead->id }}/message" data-toggle="modal" data-target="#modal" title="Send Message">
                        <i class=" fa fa-envelope-o fa-2x"></i>
                    </a>

				</div>
			</div>
		</div>

<div id="dispositions">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">                
                <h5>CRM Dispositions</h5>
            </div>  
            <div class="panel-body">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th width="55%">Disposition</th>
                            <th>Name</th>
                            <th>Email/SMS</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php $i=0 ?>
            @foreach($lead->dispositions as $disposition)
                <?php $i++ ?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ date('jS-M-y h:i A', strtotime($disposition->created_at)) }}</td>
                            <td><b>{{ $disposition->master->disposition_code or "" }}</b>  : 
                                {{ $disposition->remarks }}
                                <small class="pull-right">{!! $disposition->callback ? "Callback On : " . date('jS-M-Y h:i A', strtotime($disposition->callback)) : "" !!}</small>
                            </td>
                            <td>{{ $disposition->name }}</td>
                            <td> 
                                {!! $disposition->email ? "<span class='label label-success'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Email Sent'></span></span>" : "" !!}
                                {!! $disposition->sms ? "<span class='label label-success'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='" . $disposition->sms . "'></span></span>" : "" !!}
                            </td>
                        </tr>
            @endforeach
                    </tbody>
                </table>
            </div>
        </div>      
    </div>
@if(isset($dialer_dispositions))
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">                
                <h5>Dialer Dispositions</h5>
            </div>  
            <div class="panel-body">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Disposition</th>
                            <th>Duration</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            <?php $i=0 ?>
            @foreach($dialer_dispositions as $disposition)
                <?php $i++ ?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ date('jS-M-y h:i A', strtotime($disposition->eventdate)) }}</td>
                            <td><b>{{ $disposition->disposition or "" }}</b>
                                
                            </td>
                            <td>{{ $disposition->duration or "" }}</td>
                            <td>{{ $disposition->userfullname or "" }}</td>
                            <td><a href='/playAudio/?mediafile={{ $disposition->filename or "" }}'><i class="fa fa-play-circle"></i></a></td>
                        </tr>
            @endforeach
                    </tbody>
                </table>
            </div>
        </div>      
    </div>
@endif
</div>

	<style type="text/css">
		.warning {
			text-align: center;
		}
	</style>

    @include('partials.modal')
@else

@if($lead->country!='IN')
<?php
	$alert = true;
	if(Session::has('country_alert_time')){
		if(Session::get('country_alert_lead_id')==$lead->id){
			if((time() - Session::get('country_alert_time')) < 120 )
				$alert = false;
		}
	}
	if($alert){
		Session::forget(['country_alert_time','country_alert_lead_id']);
		$countryAlert = array();
		$countryAlert['country_alert_time'] = time();
		$countryAlert['country_alert_lead_id'] = $lead->id;
		Session::put($countryAlert);
	}
?>	
@if($alert)
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="alert alert-danger warning">
				<a class="close" data-dismiss="alert">×</a>
				<strong><h5>This is a foreign client. Please check local time before calling</h5></strong> 
			</div>
		</div>
	</div>

	<style type="text/css">
		.warning {
			position: fixed;
			z-index: 9999;
			width:400px;
			min-height: 100px;
			text-align: center;
		}
	</style>	
@endif
@endif
<?php
    $data = "";
    if($lead->created_by) {
        $data .= '<b>Created By</b> : '.$lead->created_by.'<p>';
    }
    if($lead->created_at) {
        $data .='<b>Created At</b> : '.$lead->created_at->format('jS M, Y h:i A').'<p>';
    }
    if($lead->updated_by) {
        $data .= '<b>Updated By</b> : '.$lead->updated_by.'<p>';
    }
    if($lead->updated_at) {
        $data .='<b>Updated At</b> : '.$lead->updated_at->format('jS M, Y h:i A').'<p>';
    }

?>

<div class="container" style="margin-top:20px">
	<div class="col-md-3">
		<div class="panel panel-default">
		  	<div class="panel-heading">
		  		<div class="panel-title pull-right" data-html="true" data-toggle="popover" title="Lead Details" data-content="{!! $data !!}"><i class="fa fa-info-circle"></i></div>
		    	<h1 class="panel-title"><?php echo $lead->name;?></h1>
		  	</div>
		  	<div class="panel-body">	
            <?php $lead->getInternationalLocalTime(); ?> 
                		  	
		  		<label class="form-control">Lead Id : <?php echo $lead->id;?></label>

		  		<label class="form-control">Patient Id :   				
				@if($lead->patient)
					<a href="/patient/{{$lead->patient->id}}/diet">{{$lead->patient->id}}</a>
					<div class="pull-right">
						<strike>
							<a href="http://crm/patient.php?clinic={{$lead->clinic}}&registration_no={{$lead->patient->registration_no}}&phone_number={{$lead->phone or $lead->mobile}}" target="_blank"> {{ $lead->patient->registration_no }}</a>
						</strike>

					</div>
				@else
						<a href="/lead/{{$lead->id}}/register">Click</a>
				@endif  					
					</label>

					<label class="form-control">Age : {{trim($lead->dob)<>''?$lead->dob->diff(Carbon::now())->format('%y years, %m months and %d days') : ''}}  					 
					</label>
		  	@if($lead->patient && $lead->patient->hasTag('VIP'))
		  		<label class="form-control">VIP Client
				</label>
			@elseif($lead->dnc)
				<label class="form-control">DNC
				</label>
		  	@elseif($lead->maskPhone) 
                <p class="form-control redtime" style=''>Local Time:  {{($lead->current_time)? date("h:i:s a", strtotime($lead->current_time)): ''}}</p>
            @else
                @if(isset($lead->current_time))
                <p class="form-control greentime" style=''>Local Time:  {{($lead->current_time)? date("h:i:s a", strtotime($lead->current_time)): ''}}</p>
                @endif
				<label class="form-control">P : {{$lead->phone}} <a href="{{Lead::dialerUrl($lead->phone)}}" target="_blank"><i class='fa fa-phone pull-right'></i></a>
				</label>
				<label class="form-control"> M : {{$lead->mobile}} <a href="{{Lead::dialerUrl($lead->mobile)}}" target="_blank"><i class='fa fa-phone pull-right'></i></a>
				</label>
			@endif

            @if($lead->cre_name)
                <label class="form-control">CRE : {{ $lead->cre_name }}</label>
            @endif
				<label class="form-control" id="status">
				</label>

             
                   
                   

                
		  	</div>
		</div>
	</div>
	<div class="col-md-9">

		<div id="sidebar">
		  	@include('lead.partials.sidenavbar')
		</div>
		<div style="margin-top:20px;">
			@yield('top')
		</div>
	</div>
</div>
<div>
	@yield('main')
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#status').raty({ 
		readOnly: true,
		score: {{$lead->status_id ? $lead->status_id : 1}},
		hints : ['New', 'Explanined', 'Follow Up', 'Hot', 'Converted', 'Dead'],

	@if($lead->status_id == 6) 
		number : {{$lead->status_id}},
	@endif
	});

	$('[data-toggle="popover"]').popover({trigger : 'hover'}); 
});
</script>
<style type="text/css">
	.popover {
		min-width: 300px;
		max-width: 1024px;
		color: #444;
	}
    .redtime
    {
        background: #ac3939;
        color: #ffffff;
    }
     .greentime
    {   
        background: #2d8659;
        background: #9fdfbf;
        color: #333;
    }
</style>

@endif