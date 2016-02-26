@if($lead->cre)
	@if(Auth::user()->hasRole('cre') && $lead->cre->cre <> Auth::user()->employee->name)	
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="alert alert-danger warning">
					<a class="close" data-dismiss="alert">×</a>
					<strong>This lead belongs to <h3>{{$lead->cre->cre}}</h3></strong> 
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

<div class="container" style="margin-top:20px">
	<div class="col-md-3">
		<div class="panel panel-default">
		  	<div class="panel-heading">
		  		<div class="panel-title pull-right" data-html="true" data-toggle="popover" title="Lead Details" data-content="{{$lead->created_by ? '<b>Created By</b> : '.$lead->created_by.'<p>':''}}{{$lead->created_at ? '<b>Created At</b> : '.$lead->created_at->format('jS M, Y h:i A').'<p>':''}}"><i class="fa fa-info-circle"></i></div>
		    	<h1 class="panel-title"><?php echo $lead->name;?></h1>
		  	</div>
		  	<div class="panel-body">			  	
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
		  		<label class="form-control">Enq No : <?php echo $lead->clinic;?> - <?php echo $lead->enquiry_no;?></label>
		  	@if($lead->patient && $lead->patient->hasTag('VIP'))
		  		<label class="form-control">VIP Client
				</label>
			@elseif($lead->dnc)
				<label class="form-control">DNC
				</label>
		  	@else
				<label class="form-control">P : {{$lead->phone}} <a href="{{Lead::dialerUrl($lead->phone)}}" target="_blank"><i class='fa fa-phone pull-right'></i></a>
				</label>
				<label class="form-control"> M : {{$lead->mobile}} <a href="{{Lead::dialerUrl($lead->mobile)}}" target="_blank"><i class='fa fa-phone pull-right'></i></a>
				</label>
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
</style>