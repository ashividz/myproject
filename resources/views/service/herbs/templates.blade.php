<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Add Herb Template</h4>
		</div>
		<div class="panel-body">
			<form id="form-template" method="POST" class="form">
				<div class="form-group">
					<select name="herb" id="herb">
						<option value="">Select Herb</option>
					
					@foreach($herbs AS $herb)
						<option value="{{$herb->id}}">{{$herb->name}}</option>
					@endforeach

					</select>
				</div>
				<div class="form-group">
					<input type="text" name="quantity" id="quantity" size="3" placeholder="Quantity">
					<select name="unit" id="unit">
						<option value="">Select Unit</option>
					
					@foreach($units AS $unit)
						<option value="{{$unit->id}}">{{$unit->name}}</option>
					@endforeach

					</select>
				</div>
				<div class="form-group">
					<textarea name="remark" cols="30"></textarea>
				</div>
				<div class="form-group">
					<select name="mealtimes[]" id="mealtimes" multiple size='7'>						
					@foreach($mealtimes AS $mealtime)
						<option value="{{$mealtime->id}}">{{$mealtime->name}}</option>
					@endforeach

					</select>
				</div>
				<div class="form-group">
					<button type='submit' class='btn btn-primary'>Submit</button>
					<button type='reset' class='btn btn-danger'>Cancel</button>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</div>
			</form>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Herb Templates</h4>
		</div>
		<div class="panel-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Herb</th>
						<th>Quantity</th>
						<th>Unit</th>
						<th>Remark</th>					
						<th>Mealtimes</th>
						<th></th>
					</tr>				
				</thead>
				<tbody>

		@foreach($templates as $template)
					<tr>
						<td>{{$i++}}</td>
						<td>{{$template->herb->name or ""}}</td>
						<td>{{$template->quantity or ""}}</td>
						<td>{{$template->unit->short_name or ""}}</td>
						<td>{{$template->remark or ""}}</td>
						<td width="15%">
					@foreach($template->mealtimes AS $mealtime)	
								<i class="fa fa-check-square"></i> {{$mealtime->mealtime->name or ""}}<br>
					@endforeach	
						</td>
						<td><i class="fa fa-info-circle"></i></td>
					</tr>
			@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>		
<script type="text/javascript">
	$("#form-template").submit(function(event){
		event.preventDefault();
		var url = '/service/herb/template/add';
		$.ajax(
        {
           type: "POST",
           url: url,
           data: $("#form-template").serialize(),
           success: function(data)
           {
               $('#alert').show();
               $('#alert').empty().append(data);
               	setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        location.reload();
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
</script>
			