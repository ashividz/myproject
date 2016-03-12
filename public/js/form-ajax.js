$(document).ready(function() 
{
	$("#form").submit(function(event){
		event.preventDefault();
		var url = $("#form").attr('action');
		$.ajax(
        {
           type: "POST",
           url: url,
           data: $("#form").serialize(),
           success: function(data)
           {
               $('#alert').show();
               $('#alert').empty().append(data);
               	setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        
                    });
                }, 3000);
           },
           error : function(data) {
           		var errors = data.responseJSON;

        		console.log(errors);

           		$('#alert').show();
               	$('#alert').empty();
               	$.each(errors, function(index, value) {
		            $('#alert').append("<li>"+value+"</li>");
		        });

               	setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        //location.reload();
                     });
                }, 3000);
           }
        });
	});
});