<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>{{$user->employee->name}}</h4>
		</div>
		<div class="panel-body">
			<div class="container">
				<div class="col-md-4">
					<form id="form" class="form-horizontal"	action="/admin/user/{{$user->id}}/role" method="post">
						<div class="form-group">
							<select name="role" class="form-control col-md-2">
								<option value="">Select Role</option>
							@foreach($roles AS $role)
								<option value="{{$role->id}}">{{$role->display_name}}</option>
							@endforeach
							</select>
						</div>
						<div class="form-group">
							<button type="submit" id="add_user" class="btn btn-success">Add</button>
						</div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</form>
				</div>
				<div>					
					<table class="table striped">
						<thead>
							<tr>
								<th>Role Name</th>
								<th>Display Name</th>
								<th>Description</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						
						@foreach ($user->roles as $role)
							<tr>
								<td>{{$role->name}}</td>
								<td>{{$role->display_name}}</td>
								<td>{{$role->description}}</td>
								<td><a href="#" id="{{$role->pivot->id}}" class="delete-role" style=""><i class="red fa fa-close"></i></a></td>
							</tr>
						@endforeach

						</tbody>
					</table>
				</div>
					
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('.delete-role').on('click', function(){

	    var r=confirm("Are you sure you want to delete?");
    	if (r==true){
            var url = "/user/role/delete"; //
            id = this.id;
	        $.ajax(
	        {
	           type: "POST",
	           url: url,
	           data: {id : id, _token : '{{ csrf_token() }}'}, // send Source Id.
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
	});
});	
</script>