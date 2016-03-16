@extends('lead.index')

@section('top')
<?php
	$readonly = "readonly";
	$title = "You do not have the permissions to edit this field. Please contact your Senior or Marketing Department.";
	if (Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('marketing')) {
		$readonly = "";
		$title = "";
	}
?>	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">PERSONAL DETAILS</h2>
		</div>
		<div class="panel-body">
			<form method="POST" action="/lead/{{ $lead->id }}/savePersonalDetails" role="form" class="form-inline" id="form">		<fieldset>
					<ol>
						<li>
							<label>Name</label>
							<input type="text" id="name" name="name" title="{{$title}}" value="{{ $lead->name }}" {{trim($lead->name) == '' || strpos($lead->name, 'Client') !== false ? '' : $readonly}}>
						</li>
						<li>
							<label>Date of Birth <br><em><small>(DD-MM-YYYY)</small></em></label>
							<input id="datepicker" type="text" id="dob" name="dob" placeholder="dd-mm-YYYY" value="{{ $lead->dob ? date('d-m-Y', strtotime($lead->dob)) : "" }}">
							<input type="image" id="calendar" src="/images/calendar.png">
						</li>
						<li>
							<label>Gender</label>
							<input type="radio" name="gender" id="gender" value="F" {{ ($lead->gender == 'F') ? 'checked' : '' }} required> Female &nbsp;
							<input type="radio" name="gender" id="gender" value="M" {{ ($lead->gender == 'M') ? 'checked' : '' }}> Male
						</li>
						<li>
							<label>Profession</label>
							<input type="text" id="height" name="profession" value="{{ $lead->profession }}">
						</li>
						<li>
							<label>Organization</label>
							<input type="text" id="height" name="organization" value="{{ $lead->company }}">
						</li>
						<li>
							<label>Height (cms)</label>
							<input type="text" id="height" name="height" value="{{ $lead->height }}">
						</li>
						<li>
							<label>Weight (Kgs)</label>
							<input type="text" id="weight" name="weight" value="{{ $lead->weight }}">
						</li>
						<li>
							<label>BMI</label>
							
						</li>
					</ol>
				</fieldset>					
				<div  class="col-md-3">
					<button type="submit" id="edit" name="edit" class="btn btn-primary">Edit</button>
					<button id="save" type="submit" name="save" class="btn btn-success"> Save</button> 
					<button id="cancel" type="submit" name="cancel" class="btn btn-danger">Cancel</button>
				</div>
				<div class="alert alert-warning" id="alert" role="alert"></div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
		</div>	
	</div>
<script type="text/javascript">
	$(document).ready(function() 
	{     
	   
	   	$('#calendar').click(function(event) {
		    event.preventDefault();
		});

	   	$( "#calendar" ).datetimepicker({
	   		timepicker: false,
	   		maxDate: new Date,
	   		format:'d-m-Y',
	   		onChangeDateTime:function(dp,$input){
			    $( "#datepicker" ).val($input.val())
			  }
	    });

	   	$( "#datepicker" ).datetimepicker({
	   		timepicker: false,
            maxDate: new Date,
	   		format:'d-m-Y'
	   });
	});
</script>
<script type="text/javascript" src="/js/form.js"></script>	

<script type="text/javascript">
	$('input[readonly]').click(function () {
	    alert('{{$title}}');
	})	
</script>
@endsection			