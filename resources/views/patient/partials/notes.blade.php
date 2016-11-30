@extends('patient.index')
@section('top')
<div class="container1">	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">New Note</h2>
		</div>
		<div class="panel-body">
			<form method="POST" action="" role="form" class="form-inline" id="form">
				<textarea class="form-control" name="note" cols="50"></textarea>	
				<input type="hidden" name="_token" value="{{ csrf_token()}}">
				<button type="submit" class="btn btn-primary">Save</button>
			</form>
		</div>
	</div>
</div>
@endsection
@section('main')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">Notes</h2>
		</div>
		<div class="panel-body">
			<table class="table">
				<thead>
					<tr>
						<th>Note</th>
						<th>By</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>

				@foreach($notes as $note)
					<tr>
						<td>{{$note->text}}</td>
						<td>{{$note->created_by}}</td>
						<td>{{$note->created_at}}</td>
					</tr>
				@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#tag").append("<option value=''> Select Tag </option>");
    $.getJSON("/api/getTagList",function(result){
        $.each(result, function(i, field){
            $("#tag").append("<option value='" + field.id + "'> " + field.name + "</option>");
        });
    });
</script>
</script>
@endsection