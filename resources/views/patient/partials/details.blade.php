@extends('patient.index')
@section('top')
<div class="col-md-4">		
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">Nutritionist</div>
		</div>
		<div class="panel-body">
			{!!!($patient->primaryNtr->isEmpty()) ? "<label>".$patient->primaryNtr->last()->nutritionist."</label> <em class='pull-right'>since ".$patient->primaryNtr->last()->created_at->format('jS M, Y')."</em>" :'NA'!!}
		</div>
	</div>
</div>
<div class="col-md-4">		
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">Secondary Nutritionist</div>
		</div>
		<div class="panel-body">
			{!!!($patient->secondaryNtr->isEmpty()) ? "<label>".$patient->secondaryNtr->last()->nutritionist."</label> <em class='pull-right'>since ".$patient->secondaryNtr->last()->created_at->format('jS M, Y')."</em>" :'NA'!!}
		</div>
	</div>
</div>
<div class="col-md-4">		
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">Doctor</div>
		</div>
		<div class="panel-body">
			{!!!($patient->doctors->isEmpty()) ? "<label>Dr. ".$patient->doctors->last()->name."</label> <em class='pull-right'>since ".$patient->doctors->last()->created_at->format('jS M, Y')."</em>" :'NA'!!}
		</div>
	</div>
</div>
@endsection
@section('main')
	<div class="container">
		@include('lead.partials.details')
	</div>	
@endsection