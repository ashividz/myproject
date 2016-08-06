@include('partials/header')
<!-- STEP 1 : Lead Upload Form Begin-->
<div class="span6" id="form-login">
	<form class="form-horizontal well" action="" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Import CSV/Excel file</legend>
			<div class="control-group">
				<div>
					<label>select carrier</label>
				</div>
				<div class="controls">
					<select name="carrier_id">
					@foreach($carriers as $carrier)
					 <option value="{{$carrier->id}}">{{$carrier->name
					 }}</option>
					@endforeach
					</select>
				</div>				
				<div>
					<label>CSV/Excel File:</label>
				</div>
				<div class="controls">
					<input type="file" name="file" id="file" class="input-large" required>
				</div>
			</div>
			<hr>
			<div class="control-group">
				<div class="controls">
				<button type="submit" id="upload" name="upload" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</fieldset>
	</form>
</div>	