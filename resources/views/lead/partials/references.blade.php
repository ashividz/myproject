@extends('lead.index')

@section('top')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">References</h2>
		</div>
		<div class="panel-body">
		
		@if(!$lead->dnc)
			<form method="POST" action="/lead/{{ $lead->id }}/saveReference" role="form" class="form-inline" id="form">		
				<fieldset id="reference" style="display:none">
					<ol>
						<li>
							<label>Date</label>
							<input id="datepicker" type="text" id="dob" name="dob" placeholder="YYYY-mm-dd" value="{{ date('m/d/Y')}}">
							<input type="image" id="calendar" src="/images/calendar.png">
						</li>
						<li>
							<label>Name *</label>
							<input type="text" id="name" name="name" required >
						</li>
						<li>
							<label>Gender *</label>
							<input type="radio" name="gender" id="gender" value="F" required> Female &nbsp;
							<input type="radio" name="gender" id="gender" value="M"> Male
						</li>
						<li>
							<label>Email</label>
							<input type="text" id="email" name="email" value="">
						</li>
						<li>
							<label>Mobile *</label>
							<input type="text" id="mobile" name="mobile" required>
						</li>
						<li>
							<label>How did you hear about us?</label>
							<div class="dropdown">
								<select id="voice" name="voice">
								</select>
						 	</div>
						</li>
						<li>
							<label>Country</label>
							<div class="dropdown">
								<select id="country" onchange="selectState(this.options[this.selectedIndex].value)" name="country">
									<option value="">Select Country</option>
								</select>
						 	</div>
						</li>
						<li>
							<label>State/Region</label>
							<select id="state" onchange="selectCity(this.options[this.selectedIndex].value)" name="state">
		                        <option value="">Select State</option>
		                    </select>
						</li>
						<li>
							<label>City</label>
							<select id="city" name="city">
		                        <option value="">Select City</option>
		                    </select>
						</li>
						<li>
							<label>Remarks</label>
							<textarea rows="3" cols="50" id="remarks" name="remarks" required>
							</textarea>
						</li>
					@if(Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('admin'))

						<li>
							<label>Sourced By</label>
							<input type="text" id="sourced_by" name="sourced_by">
						</li>
					@else
							<input type="hidden" name="sourced_by" value="{{ Auth::user()->employee->name }}">
					@endif					
					</ol>
				</fieldset>					
				<div  class="col-md-3">
					<button type="submit" id="add" name="add" class="btn btn-primary">Add</button>
					<button id="save" type="submit" name="save" class="btn btn-success"> Save</button> 
					<button id="cancel" type="submit" name="cancel" class="btn btn-danger">Cancel</button>
				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="source" value="10">
				<input type="hidden" name="referrer_clinic" value="{{ $lead->clinic }}">
				<input type="hidden" name="referrer_enquiry_no" value="{{ $lead->enquiry_no }}">
				<input type="hidden" name="referrer_id" value="{{ $lead->id }}">
			</form>

		@else
			<div class="blacklisted"></div>
		@endif
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			References
		</div>
		<div class="panel-body">
			<table class="table table-bordered">
				<thead>					
					<tr>
						<th>Lead Id</th>
						<th>Patient Id</th>
						<th>Name</th>
						<th>Phone</th>
						<th>Email</th>
						<th>Voice</th>
						<th>Date</th>
						<th>Sourced By</th>
					</tr>
				</thead>
				<tbody>
			@foreach($references AS $reference)
					<tr>
						<td><a href="/lead/{{$reference->id}}/viewDispositions" target="_blank">{{$reference->id}}</a></td>
						<td>
						@if(isset($reference->patient))
							<a href="/patient/{{$reference->patient->id}}/diet" target="_blank"> 
								{{ $reference->patient->id}}
							</a>
						@endif
						</td>
						
						<td>{{$reference->name}}</td>
						<td>{{$reference->phone}}</td>
						<td>{{$reference->email}}</td>
						<td>{{$reference->voice or ""}}</td>
						<td>{{$reference->date ? date("dS M, Y h:i A", strtotime($reference->date)) : ""}}</td>
						<td>{{$reference->sourced_by}}</td>
					</tr>
			@endforeach
				</tbody>
			</table>
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
	   		format:'d/m/Y',
	   		onChangeDateTime:function(dp,$input){
			    $( "#datepicker" ).val($input.val())
			  }
	   });
	   	$( "#datepicker" ).datetimepicker({
	   		timepicker: false,
	   		format:'d/m/Y'
	   });

	   	var form = $("#form");
		$('#add').click(function(event) 
		{
		    event.preventDefault();
		    form.find(':disabled').each(function() 
		    {
		        $(this).removeAttr('disabled');
		    });

			$('#reference').show();
			$('#form-fields').show();
		    $('#add').hide();
		    $('#cancel').show();
		    $('#save').show();
	    	$('#alert').hide();
		});
	});
</script>
<script type="text/javascript">
$(document).ready(function() 
{     
    $("#country").empty();
    $("#country").append("<option value=''> Select Country </option>");
    $.getJSON("/api/getCountryList",function(result){
        var country = "{{ $lead->country }}";
        $.each(result, function(i, field){
            if (field.country_code == country) {
                $("#country").append("<option value='" + field.country_code + "' selected> " + field.country_name + "</option>");
            }
            else
            {
                $("#country").append("<option value='" + field.country_code + "'> " + field.country_name + "</option>");
            }       
        });
        selectState(country);
    });

});

/*This function is called when country dropdown value change*/
function selectState(country_id){
    //alert(country_id);
    //$("#state").prop("disabled", true);
    $("#city").empty();
    $("#city").append("<option value=''> Select City </option>");
    //$("#city").prop("disabled", true);
    getRegionCode(country_id);
    getPhoneCode(country_id);
    //$("#state").prop("disabled", false);
}

/*This function is called when state dropdown value change*/
function selectCity(state_id){
    //$("#city").prop("disabled", true);
    getCityCode(state_id);
    //$("#city").prop("disabled", false);
}



function getPhoneCode(country_id) {
    $.getJSON("https://portal.yuwow.com/access_api/phone_code.php", { country_code: country_id }, function(result){
        $.each(result, function(i, field) {
            $("#phone_code").val('+' + field.phone_code);
        });
    });
} 

function getRegionCode(country_id) {
	var state = "{{ $lead->state?$lead->state:'' }}".toUpperCase();
	if (state == 'NEW DELHI' || state =='DELHI' || state =='GURGAON' || state =='FARIDABAD' || state =='GHAZIABAD'  || state =='NOIDA') {
		state = "IN.07";
	}
    $.getJSON("/api/getRegionList", { country_code: country_id }, function(result){
        $("#state").empty();
        $("#state").append("<option value=''> Select State </option>");
        $.each(result, function(i, field) {            
        	if (field.region_code == state) {
                $("#state").append("<option value='" + field.region_code + "' selected> " + field.region_name + "</option>");
            }
            else
            {
                $("#state").append("<option value='" + field.region_code + "'> " + field.region_name + "</option>");
            }
        });
    });
    getCityCode(state);    
}

function getCityCode(state_id) {

	var city = "{{ $lead->city?$lead->city:'' }}";
    $.getJSON("/api/getCityList", { region_code: state_id }, function(result){
        $("#city").empty();
        $("#city").append("<option value=''> Select City </option>");
        $.each(result, function(i, field) {           
        	if (field.city_name.toUpperCase() == city.toUpperCase()) {
                $("#city").append("<option value='" + field.city_name + "' selected> " + field.city_name + "</option>");
            }
            else
            {
                $("#city").append("<option value='" + field.city_name + "'> " + field.city_name + "</option>");
            }
        });
    });
    
}
//Fetch Lead Voices   
    $("#voice").append("<option value=''> Select Voice </option>");
    $.getJSON("/api/getVoiceList",function(result){
        $.each(result, function(i, field){
            $("#voice").append("<option value='" + field.id + "'> " + field.name + "</option>");
        });
    });
</script>
<script type="text/javascript" src="/js/form.js"></script>	
@endsection