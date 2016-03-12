@extends('patient.index')
@section('top')
<div class="container1">	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">Tag</h2>
		</div>
		<div class="panel-body">
			<div class="col-md-8">
				<form method="POST" action="" role="form" id="form">
					<div class="form-group">
						<select name="tag" id="tag">
						</select>					
					</div>	
					<div class="form-group">
						<textarea name="note" cols="60" placeholder="Write Note"></textarea>
					</div>
					
					<input type="hidden" name="_token" value="{{ csrf_token()}}">
					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>
			<div class="col-md-4">
				@if (count($errors) > 0)
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif
			</div>			
		</div>
	</div>
</div>
@endsection
@section('main')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">Tag</h2>
		</div>
		<div class="panel-body">
			<table class="table">
				<thead>
					<tr>
						<th>Tag</th>
						<th>Note</th>
						<th>Tagged By</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>

				@foreach($tags as $tag)
					<tr>
						<td>{{$tag->tag->name or ""}}</td>
						<td>{{$tag->note}}</td>
						<td>{{$tag->created_by}}</td>
						<td>{{$tag->created_at}}</td>
					</tr>
				@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#tag").append("<option value=''> Select Tag *</option>");
    $.getJSON("/api/getTagList",function(result){
        $.each(result, function(i, field){
            $("#tag").append("<option value='" + field.id + "'> " + field.name + "</option>");
        });
    });
</script>
</script>
@endsection