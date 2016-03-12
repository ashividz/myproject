<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Add Herb</h4>
		</div>
		<div class="panel-body">
			<form id="form" method="POST" class="form">
				<div class="col-md-4">					
					<div class="form-group">
						<input type="text" name="herb" id="herb" placeholder="Herb">
					</div>
					<div class="form-group">
						<button type='submit' class='btn btn-primary'>Submit</button>
						<button type='reset' class='btn btn-danger'>Cancel</button>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</div>
				<div class="col-md-4">		
				@if (count($errors) > 0)
				    <div class="alert alert-danger form-group">
				    	<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
				        <ul>
				            @foreach ($errors->all() as $error)
				                <li>{{ $error }}</li>
				            @endforeach
				        </ul>
				    </div>
				@endif
				</div>
			</form>

		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Herbs</h4>
		</div>
		<div class="panel-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Herb</th>
					</tr>				
				</thead>
				<tbody>

		@foreach($herbs as $herb)
					<tr>
						<td>{{$i++}}</td>
						<td><span class='editable_herb' id='{{ $herb->id }}'>{{$herb->name or ""}}</span></td>
					</tr>
			@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$(".editable_herb").editable("/herb/update", { 
      	type      : "text",
      	submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      	cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      	placeholder: '<span class="placeholder">(Edit)</span>',
    });

});
</script>