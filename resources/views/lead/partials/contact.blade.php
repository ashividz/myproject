@extends('lead.index')
@section('top')
<div class="panel panel-default">
	<div class="panel-heading">
		<h2 class="panel-title">ADDRESS</h2>
	</div>
	<div class="panel-body">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#address" aria-controls="address" role="tab" data-toggle="tab">Address</a></li>
        <li role="presentation" ><a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab">Addresses</a></li>        
    </ul>


    <div class="tab-content">
    <!-- Primary address -->
    <div role="tabpanel" class="tab-pane active" id="address">

    @if($lead->patient && $lead->patient->hasTag('VIP') && !(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('service') || Auth::user()->hasRole('doctor')))
  		<h3>VIP Client</h3>
  	@else
		@if(!$lead->dnc)
			<form method="POST" action="/lead/{{ $lead->id }}/saveContactDetails" role="form" class="form-inline" id="form">
				<fieldset>
					<ol>
						<li>
							<label>Mobile</label>
                        @if(Auth::user()->canEditLeadContact() || $lead->mobile == '')
							<input type="text" id="mobile" name="mobile" value="{{ $lead->mobile }}">
                        @else
                            {{ $lead->mobile }}
                        @endif
						</li>
						<li>
							<label>Phone *</label>
                        @if(Auth::user()->canEditLeadContact() || $lead->phone == '')
							<input type="text" id="phone" name="phone" value="{{ $lead->phone }}">
                        @else
                            {{ $lead->phone }}
                        @endif
						</li>
						<li>
							<label>Email *</label>

                        @if(Auth::user()->canEditLeadContact() || $lead->email == '')
							<input type="email" id="email" name="email" value="{{ $lead->email }}">
                        @else
                            {{ $lead->email }}

                        @endif

						</li>
						<li>
							<label>Alternate Email</label>

                        @if(Auth::user()->canEditLeadContact() || $lead->email_alt == '')
							<input type="email" id="email_alt" name="email_alt" value="{{ $lead->email_alt }}">
                        @else
                            {{ $lead->email_alt }}
                        @endif

						</li>
						<li>
							<label>Skype</label>
							<input type="text" id="skype" name="skype" value="{{ $lead->skype }}">
						</li>
						<li>
							<label>Address</label>
							<input type="text" id="address" name="address" value="{{ $lead->address }}">
						</li>
						<li>
							<label>Country</label>
							<div class="dropdown">
								<select id="country" onchange="selectState(this.options[this.selectedIndex].value)" name="country" disabled>
									<option value="">Select Country</option>
								</select>
						 	</div>
						</li>
						<li>
							<label>State/Region</label>
							<select id="state" onchange="selectCity(this.options[this.selectedIndex].value)" name="state" disabled>
		                        <option value="">Select State</option>
		                    </select>
						</li>
						<li>
							<label>City</label>
							<select id="city" name="city" disabled>
		                        <option value="">Select City</option>
		                    </select>
						</li>
						<li>
							<label>ZIP/PIN</label>
							<input type="text" id="zip" name="zip" value="{{ $lead->zip }}">
						</li>
					</ol>
					<div class="cod">
						{!! $cod !!}
					</div>
				</fieldset>				
				<div class="col-md-3">
					<button type="submit" id="edit" name="edit" class="btn btn-primary">Edit</button>
					<button id="save" type="submit" name="save" class="btn btn-success"> Save</button> 
					<button id="cancel" type="submit" name="cancel" class="btn btn-danger">Cancel</button>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">			
			</form>		
	</div>
	<!--end of primary address-->

	<!--start of  addresses tab-->
	<div role="tabpanel" class="tab-pane" id="addresses">
		<div class="container" style="margin-top:5px;">
		<a data-toggle="modal" data-target="#addressAdd" href="/lead/{{$lead->id}}/address/add" class="btn btn-primary btn-xs pull-right">Add New</a>
		</div>
		<ul class="list-group">
		@foreach($lead->addresses as $address)					  	
		  		<b style="display:block">{{$address->address_type}}</b>
		  		<li class="list-group-item col-sm-12">
		  			<a data-toggle="modal" data-target="#addressEdit" href="/address/{{$address->id}}/edit"><i class="fa fa-edit pull-right"></i></a>
		    		<ul class="col-sm-6">
  						<li><b>Name</b>:{{$address->name}}</li>
		    			<li><b>address</b>:{{$address->address}}, 
		    			{{$address->city}}, {{$regions->where('region_code',$address->state)->first()->region_name or ''}}, {{$countries->where('country_code',$address->country)->first()->country_name or ''}} - {{$address->zip}}</li>
					</ul>
					<ul class="col-sm-4">
					<div class="col-sm-12" style="height:2em;"></div>
					<div class="col-sm-12" style="border:solid 1px #e4c94b;background-color:#fff4c5;">
					{!!$address->cod!!}
					</div>
					</ul>					
		    	</li>
		@endforeach
		</ul>

	</div>
	
	</div>
	</div>

	@else
			<div class="blacklisted"></div>
		@endif
	@endif	
</div>

<!-- Modal Template-->
<div class="modal fade" id="addressAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Add Address</h4>

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


<!-- Modal Template-->
<div class="modal fade" id="addressEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Edit Address</h4>
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

<script type="text/javascript" src="/js/form.js"></script>
<script>
$('body').on('hidden.bs.modal', '.modal', function () {
  $(this).removeData('bs.modal');
}); 
</script>
<style type="text/css">
    #form li {
        margin: 15px;
        height: 25px;
    }
</style>
@endsection