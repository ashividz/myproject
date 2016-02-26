@extends('patient.index')
@section('main')
<script type="text/javascript" src="/js/modals/herb.js"></script>
<script type="text/javascript" src="/js/modals/mealtime.js"></script>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Herbs
				@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('doctor'))
					<span class="pull-right fa fa-plus-circle" id="addHerb" value="{{$patient->id or ''}}"></span>
				@endif
			</h3>
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
						<th>Date</th>
						<th>Doctor</th>
						<th>Active</th>
					</tr>				
				</thead>
				<tbody>

		@foreach($patient->herbs as $herb)
					<tr>
						<td></td>
						<td>{{$herb->herb->name or ""}}</td>
						<td>{{$herb->quantity or ""}}</td>
						<td>{{$herb->unit->short_name or ""}}</td>
						<td>{{$herb->remark or ""}}</td>
						<td width="15%">
					@foreach($herb->mealtimes AS $mealtime)	
								<i class="fa fa-check-square"></i> {{$mealtime->mealtime->name or ""}}<br>
					@endforeach	
							
						@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('doctor'))
							<span class="pull-right fa fa-plus-circle blue mealtime" value="{{$herb->id}}"></span>
						@endif

						</td>
						<td>{{date('jS M, Y h:i A', strtotime($herb->created_at))}}</td>
						<td>Dr. {{$herb->created_by}}</td>
						<td>
							<input type="checkbox" name="active" value="{{$herb->id}}" class="switch" data-size="mini" data-off-color="danger" {{$herb->deleted_at == NULL ?'checked':''}}>

							@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('seervice'))
								<div class="pull-right">
									<a href="#" id="{{$herb->id}}" onclick="deleteHerb(this.id)"><i class="fa fa-remove red"></i></a>
								</div>
							@endif
							<p>{{$herb->deleted_at? date('jS M, Y', strtotime($herb->deleted_at)) :''}}
						</td>
					</tr>
			@endforeach

				</tbody>
			</table>
		</div>
	</div>
@endsection
@section('top')
	<div class="panel panel-default">
		<div class="panel-heading">

			<form id="form-template-select">
				<select name="template" id="template-select">
					<option value="Select Herb">Select Template</option>
	@foreach($templates as $template)
					<option value="{{$template->id}}">{{$template->herb->name}}</option>
	@endforeach

				</select>		
			</form>
		</div>
		<div class="panel-body">
			<form id="form-template" class="form-inline">
				<div class="form-group">
					<input type="hidden" name="herb" id="herb_id" value="">
					<input type="text" name="template_name" id="herb_name" placeholder="Herb" value="" readonly>
				</div>
				<div class="form-group">
					<input type="text" name="quantity" id="quantity" size="3" placeholder="Quantity" value="">
				</div>

				<div class="form-group">
					<select id="unit" name="unit">
					</select>
				</div>
				<div class="form-group">
					<textarea name="remark" id="remark" placeholder="Remark"></textarea>
				</div>
				<div class="form-group">
					<select id="mealtime" name="mealtimes[]" multiple required>
					</select>
				</div>
				<div class="form-group">
					<input type="hidden" name="id" value="{{ $patient->id }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<button class="btn btn-primary">Save Herb</button>
				</div>		
			</form>
		</div>
	</div>
</div>
@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('doctor'))
<script type="text/javascript">

	//Bootstrap Switch 
	$('input[name="active"]').bootstrapSwitch();
	
	$('input[name="active"]').on('switchChange.bootstrapSwitch', function(event, state) {

		var url = "/patient/herb/" + this.value + "/update"; //
        $.ajax(
        {
           type: "POST",
           url: url,
           data: {state : state, "_token" : "{{ csrf_token()}}" }, // send Source Id.
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
           	error: function(data) {
			    var errors = data.responseJson;

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

	function deleteHerb(id) {

	    var r=confirm("Are you sure you want to delete?");
    	if (r==true){
            var url = "/patient/herb/" + id + "/delete"; //
	        $.ajax(
	        {
	           type: "POST",
	           url: url,
	           data: {"_token" : "{{ csrf_token()}}"}, // send Source Id.
	           success: function(data)
	           {
	               $('#alert').show();
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
        };
	};

	$("#form-template").hide();

	$("#template-select").on('change', function(event) {

	    event.preventDefault();
	    /* stop form from submitting normally */
	    var url = "/api/template/"+this.value;
	    $.ajax(
        {
           	type: "get",
           	url: url,
           	success: function(data)
           	{
               	$("#form-template").show();
               	$("#form-template select").find('option').remove();

               	$("#herb_id").val(data.herb_id);
               	$("#herb_name").val(data.herb.name);
               	$("#quantity").val(data.quantity);
               	$("#remark").val(data.remark);

               	$.each(data.units, function(i, field){
		            if (field.id == data.unit_id) {
		                $("#unit").append("<option value='" + field.id + "' selected> " + field.name + "</option>");
		            }
		            else
		            {
		                $("#unit").append("<option value='" + field.id + "'> " + field.name + "</option>");
		            }       
		        });

		        $.each(data.mealtime, function(i, field){

		        	var selected = '';
		        	
		        	$.each(data.mealtimes, function(i, m){
		        		if (field.id == m.mealtime_id) {
			               selected = 'selected';
			            }
		        	});

		        	$("#mealtime").append("<option value='" + field.id + "' "+selected+"> " + field.name + "</option>");
		        });

		        $("#mealtime").attr("size",$("#mealtime option").length);
           	}
        });
	});
	
	$("#form-template").submit(function(event){
		event.preventDefault();
		var url = '/patient/saveHerb';
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
           }
        });
	});
</script>
@endif
<style type="text/css">
	#form-template .form-control{
		padding: 0;
		border: none;
	}
</style>
@endsection