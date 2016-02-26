<style type="text/css">
	table th {
		padding: 5px;
	}
	table td {
		padding: 5px;
	}
	#modal-container {
		width: 800px !important;
	}
</style>
<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<div class="container">
			<form action='#' style='display:none'>
			<table>
				<tr>
					<th colspan="4">
						<h2 style="margin:0px 20px 20px 30px;">Registration Process</h2>
					</th>
				</tr>
				<tr>
					<th>Name :</th>
					<td>{{$lead->name}}</td>
					<th>Gender :</th>
					<td>{{$lead->gender}}</td>
				</tr>
				<tr>
					<th>Email : </th>
					<td>{{$lead->email}}</td>
					<th>Phone : </th>
					<td>{{$lead->phone}}</td>
				</tr>
				<tr>
					<th>Country : </th>
					<td>{{$lead->m_country->country_name}}</td>
					<th>State</th> 
					<td>{{$lead->region->region_name}}</td>
				</tr>
				<tr>
					<th>City : </th>
					<td>{{$lead->city}}	</td>
					<th>PIN : </th>
					<td>{{$lead->zip}}	</td>
				</tr>
				<tr>
					<th>Source : </th>
					<td>{{$lead->source->source_name}}</td>
					<th>CRE : </th>
					<td>{{$lead->cre->cre}}</td>
				</tr>
				<tr>
					<th>Amount : </th>
					<td>
						<input type="text" name="amount" id="amount">
					</td>
					<th>Payment Mode : </th>
					<td>
						<select name="mode" id="mode">
							<option value="">Select Payment Mode</option>
						
						@foreach($modes AS $mode)
							<option value="{{$mode->id}}">{{$mode->name}}</option>
						@endforeach

						</select>
					</td>
				</tr>
				<tr>
					<th>Duration : </th>
					<td>
						<select name="duration" id="duration">
							<option value="">Select Duration</option>
							<option value="1">1 month</option>
							<option value="3">3 months</option>
							<option value="6">6 months</option>
							<option value="12">12 months</option>
						</select>
					</td>
					<th>Discount : </th>
					<td><input type="text" maxlength="3" size="3" name="discount"></td>
				</tr>
				<tr>
					<th>Remark : </th>
					<td colspan="3">
						<textarea cols="70"></textarea>
					</td>
				</tr>
				
			</table>

			<div style="text-align:center; margin-top:10px;">

	@if(trim($lead->name) == "" || trim($lead->phone) == "" || trim($lead->email) == "" || trim($lead->country) == "" || trim($lead->state) == "" || !$lead->cre || !$lead->source_id)

				<button class='modal-cancel modal-button simplemodal-close' disabled>Incomplete details</button>
	@else

				<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
	@endif
				<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
				<br/>
				<input type='hidden' name='id' value='{{ $lead->id }}'/>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</form>
	</div>