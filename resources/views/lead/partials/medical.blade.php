@extends('lead.index')
@section('main')
<script type="text/javascript" src="/js/modals/herb.js"></script>
<script type="text/javascript" src="/js/modals/mealtime.js"></script>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Herbs
				@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('doctor'))
					<span class="pull-right fa fa-plus-circle blue" id="addHerb" value="{{$lead->id}}"></span>
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
					<td>{{$i++}}</td>
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
					</td>
				</tr>
		@endforeach

			</tbody>
		</table>
		</div>
	</div>
</div>
@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('service'))
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
	                        location.reload();
	                     });
	                }, 3000);
	           }
	        });
        };
	};
</script>
@endif
@endsection