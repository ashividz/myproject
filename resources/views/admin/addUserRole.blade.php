<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/addUserRole') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					
						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Display Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="display_name">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Description</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="description">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" id="save" class="btn btn-success">Save</button>

								<button type="button" id="cancel" onclick="location.href='/admin/viewUserRoles';" class="btn btn-warning">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>