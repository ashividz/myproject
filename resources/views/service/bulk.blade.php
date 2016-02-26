<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right">
			@include('nutritionist/partials/users')
		</div>
		<h4>Bulk Email & SMS</h4>
	</div>
	<div class="panel-body">
		<form action="/service/bulk/send" id="form-bulk">
			<table id="primary_table" class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th><input type='checkbox' id='selectallemail'> Email</th>
						<th><input type='checkbox' id='selectallsms'> SMS</th>
						<th width="13%">Name</th>
						<th>City</th>
						<th>State</th>
						<th>Country</th>
					</tr>
				</thead>
				<tbody>

				@foreach($patients as $patient)	
					<tr>
						<td>{{$i++}}</td>
						<td>
					@if($patient->lead->email <> '' || $patient->lead->email_alt <> '')
							<input type="checkbox" name="email[]" value="{{$patient->lead_id}}" class="checkemail">
					@endif
						</td>
						<td>
					@if($patient->lead->country == 'IN')
						<input type="checkbox" name="sms[]" value="{{$patient->lead_id}}" class="checksms">
					@endif
						</td>
						<td><a href="/patient/{{$patient->id}}/diets" target="_blank">{{$patient->lead->name}}</a></td>

						<td>{{$patient->lead->city}}</td>
						<td>{{$patient->lead->region->region_name or ""}}</td>
						<td>{{$patient->lead->m_country->country_name or ""}}</td>
					</tr>
				@endforeach

				</tbody>
			</table>

			<div class="container">
				<div class="col-md-4">
					<select name="template_id">
						<option>Select Template</option>

					@foreach($templates AS $template)
						<option value="{{$template->id}}">{{$template->subject}}</option>
					@endforeach

					</select>
				</div>
				<div class="col-md-8">
					<button class="btn btn-primary">Send</button>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</div>
			</div>
			<div class="container">
				<div class="panel panel-default">
					<div class="panel-body">

					@foreach($templates AS $template)
						<div id="{{$template->id}}" class="template" style="display:none">
							<h5>Email Content :</h5>
							<div>
							{!!Helper::nl2p($template->email)!!}</div>
							<ul>
							@foreach($template->attachments AS $attachment)
								<li><img class="attachment" src="/images/cleardot.gif"> {{$attachment->name}}</li>
							@endforeach
							</ul>
							<hr>
							<h5>SMS Content :</h5>
							{{$template->sms}}
						</div>
					@endforeach

					</div>
				</div>
			</div>
		</form>
	</div>	
</div>

<script type="text/javascript">
	$(document).ready(function() {
	    $('#selectallemail').click(function(event) {  //on click 
	        if(this.checked) { // check select status
	            $('.checkemail').each(function() { //loop through each checkbox
	                this.checked = true;  //select all checkboxes with class "checkbox1"               
	            });
	        }else{
	            $('.checkemail').each(function() { //loop through each checkbox
	                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
	            });         
	        }
	    });
	    $('#selectallsms').click(function(event) {  //on click 
	        if(this.checked) { // check select status
	            $('.checksms').each(function() { //loop through each checkbox
	                this.checked = true;  //select all checkboxes with class "checkbox1"               
	            });
	        }else{
	            $('.checksms').each(function() { //loop through each checkbox
	                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
	            });         
	        }
	    });
	    
	});

	$("#form-bulk").submit(function(event) {
	    event.preventDefault();
	    /* stop form from submitting normally */

        var url = $("#form-bulk").attr('action'); // the script where you handle the form input.
        $.ajax(        {
           	type: "POST",
           	url: url,
           	data: $("#form-bulk").serialize(), // serializes the form's elements.
           	beforeSend: function () { 
           		$('#alert').show();
           		$('#alert').empty().append('<i class="fa fa-spinner fa-spin fa-5x"></i>');
           	},
         	complete: function () { 
         		
         	},
           	success: function(data)
           	{	
               	//$('#alert').show();
               	$('#alert').empty().append(data);
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
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$("select").change(function(){
			$('.template').hide();
			$('#' + this.value).show();
		});

	});
</script>