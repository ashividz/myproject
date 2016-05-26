
<?php
	$i = 0;
?>

<style type="text/css">
	.checkbox {
		padding: 10px;
	}
	.shs_row1, .shs_row2
	{

		background: #ffe066;
	}
	.shs_row1 td
	{
		border-top:1px solid #ffb31a !important;
	}
	.shs_row1 td:first-child 
	{
		border-top:1px solid #ffb31a; 
		border-left:1px solid #ffb31a;
	}
	
		
	
	.shs_row1 td:last-child 
	{
		border-top:1px solid #ffb31a;
		border-right:1px solid #ffb31a;
	}
	.shs_row2 td
	{
		border-bottom:1px solid #ffb31a;
	}
	.shs_row2 td:first-child 
	{
		border-bottom:1px solid #ffb31a; 
		border-left:1px solid #ffb31a;
	}
	.shs_row2 td:last-child 
	{
		border-bottom:1px solid #ffb31a; 
		border-right:1px solid #ffb31a;
	}
	.row
	{
		box-shadow: 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 2px rgba(0, 0, 0, 0.5) inset;
		background: #ccc;
		margin: 0px;
		margin-bottom: 20px;
		margin: 0px;
		padding: 10px;
	}
</style>

		
<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class='col-md-4 col-md-offset-1 alert alert-{{$nlp ? "success" : "warning"}}' role='alert'>
				{{$nlp}} Lead{{$nlp ? "s" : ""}} synced from NLP. </div>
			<div class='col-md-4 col-md-offset-2 alert alert-{{$website ? "success" : "warning"}}' role='alert'>
				{{$website}} Lead{{$website ? "s": ""}} synced from Website. 
			</div>
		</div>
	</div>
	<div class="panel">
		<div class="heading">
				
			<div style="margin-bottom:20px; padding:20px">
			<div style='display: inline-block;'>
			@include('partials/daterange')
			</div>
			<div style='display: inline-block;'>
				<form id="form2" class="form-inline" method="POST" action="">
					<b style='margin-left:30px'>FILTER</b>		
				  	<div class="checkbox">
				    	<label>
				      		<input type="checkbox" id="ncr" checked="true" onchange="filter(this.id)"> Delhi/NCR
				    	</label>
				  	</div>		
				  	<div class="checkbox">
				    	<label>
				      	<input type="checkbox" id="pan" checked="true" onchange="filter(this.id)"> PAN India
				    	</label>
				  	</div>		
				  	<div class="checkbox">
				    	<label>
				      	<input type="checkbox" id="int" checked="true" onchange="filter(this.id)"> International
				    	</label>
				  	</div>
				</form>
				</div>
							

			</div>
		</div>
		<div class="body">
			<form id='save-lead-form' method='POST' action='' novalidate>
				<div class='row' style=''>
					<label><input type="checkbox" onchange=""  id="select_all"> Select All
					</label>

					<div class='dropdown' style='display: inline-block;width: 30%;margin-left: 20px'>
						<select name='cre' id='cre_list' class='form-control'>
							<option value=''>Select CRE</option>
					@foreach($users AS $user)
								<option value="{{$user->name}}">{{$user->name}}</option>
					@endforeach
						</select>
					</div>	

				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<table class="table">
					<tbody>
				@foreach($queries AS $query)

		<?php
			$background = $query->status == 2 ? "#fcf8e3": "#DFF0D8";
			$border = $query->status == 2 ? "#EC86A7" : "#5CB85C";
			
		?>		

					@if(isset($query->lead))
						<tr style="background-color:{{$background}}">
							<td style="border-top:1px solid {{$border}}; border-left:1px solid {{$border}}">
								{{$query->date ? date('dS M, Y h:i A', strtotime($query->date)) : ""}}
							</td>
							<td style="border-top:1px solid {{$border}}">
								{{$query->lead->name or ""}}
							</td>
							<td style="border-top:1px solid {{$border}}">
								{{$query->lead->phone or ""}}
							</td>
							<td style="border-top:1px solid {{$border}}">
								{{$query->lead->email or ""}}
							</td>
							<td style="border-top:1px solid {{$border}}; border-right:1px solid {{$border}}">
								{{$query->lead->source->master->source_name or ""}}
							</td>
						</tr>
						<tr style="background-color:{{$background}}">
							<td style="border-bottom:1px solid {{$border}}; border-left:1px solid {{$border}}">
								Lead : <a href="/lead/{{$query->lead->id}}/viewDetails" target="_blank">{{$query->lead->id}}</a>
							</td>
							<td style="border-bottom:1px solid {{$border}}">{{$query->lead->country or ""}}</td>
							<td style="border-bottom:1px solid {{$border}}">{{$query->lead->state or ""}}</td>
							<td style="border-bottom:1px solid {{$border}}">{{$query->lead->city or ""}}</td>
							<td style="border-bottom:1px solid {{$border}}; border-right:1px solid {{$border}}">{{$query->lead->cre->cre or ""}}</td>

						</tr>
					@else
			<?php 
				$i++; 

				$filter = "";
				if ($query->country == 'IN' && $query->state == 'IN.07') 
				{
					$filter = "ncr";
				}
				elseif ($query->country == 'IN' ) 
				{
					$filter = "pan";
				}
				elseif (trim($query->country) <> "" && $query->country <> 'IN' ) 
				{
					$filter = "int";
				}

				$shs_class = '';
			    $shs_class2 = '';
			    $shs_select = '';
			?>
						<tr class="{{$filter}} {{$shs_class}}">
							<td>
								<input type='checkbox' class='checkbox'  name='query_id[{{$i}}]' value="{!! htmlentities($query->id) !!}">
								{{$query->date ? date('dS M, Y h:i A', strtotime($query->date)) : ""}}
							</td>
							<td>
								<input class='form-control' type='text' name='name[{{$i}}]' value='{{$query->name}}' placeholder='Name' required>
							</td>
							<td>
								<input class='form-control' size='14' type='text' name='phone[{{$i}}]' value='{{$query->phone}}' placeholder='Phone' {!!Lead::isExistingMobile($query->phone) ? 'style="background-color:#f2dede;" title="duplicate phone"':''!!}required>
							</td>
							<td>
								<input class='form-control' type='text' name='email[{{$i}}]' value='{{$query->email}}' placeholder='Email' {!!Lead::isExistingEmail($query->email) ? 'style="background-color:#f2dede;" title="duplicate email"':''!!}>
							</td>
							<td>
								<div class='dropdown'>
									<select name='source[{{$i}}]' class='form-control' required>
							@foreach($sources AS $source)
										<option value="{{$source->id}}"  {{$query->source == $source->source || $query->source == $source->source1 || $query->source == $source->source2 || $query->source == $source->source3 ? "selected": ""}}>{{$source->source_name}}</option>
							@endforeach
									</select>
								</div>
							</td>
							<td></td>
						</tr>
						<tr class="{{$filter}} {{$shs_class2}}">
							<td></td>
							<td>
								<div class='dropdown'>
									<select name='country[{{$i}}]' class='form-control'>
							@foreach($countries AS $country)
										<option value="{{$country->country_code}}" {{$query->country == $country->country_code ? "selected": ""}}>{{$country->country_name}}</option>
							@endforeach
									</select>
								</div>
							</td>
							<td>
								<input class="form-control" type='text' name='state[{{$i}}]' value='{{$query->state}}' placeholder='State' readonly>
							</td>
							<td>
								<input class="form-control" type='text' name='city[{{$i}}]' value='{{$query->city}}' placeholder='City' readonly>
							</td>
							<td>
							
							</td>

							<td style='text-align:center'>
						@if ($query->status == 1) 
								<span class='label label-success' title="Lead Already Added"><span class='glyphicon glyphicon-ok'></span></span>

						@elseif ($query->status == 2) 

								<span class='label label-warning'><span class='glyphicon glyphicon-flash'></span></span>

						@else
								<span class='label label-danger' title="Lead Not Added Yet"><span class='glyphicon glyphicon-remove'></span></span>
						@endif
							</td>
						</tr>
						<input class='cre_field' type="hidden" name="cre[{{$i}}]" value="">
						<input type="hidden" name="remark[{{$i}}]" value="{!! htmlentities($query->query) !!}">
						<input type="hidden" name="date[{{$i}}]" value="{{ $query->date }}">
						<input type="hidden" name="id[{{$i}}]" value="{{ $query->id }}">

				@endif
				@endforeach
					</tbody>
				</table>
				<div style="text-align:center">
					<button type="submit" id="save" name="save" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	function filter(id)
	{
		if($("#" + id).is(':checked'))
	    	$('tr.' + id).show();  // checked
		else
	    	$('tr.' + id).hide();  // unchecked
	}

	$('select#cre_list').on('change', function() {
	 	var thisvalue = $(this).find("option:selected").text();
	 	
	 	$('.cre_field').val(thisvalue);

	});

	$("#select_all").change(function () {
        $("input.checkbox:checkbox").prop('checked', $(this).prop("checked"));
    });

     $("#save-lead-form").submit(function(event) {


       
        /* stop form from submitting normally */

        var url = $("#save-lead-form").attr('action'); // the script where you handle the form input.
        $('#alert').show();
        if($('input[class="checkbox"]:checked').length == 0)
            {
                $('#alert').empty().append("<p>No Query selected..</p>");
                setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut();
                }, 2000);
            
            return false;
            }
        	var thisvalue = $('select#cre_list').find("option:selected").text();
           if(thisvalue =='Select CRE')
            {
                $('#alert').empty().append("<p>No CRE selected..</p>");
                setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut();
                }, 2000);
            
            return false;
            }

        $('#alert').empty().append("<p>Processing Please Wait..</p>");
       $(this).submit();
        return true; // avoid to execute the actual submit of the form.
    }); 
</script>