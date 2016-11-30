@extends('lead.index')

@section('top')
	
	<div class="panel panel-default">
	  	<div class="panel-heading">
	    	<h1 class="panel-title">REGISTRATION</h1>
	  	</div>
	  	<div class="panel-body">
	  		<form id="form-register" method="post" class="form-inline1">
	  			<div class="col-md-5">
	  				<div class="panel panel-default">
	  					<div class="panel-body">
	  						<div class="form-group">
	  							<label>Phone</label>
								<input type="text" id="phone" name="phone" value="{{ old('phone')?old('phone'):$lead->phone }}" size="15">
							</div>
	  						<div class="form-group">
	  							<label>Mobile</label>
								<input type="text" id="mobile" name="mobile" value="{{ old('mobile')?old('mobile'):$lead->mobile }}" size="15">
							</div>
	  						<div class="form-group">
	  							<label>Email</label>
								<input type="text" id="email" name="email" value="{{ old('email')?old('email'):$lead->email }}">
							</div>
	  					</div>
	  				</div>
	  			</div>
	  			<div class="col-md-7">
	  				<div class="panel panel-default">
	  					<div class="panel-body">

	  						<div class="form-group">
	  							<label>Address</label>
								<input type="text" id="address" name="address" value="{{ old('address')?old('address'):$lead->address }}">
							</div>

							<div class="form-group">
								<label>Country</label>
								<select id="country" onchange="selectState(this.options[this.selectedIndex].value)" name="country">
									<option value="">Select Country</option>
								</select>
							</div>

							<div class="form-group">
								<label>State</label>
								<select id="state" onchange="selectCity(this.options[this.selectedIndex].value)" name="state">
			                        <option value="">Select State</option>
			                    </select>
							</div>

							<div class="form-group">
								<label>City</label>
								<select id="city" name="city">
			                        <option value="">Select City</option>
			                    </select>
							</div>
	  					</div>
	  				</div>
	  			</div>
	  			
	  		
	  			<div class="form-group">
	  				<input type="hidden" name="id" value="{{ $lead->id }}">
	  			@if(!$lead->patient)	
	  				<input type="hidden" name="_token" value="{{ csrf_token() }}">
	  				<button class="btn btn-primary">Register Patient</button>
	  			@else
	  				<a href="/patient/{{$lead->patient->id}}/fee" class="btn btn-success">Enter Fee Details</a>
				@endif
	  			</div>
	  		</form>
	  	</div>
	</div>
<style type="text/css">
	#form .form-group{
		margin: 10px 20px;
	}
</style>
<script type="text/javascript">
$(document).ready(function() 
{
  $('#entry_date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'D-M-YYYY',
    maxDate: new Date() 
  }); 
  $('#start_date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'D-M-YYYY',
    minDate: new Date() 
  }); 
 });
</script>

<style type="text/css">
	label {
		min-width: 50px;
	}
</style>
<style type="text/css">
	label {
		min-width: 50px;
	}
</style>
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
</script>
@endsection