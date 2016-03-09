@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('doctor') || Auth::user()->hasRole('registration'))			
	<div class="container">
		<div class="col-md-3">
			<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h1 class="panel-title">{{$patient->lead->name}}</h1>
			  	</div>
			  	<div class="panel-body">			  	
			  		<label class="form-control">Lead Id : <a href="/lead/{{$patient->lead_id}}/viewDispositions">{{$patient->lead_id}}</a> </label>
			  		<label class="form-control">Patient Id : {{ $patient->id }}
			  			<span class="pull-right">
			  				<a href="http://crm/patient.php?clinic={{$patient->clinic}}&registration_no={{$patient->registration_no}}&phone_number={{$patient->lead->phone or $patient->lead->mobile}}" target="_blank"><strike>{{ $patient->registration_no }}</strike></a>
			  			</span>
			  		</label>
  					<label class="form-control">Age : {{trim($patient->lead->dob)<>''?$patient->lead->dob->diff(Carbon::now())->format('%y years, %m months and %d days') : ''}}  					 
  					</label>
  					<label class="form-control">N : {{ $patient->nutritionist }}</label>

  				@if($patient->lead->dnc)
  					<label class="form-control">DNC</label>
  				@else
					<label class="form-control">P : {!! $patient->lead->phone or '' !!} 
						<a href="{{Lead::dialerUrl($patient->lead->phone)}}" target="_blank"><i class='fa fa-phone pull-right'></i></a>
					</label>
					<label class="form-control"> M : {!! $patient->lead->mobile or '' !!} <a href="{{Lead::dialerUrl($patient->lead->mobile)}}" target="_blank"><i class='fa fa-phone pull-right'></i></a>
					</label>
					<label class="form-control">E : {!! $patient->lead->email or '' !!}
					</label>
					<label class="form-control"> E (alt) : {!! $patient->lead->email_alt or '' !!}
					</label>
				@endif
			  	</div>
			</div>
		</div>
		<div class="col-md-9">

			<div class="row" id="sidebar">
			  	@include('patient.partials.sidenavbar')
			</div>
			<div class="row" style="margin-top:20px;">
				@yield('top')
			</div>
		</div>
	</div>
	<div>
		@yield('main')
	</div>
@else
	<h4>NOT AUTHORIZED</h4>
@endif