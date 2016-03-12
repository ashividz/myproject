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
			<form style='display:none'>
				<table width="100%">
					<tr>
						<th colspan="4">
							<h2 style="margin:0px 20px 20px 30px;">Diet Edit</h2>
						</th>
					</tr>
					<tr>
						<td>{{$patient->lead->name}}</td>
						<td><input type="text" name="date" id="date" size="10" value="{{date('m-d-Y', strtotime($diet->date_assign))}}" readonly></td>
						<td></td>
					</tr>
					<tr>
						<th>Breakfast</th>
						<th>Mid Morning</th>
						<th>Lunch</th>
					</tr>
					<tr>			
						<td><textarea name="breakfast" id="breakfast" cols="30">{{ $diet->breakfast}}</textarea></td>					
						<td><textarea name="mid_morning" id="mid_morning" cols="30">{{ $diet->mid_morning}}</textarea></td>				
						<td><textarea name="lunch" id="lunch" cols="30">{{ $diet->lunch}}</textarea></td>	
					</tr>
					<tr>
						<th>Evening</th>
						<th>Dinner</th>
						<th>Herbs</th>
						<th>Remarks/Dev</th>
					</tr>
					<tr>				
						<td><textarea name="evening" id="evening" cols="30">{{ $diet->evening}}</textarea></td>
						<td><textarea name="dinner" id="dinner" cols="30">{{ $diet->dinner}}</textarea></td>
						<td><textarea name="herbs" id="herbs" cols="30">{{ $diet->herbs}}</textarea></td>
						<td><textarea name="rem_dev" id="rem_dev" cols="30">{{ $diet->rem_dev}}</textarea></td>
					</tr>
					
				</table>

				<div style="text-align:center; margin-top:10px;">
					<button type="submit" class='modal-send modal-button'>Save</button>
					<input type='hidden' name='id' value='{{ $id }}'/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
  $('#date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'D-M-YYYY' 
  }); 
 });
</script>