<div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="panel-title">Add Address</div>
        </div>
        <div class="panel-body">
	  	<form class="form-horizontal" role="form" method="post" action="/lead/{{$lead->id}}/address/save">
	  		{{csrf_field()}}
	  		<div class="form-group">
	    	<div class="col-sm-2">
		      		<label class="control-label input-sm" for="address_type"> type:</label>
		    </div>
		    <div class="col-sm-4">	    	
		    	<select class="form-control input-sm" id="address_type" name="address_type" required>
							<option value="">Select address type</option>
							<option value="home">home</option>
							<option value="office">office</option>
							<option value="other" id="other_value">other</option>
				</select>
			</div>	
			<div class="col-sm-6">
			<span id="other_address_type_container"></span>	      		
	    	</div>
	    	</div>	    	
	  		<div class="form-group">
		      	<div class="col-sm-2">
		      		<label class="control-label input-sm" for="name">Name:</label>
		      	</div>
		      	<div class="col-sm-8">
		        	<input type="text" class="form-control input-sm" id="address_name" name="name" placeholder="Enter name" required />
		      	</div>
	    	</div>	    	
	    	<div class="form-group">
		      	<div class="col-sm-2">
		      		<label class="control-label input-sm" for="address">Addrees:</label>
		      	</div>
			     <div class="col-sm-8">
		        	<input type="text" class="form-control input-sm" id="address_address" name="address" placeholder="address" required>
		      	</div>
	    	</div>
	    	<div class="form-group">
		      	<div class="col-sm-2">
		      		<label class="control-label input-sm" for="country">Country:</label>
		      	</div>
			    <div class="col-sm-8">
		        	<select class="form-control input-sm" id="address_country" name="country" required>
						<option value="">Select Country</option>
					</select>
		      	</div>
	    	</div>
	    	<div class="form-group">
		      	<div class="col-sm-2">
		      		<label class="control-label input-sm" for="state" >State:</label>
		      	</div>
			    <div class="col-sm-8">
		        	<select class="form-control input-sm" id="address_state" name="state" required>
						<option value="">Select State</option>
					</select>
		      	</div>
	    	</div>
	    	<div class="form-group">
		      	<div class="col-sm-2">
		      		<label class="control-label input-sm" for="city">City:</label>
		      	</div>
			    <div class="col-sm-8">
		        	<select class="form-control input-sm" id="address_city" name="city" required>
						<option value="">Select City</option>
					</select>
		      	</div>
	    	</div>
	    	<div class="form-group">
		      	<div class="col-sm-2">
		      		<label class="control-label input-sm" for="zip">Zip/Pin:</label>
		      	</div>
			    <div class="col-sm-4">
		        	<input type="text" class="form-control input-sm" id ="address_zip"name="zip" placeholder="zip" required>
		      	</div>

	    	</div>
	    	<button class="btn btn-primary btn-xs" type="submit" value="Submit">Submit</button>


	   </form>
</div>
</div>	
<script>
$(document).ready(function() 
{     
    $("#address_country").empty();
    $("#address_country").append("<option value=''> Select Country </option>");
    $.getJSON("/api/getCountryList",function(result){        
        $.each(result, function(i, field){            
                $("#address_country").append("<option value='" + field.country_code + "'> " + field.country_name + "</option>");            
        });        
    });

});

$( "#address_country" ).change(function() {
  	country_id = this.value;
  	$("#address_city").empty();
    $("#address_city").append("<option value=''> Select City </option>");    
    getRegionCode(country_id);    
});

$( "#address_state" ).change(function() {
  	state_id = this.value;
  	getCityCode(state_id);    
});

function getRegionCode(country_id) {
	<?php //var state = "{{ $lead->state?$lead->state:'' }}".toUpperCase();?>	
    $.getJSON("/api/getRegionList", { country_code: country_id }, function(result){
        $("#address_state").empty();
        $("#address_state").append("<option value=''> Select State </option>");
        $.each(result, function(i, field) {            
        	$("#address_state").append("<option value='" + field.region_code + "'> " + field.region_name + "</option>");
            });
        });

    //getCityCode(state);    
}

function getCityCode(state_id) {
	$.getJSON("/api/getCityList", { region_code: state_id }, function(result){
        $("#address_city").empty();
        $("#address_city").append("<option value=''> Select City </option>");
        $.each(result, function(i, field) {           
        	$("#address_city").append("<option value='" + field.city_name + "'> " + field.city_name + "</option>");            
        });
    });
}

<?php
$existing_address_types = '[';
foreach ($lead->addresses as $address) {
	$existing_address_types .= '"'.$address->address_type.'",';	
}
$existing_address_types .=']';
?>

var existing_address_types = {!!$existing_address_types!!};
console.log(existing_address_types);

$("#address_type").change(function() {
  	address_type = this.value;
  	if( $.inArray(address_type,existing_address_types) != -1 ){
  		alert('one address with this tag already exists.Please add a new one.');
  		$("#address_type").val('');
  	}
  	if(address_type == 'other'){
		$("#other_value").attr("value","other");
		$('#other_address_type_container').html("<small>Please specify</small><input type='text' id='address_type_other' required/>");
	}
  	else{
  		$('#other_address_type_container').html('');
  	}
});
$('body').on('change','#address_type_other', function() {		
	address_type = $("#address_type_other").val();	
	if( $.inArray(address_type,existing_address_types) != -1 ){
  		alert('one address with this tag already exists.Please add a new one.');
  		$("#address_type_other").val('');	
  		$("#other_value").attr("value","other");
  	}
  	else
		$("#other_value").attr("value",address_type);
});

</script>