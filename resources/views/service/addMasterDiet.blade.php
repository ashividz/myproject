<div class="col-md-8 col-md-offset-2">

	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">Add Master Diet</h2>
		</div>
		<div class="panel-body">
	        <hr>
			<form method="POST" role="form" class="form-inline" id="form" data-message="Do you really want to save this MasterDiet?">
				<fieldset>
					<ol>						
						<li>
							<label>Program *</label>
                            <select name="program_id" id="program_id" required>
                            <option></option>
                            @foreach($programs as $pr)
                            <option value="{{$pr->id}}" >{{$pr->name}}</option>
                            @endforeach
                            </select>
						</li>
						<li>
							<label>BloodGroup *</label>
                            <select name="blood_group_name" id="blood_group_name" required>
                            <option></option>
                            @foreach($blood_groups as $bg)
                            <option value="{{$bg->name}}" >{{$bg->name}}</option>
                            @endforeach
                            </select>
						</li>
                        <li>
							<label>RhFactor *</label>
							<select name="rhfactor_name" id="rhfactor_name" required>
                            <option></option>
                            @foreach($rh_factors as $rh)
                            <option value="{{$rh->code}}" >{{$rh->code}}</option>
                            @endforeach
                            </select>
						</li>
                        <li>
							<label>Prakriti *</label>
							<select name="prakriti_name" id="prakriti_name" required>
                            <option></option>
                            @foreach($prakriti as $pk)
                            <option value="{{$pk->name}}" >{{$pk->name}}</option>
                            @endforeach
                            </select>
						</li>

						<li>
							<label>Breakfast *</label>
							<textarea size="3" class="form-control" type="text" id="breakfast" name="breakfast" style="width:390px" required></textarea>
						</li>
						<li>
							<label>MidMorning *</label>
							<textarea size="3" class="form-control" type="text" id="midmorning" name="midmorning" style="width:390px" required></textarea>
						</li>
                        <li>
							<label>Lunch *</label>
							<textarea size="3" class="form-control" type="text" id="lunch" name="lunch" style="width:390px" required></textarea>
						</li>
                        <li>
							<label>Evening *</label>
							<textarea size="3" class="form-control" type="text" id="evening" name="evening" style="width:390px" required></textarea>
						</li>
                        <li>
							<label>Dinner *</label>
							<textarea size="3" class="form-control" type="text" id="dinner" name="dinner" style="width:390px" required></textarea>
						</li>
					</ol>
				</fieldset>				
				<div class="row">
				
					<div class="col-md-4">
						<button type="submit" name="submit" class="btn btn-success">Submit</button>
						<input class="btn btn-danger" type="reset" value="Clear form">
					</div>
				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">			
			</form>
		</div>	
	</div>
	
</div>

<div class="panel-body" id="mydiets">
        <form id="form-diet" action="#" method="post" class="form-inline">
            <table id="example" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>BloodGroup</th>
                            <th>Prakriti</th>
                            <th>DayCount</th>
                            <th>Preference</th>
                            <th>BreakFast</th>
                            <th>MidMorning</th>
                            <th>Lunch</th>
                            <th>Evening</th>
                            <th>Dinner</th>
                        </tr>
                    </thead>
                        
                    <tbody>
            @if($diets <> NULL)
                @foreach($diets as $diet)
                    
                        <tr>
                            <td>{{ $diet->name}}</td>
                            <td>{{ $diet->Blood_Group}}{{$diet->Rh_Factor}}</td>
                            <td>{{ $diet->Body_Prakriti}}</td>
                            <td>{{ $diet->Day_Count }}</td> 
                            <td> <?php $colorClass="redbox"; if($diet->isveg==1) $colorClass="greenbox";?> 
                            <div id="circle" class="circle <?php echo $colorClass; ?>"></div></td>
                            <td>
                                <div class="breakfast">{{$diet->Breakfast}}</div>
                            </td>    
                            <td>
                                <div class="midmorning">{{ $diet->MidMorning }}</div>
                            </td>
                            <td>
                                <div class="lunch">{{ $diet->Lunch }} </div>
                            </td>
                            <td>
                                <div class="Evening">{{ $diet->Evening }}</div>
                            </td>
                            <td>
                                <div class="Dinner">{{ $diet->Dinner }}</div>
                                
                            </td>
                            
                        </tr>        
                @endforeach
            @endif

                @if(!$diets)
                    <tr>
                        <td colspan="8">No results found</td>
                    </tr>
                @endif
                    </tbody>

                </table>
            </form>
        </div>
<style>
.circle {
      width: 10px;
      height: 10px;
      -webkit-border-radius: 25px;
      -moz-border-radius: 25px;
      border-radius: 25px;
    }
.redbox{
    background: red;
}   
.greenbox{
    background: green;
} 
</style>

<script>
$(document).ready(function () {
	$("form.form-inline").submit(function (e) {
  var $message = $(this).data('message');
  if(!confirm($message)){
    e.preventDefault();
  }
});	
});
</script>