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