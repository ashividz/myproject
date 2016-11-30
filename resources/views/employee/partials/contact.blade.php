@extends('hr/employee')

@section('main')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Contact Details</h4>
		</div>
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

					<form class="form-horizontal" role="form" method="POST">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $employee->id }}">

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ $employee->email }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Mobile</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="mobile" value="{{ $employee->mobile }}">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Update
								</button>
							</div>
						</div>
					</form>
		</div>
	</div>
</div>
fasfsad
@endsection
