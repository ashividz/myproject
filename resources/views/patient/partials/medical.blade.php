@extends('patient.index')
@section('main')
<div class="container1">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Medical</h3>
		</div>
		<div class="panel-body">
			<form class="form-inline" method="post">
				<table class="table table-bordered">
					<tr>
						<td>
							<label>Blood Group :</label>
							<select name="blood_group_id">
								<option></option>
							@foreach($blood_groups as $bg)
								<option value="{{$bg->id}}" {{$bg->id == $patient->blood_group_id ? 'selected' : ''}}>{{$bg->name}}</option>
							@endforeach
							</select>
							<select name="rh_factor_id">
								<option></option>
							@foreach($rh_factors as $rh)
								<option value="{{$rh->id}}" {{$rh->id == $patient->rh_factor_id ? 'selected' :''}}>{{$rh->code}}</option>
							@endforeach
							</select>
							({{$patient->blood_group}})
						</td>
						<td>
							<label>Constipation :</label> 
							<select name="constipation">
								<option></option>
								<option value="Yes" {{$patient->constipation == 'Yes' ? 'selected' : ''}}>Yes</option>
								<option value="No" {{$patient->constipation == 'No' ? 'selected' : ''}}>No</option>
							</select>
						</td>
						<td>
							<label>Gas :</label> 
							<select name="gas">
								<option></option>
								<option value="Yes" {{$patient->gas == 'Yes' ? 'selected' : ''}}>Yes</option>
								<option value="No" {{$patient->gas == 'No' ? 'selected' : ''}}>No</option>
							</select>
						</td>
						<td>
							<label>Water Retention :</label> 
							<select name="water_retention">
								<option></option>
								<option value="Yes" {{$patient->water_retention == 'Yes' ? 'selected' : ''}}>Yes</option>
								<option value="No" {{$patient->water_retention == 'No' ? 'selected' : ''}}>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label>Digestion Type :</label>
							<input type="text" name="digestion_type" value="{{$patient->digestion_type}}">
						</td>
						<td>
							<label>Allergic :</label>
							<input type="text" name="allergic" value="{{$patient->allergic}}">
						</td>
						<td>
							<label>Wheezing :</label> 
							<select name="wheezing">
								<option></option>
								<option value="Yes" {{$patient->wheezing == 'Yes' ? 'selected' : ''}}>Yes</option>
								<option value="No" {{$patient->wheezing == 'No' ? 'selected' : ''}}>No</option>
							</select>
						</td>
						<td>
							<label>Acidity :</label> 
							<select name="acidity">
								<option></option>
								<option value="Yes" {{$patient->acidity == 'Yes' ? 'selected' : ''}}>Yes</option>
								<option value="No" {{$patient->acidity == 'No' ? 'selected' : ''}}>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label>Family History of Diseases : </label>
							<input type="text" name="diseases_history" value="{{$patient->diseases_history}}">
						</td>
						<td colspan="2">
							<label>Energy Level : </label>
							<input type="text" name="energy_level" value="{{$patient->energy_level}}">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label>Menstrual History : </label>
							<input type="text" name="menstural_history" value="{{$patient->menstural_history}}">
						</td>
						<td>
							<label>BP (Systolic)</label>
							<input type="text" name="bp_high" value="{{$patient->bp_high}}" size="3">
						</td>
						<td>
							<label>BP (Diastolic)</label>
							<input type="text" name="bp_low" value="{{$patient->bp_low}}" size="3">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<label>Diagnosis : </label>
							<input type="text" name="diagnosis" value="{{$patient->diagnosis}}" size="100">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<label>Present Medical Problem	 : </label>
							<input type="text" name="medical_problem" value="{{$patient->medical_problem}}" size="100">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<label>Any previous Weight Loss with reason	: </label>
							<input type="text" name="previous_weight_loss" value="{{$patient->previous_weight_loss}}" size="70">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<label>Past Medical History	: </label>
							<input type="text" name="medical_history" value="{{$patient->medical_history}}" size="100">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<label>Sweet Tooth	: </label>
							<input type="text" name="sweet_tooth" value="{{$patient->sweet_tooth}}" size="100">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<label>Routine Diet	: </label>
							<input type="text" name="routine_diet" value="{{$patient->routine_diet}}" size="100">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<label>Special Food Remark	: </label>
							<input type="text" name="special_food_remark" value="{{$patient->special_food_remark}}" size="100">
						</td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<button type="submit" class="btn btn-primary">Save</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>

@endsection