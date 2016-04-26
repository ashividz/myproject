@extends('message.index')
@section('main')
<div class="container1">
	<div class="col-md-6">			
		<div class="panel panel-default">
			<div class="panel-heading"><span class="panel-title">Compose New Message</span></div>
			<div class="panel-body">
				<form id="form" method="post">
					<table class="table">
						<tr>
							<td>
								<select class="recipient form-control" placeholder="Recipients" multiple="multiple" name="recipients[]">

							@foreach($users as $user)
									<option value="{{$user->name}}">{{$user->name}}</option>
							@endforeach
								
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<input class="form-control" type="text" name="subject" placeholder="Subject">
							</td>
						</tr>
						<tr>
							<td>
								<textarea name="body" placeholder="Message" cols="70"></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<button class="btn btn-primary" type="submit">Send</button>
								<input type="hidden" name="_token" value="{{csrf_token()}}">
							</td>
						</tr>						
					</table>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() 
{ 
	$(".recipient").select2();

    $("#form").submit(function(event) {

        event.preventDefault();
        /* stop form from submitting normally */
        var url = "/message/send";
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
                }, 300000);
           }
        });

        $('#alert').hide();
        return false; // avoid to execute the actual submit of the form.
    });         
});
</script>
@endsection