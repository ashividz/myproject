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
						<th>Breakfast</th>
						<th>Mid Morning</th>
						<th>Lunch</th>
					</tr>
					<tr>			
						<td><textarea name="breakfast" id="breakfast" cols="30">{{ $diet->Breakfast}}</textarea></td>					
						<td><textarea name="mid_morning" id="mid_morning" cols="30">{{ $diet->MidMorning}}</textarea></td>				
						<td><textarea name="lunch" id="lunch" cols="30">{{ $diet->Lunch}}</textarea></td>	
					</tr>
					<tr>
						<th>Evening</th>
						<th>Dinner</th>
					</tr>
					<tr>				
						<td><textarea name="evening" id="evening" cols="30">{{ $diet->Evening}}</textarea></td>
						<td><textarea name="dinner" id="dinner" cols="30">{{ $diet->Dinner}}</textarea></td>
					</tr>
					
				</table>

				<div style="text-align:center; margin-top:10px;">
					<button type="submit" class='modal-send modal-button'>Approve</button>
					<input type='hidden' name='id' value='{{ $id }}'/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</div>
			</form>
		</div>
	</div>
</div>