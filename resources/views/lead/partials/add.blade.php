<div class="col-md-6 col-md-offset-3">

	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">New Lead</h2>
		</div>
		<div class="panel-body">
	        <hr>
			<form method="POST" action="/lead/saveLead" role="form" class="form-inline" id="form">
				<fieldset>
					<ol>
						<li>
							<label>Name *</label>
							<input type="text" id="name" name="name" required>
						</li>
						<li>
							<label>Gender *</label>
							<input type="radio" name="gender" id="gender" value="F" required> Female &nbsp;
								<input type="radio" name="gender" id="gender" value="M"> Male
						</li>
						<li>
							<label>Mobile *</label>
							<input type="text" id="mobile" name="mobile" value="{{ $mobile }}" required>
						</li>
						<li>
							<label>Email</label>
							<input type="email" id="email" name="email">
						</li>
						<li>
							<label>Height (cms)</label>
							<input type="text" id="height" name="height">
						</li>
						<li>
							<label>Weight (Kgs) </label>
							<input type="text" id="weight" name="weight">
						</li>
						<li>
							<label>Lead Source *</label>
							<div class="dropdown">
								<select id="source" name="source" required>
								</select>
						 	</div>
						</li>
						<li>
							<label>How did you hear about us? *</label>
							<div class="dropdown">
								<select id="voice" name="voice" required>
								</select>
						 	</div>
						</li>
						<li>
							<label>Remarks *</label>
							<textarea size="3" class="form-control" type="text" id="remark" name="remark" style="width:225px"></textarea>
						</li>
						<li>
							<label>Address</label>
							<input type="text" id="address" name="address">
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
							<label>ZIP/PIN</label>
							<input type="text" id="zip" name="zip">
						</li>
					</ol>
				</fieldset>
				<div class="col-md-3">
					<button id="save" type="submit" name="save" class="btn btn-success"> Save</button>
				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
		</div>
	</div>

</div>


<script type="text/javascript">
$(document).ready(function()
{
	//Fetch Lead Voices
    $("#voice").append("<option value=''> Select Voice </option>");
    $.getJSON("/api/getVoiceList",function(result){
        $.each(result, function(i, field){
            if ("{{$mobile}}" == "OBD" && field.id == 6) {
            	$("#voice").append("<option value='" + field.id + "' selected> " + field.name + "</option>");
            }
            else
            {
            	$("#voice").append("<option value='" + field.id + "'> " + field.name + "</option>");
            }
        });
    });

    //Fetch Lead Source
    $("#source").append("<option value=''> Select Source </option>");
    $.getJSON("/api/getSourceList",function(result){
        $.each(result, function(i, field){
        	if ("{{$mobile}}" == "OBD" && field.id == 30) {
                $("#source").append("<option value='" + field.id + "' selected> " + field.source_name + "</option>");
            }
            else if ("{{$mobile}}" !== "" && field.id == 17) {
            	$("#source").append("<option value='" + field.id + "' selected> " + field.source_name + "</option>");
            }
            else if (field.id == 12 || field.id == 13 || field.id == 16 || field.id == 17 || field.id == 26 || field.id == 95 || field.id == 100 || field.id == 102 || field.id == 103 || field.id == 107) {
                $("#source").append("<option value='" + field.id + "'> " + field.source_name + "</option>");
            }
            @if( Auth::user()->hasRole('admin') || Auth::user()->hasRole('b2b'))
            //for corporate leads
            if ( field.channel_id==5 || field.channel_id==6 || field.channel_id==7 ) {
                $("#source").append("<option value='" + field.id + "' selected> " + field.source_name + "</option>");
            }
            @endif

        });
    });


    $("#country").empty();
    $("#country").append("<option value=''> Select Country </option>");
    $.getJSON("/api/getCountryList",function(result){
        var country = "IN";
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
    $("#city").empty();
    $("#city").append("<option value=''> Select City </option>");
    getRegionCode(country_id);
}

/*This function is called when state dropdown value change*/
function selectCity(state_id){
    getCityCode(state_id);
}


function getRegionCode(country_id) {
	var state = "IN.07";
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
    $.getJSON("/api/getCityList", { region_code: state_id }, function(result){
        $("#city").empty();
        $("#city").append("<option value=''> Select City </option>");
        $.each(result, function(i, field) {
        	$("#city").append("<option value='" + field.city_name + "'> " + field.city_name + "</option>");
        });
    });

}

</script>
