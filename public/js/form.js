$(document).ready(function() 
{ 	
	$("#form input").attr('disabled', true);
	$('#form-fields').hide();
    $('#cancel').hide();
    $('#save').hide();
    $('#alert').hide();
	var form = $("#form");
	$('#edit').click(function(event) 
	{
	    event.preventDefault();
	    form.find(':disabled').each(function() 
	    {
	        $(this).removeAttr('disabled');
	    });

		$('#form-fields').show();
	    $('#edit').hide();
	    $('#cancel').show();
	    $('#save').show();
    	$('#alert').hide();
	});
	 
	$('#cancel').click(function(event) {
	    event.preventDefault();
	    form.find(':enabled').each(function() {
	        $(this).attr("disabled", "disabled");
	    });

		$('#form-fields').hide();
	    $('#edit').show();
	    $('#add').show();
	    $('#cancel').hide();
	    $('#save').hide();
	    $('#edit').prop("disabled", false);
    	$('#alert').hide();
	});
	 
	$("#form").submit(function(event) {


	    event.preventDefault();
	    /* stop form from submitting normally */

	    
	    $('#edit').prop("disabled", false);
        var url = $("#form").attr('action'); // the script where you handle the form input.
        $.ajax(
        {
           type: "POST",
           url: url,
           data: $("#form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               //alert(data);
               $('#alert').show();
               $('#alert').empty().append(data);
               setTimeout(function()
               	{
			     	$('#alert').slideUp('slow').fadeOut(function() 
			     	{
			         	location.reload();
			         });
				}, 3000);
           }
        });

		$('#form-fields').hide();
        $('#edit').show();
	    $('#cancel').hide();
	    $('#save').hide();
	    form.find(':enabled').each(function() 
	    {
	        $(this).attr("disabled", "disabled");
	    });
	    $('#edit').prop("disabled", false);
    	$('#alert').hide();
        return false; // avoid to execute the actual submit of the form.
	});			
});