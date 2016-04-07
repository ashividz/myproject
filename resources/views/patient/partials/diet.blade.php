@extends('patient.index')

@section('top')
<style type="text/css">
	.tags li {
		list-style: none;
		display: inline-block;
	}
	.tags li .tag {
		text-transform: uppercase;
		font-size: 14px;
		margin-left: 20px; 
		background-color: rgba(111, 128, 168, 1);
    	box-shadow: 0 1px 0 rgba(255, 255, 255, 0.15), inset 0 1px 2px rgba(0, 0, 0, 0.5);
    	padding: 2px 20px;	
	}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
	</div>
	<div class="panel-body">
		<form id="form" method="post" action="/patient/{{$patient->id or ''}}/suit">
			<table class="table table-bordered">
				<tbody>
					<tr>
						<td>
							<label> Preferred Time : </label> 
							<input name="trial_plan" value="{{$patient->suit->trial_plan or ""}}">
						</td>
						<td>
							<label> Special Food Remarks : </label> 
							{{$patient->special_food_remark or ""}}
						</td>
					</tr>
					<tr>
						<td>
							<label> Suit : </label>
							<textarea name="suit" cols="40">{{$patient->suit->suit or ""}}</textarea>
						</td>
						<td>
							<label> Not Suit : </label> 
							<textarea name="not_suit" cols="40">{{$patient->suit->not_suit or ""}}</textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label> Deviation : </label> 
							<textarea name="deviation" cols="40">{{$patient->suit->deviation or ""}}</textarea>
						</td>
						<td><label> Remark : </label> 
							<textarea name="remark" cols="40">{{$patient->suit->remark or ""}}</textarea>
						</td>
					</tr>
					<tr>
						<td>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<button type="submit" class="btn btn-primary">Save</button>
						</td>
                        <td>
                            <a href="/patient/{{ $patient->id }}/recipes" class="btn btn-success">Recipes</a>
                        </td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
@endsection

@section('main')
@if($patient->fee)
<script type="text/javascript" src="/js/modals/diet.js"></script>
<?php
	$herbs = '';
	if ($patient->herbs) {

		foreach ($patient->herbs as $herb) {

			$when = '';

			$herbs .= $herb->herb->name." : ".$herb->quantity." ";
			$herbs .= $herb->unit?$herb->unit->name:"";
			$herbs .= " ".$herb->remark;

			//if(isset($herb->mealtimes)) {
				foreach ($herb->mealtimes as $mealtime) {
					$when .= $mealtime->mealtime ? $mealtime->mealtime->name . ' & ' : '' ;
				}
			//}		

			$when = rtrim($when, "& ");
			$herbs .= ' ('.$when.') '; 
			$herbs .= " + ";
		}

		$herbs = rtrim($herbs, " + ");
	}

	//$now = $patient->fee->end_date;//date('Y-m-d') > $patient->fee->end_date ? $patient->fee->end_date : date('Y-m-d');
	$fee = $patient->cfee ? $patient->cfee : $patient->fee;
	  
	$days = floor((strtotime($fee->end_date) - strtotime($fee->start_date))/(60*60*24));
	$day = floor((strtotime(date('Y-m-d')) - strtotime($fee->start_date))/(60*60*24));
	
	$day = $day < 0 ? 0 : $day;
	$diet = 0; //Diet Count
?>
@if($fee)
<div>
	<table class="table blocked" width="100%">
		<tr>
			<td width="33%">{{$fee->start_date->format('jS M, Y')}}</td>
			<td width="33%" align="center"><label>Today is {{$day <= $days ? $day : $days}} of {{$days}} days</label></td>
			<td><div class="pull-right">{{$fee->end_date->format('jS M, Y')}}</div></td>
		</tr>
		<tr>
			<td colspan="3">
				<table class="table progress" width="auto">
					<tr>
				
				@for($i = 0; $i < $days; $i++)
<?php
	$dt = $fee->start_date->addDay($i)->format('Y-m-d'); 
	$color = $dt < date('Y-m-d') ? '#ca4e4e' : 'grey';
	
	if($patient->diets->where('date_assign', $dt)->first())
	{
		$color = '#addf58';
		$diet++;
	}
?>						
					@if($dt == date('Y-m-d'))
						<td title="Today : {{date('jS M, Y', strtotime($dt))}}" style="background-color:#4D8FC5"></td>
					@else
						<td title="{{date('jS M, Y', strtotime($dt))}}" style="background-color:{{$color}}"></td>
					@endif
				@endfor	

					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div class="container">	
					<div class="panel panel-default">
						<div class="panel-body" style="align:center">
							<h4>Total Diets sent : {{$diet}} days</h4>
						</div>
					</div>
				</div>
			</td>
			<td>
				<div class="container">	
					<div class="panel panel-default">
						<div class="panel-body" style="align:center">
					@if(date('Y-m-d') > $fee->end_date)
							<h4>Program ended {{$fee->end_date->diffForHumans()}}</h4>
					@elseif(date('Y-m-d 00:00:00') == $fee->start_date)
							<h4>Program has started {{$fee->start_date->diffForHumans()}}</h4>
					@elseif(date('Y-m-d 00:00:00') < $fee->start_date)
							<h4>Program will start in {{$fee->start_date->diffForHumans()}}</h4>
					@else
							<h4>Program will end in {{$fee->end_date->diffForHumans()}}</h4>
					@endif
									
						</div>					
					</div>	
				</div>	
			</td>
			<td>
			@if($fee->start_date < $patient->fee->start_date)
				<div class="container">	
					<div class="panel panel-default">
						<div class="panel-body" style="align:center">
							<h4>New Program will start in {{$patient->fee->start_date->diffForHumans()}}</h4>
						</div>					
					</div>
				</div>		
			@endif
			</td>	
		</tr>
	</table>	
</div>
@endif

<div class="panel panel-default">
	<div class="panel-heading tags">
@foreach($patient->tags as $tag)
		<li><div class="tag">{{$tag->name}}</div></li>	
@endforeach		
	</div>
<?php
	$diet_date = $patient->diet ? date('d-m-Y', strtotime('+1 day', strtotime($patient->diet->date_assign))) : date('d-m-Y'); 
    $diet_date = strtotime($diet_date) >= strtotime(date('d-m-Y')) ? $diet_date : date('d-m-Y');
?>
	<div class="panel-body">
		<form id="form-diet" action="/patient/{{$patient->id}}/diet" method="post" class="form-inline">
			<table class="table table-bordered blocked">
				<!--
				<thead>
					<tr>					
						<th>Date</th>
						<th>Early Morning</th>
						<th>Breakfast</th>
						<td>Mid Morning</td>
						<th>Lunch</th>
						<th>Evening</th>
						<th>Dinner</th>
						<th>Herbs (&#8478;)</th>
					</tr>
				</thead>-->	
				<tbody>
					<tr>
						<td>
							<input type="text" id="date" name="date" size="10" value="{{ $diet_date }}" readonly placeholder="{{ $diet_date }}">
						</td>
						<!--<td>
							<div><label>Early Morning:</label></div>
							<textarea name="early_morning" id="early_morning" class="diet-area" placeholder="Early Morning">{{ old('early_morning')}}</textarea>
							<div id="early_morning-list" class="diet-list"></div>
						</td>-->
						<td>
							<div><label>Breakfast:</label></div>
							<textarea name="breakfast" id="breakfast" class="diet-area" placeholder="Breakfast">{{ old('breakfast')}}</textarea>
							<div id="breakfast-list" class="diet-list"></div>
						</td>
						<td>
							<div><label>Mid Morning:</label></div>
							<textarea name="mid_morning" id="mid_morning" class="diet-area" placeholder="Mid Morning">{{ old('mid_morning')}}</textarea>
							<div id="mid_morning-list" class="diet-list"></div>
						</td>
						<td>
							<div><label>Lunch:</label></div>
							<textarea name="lunch" id="lunch" class="diet-area" placeholder="Lunch">{{ old('lunch')}}</textarea>
							<div id="lunch-list" class="diet-list"></div>
						</td>
					</tr>
					<tr>
						<td>
							<div><label>Evening:</label></div>
							<textarea name="evening" id="evening" class="diet-area" placeholder="Evening">{{ old('evening')}}</textarea>
							<div id="evening-list" class="diet-list"></div>
						</td>
						<td>
							<div><label>Dinner:</label></div>
							<textarea name="dinner" id="dinner" class="diet-area" placeholder="Dinner">{{ old('dinner')}}</textarea>
							<div id="dinner-list" class="diet-list"></div>
						</td>
						<td>
							<div><label>Herbs:</label></div>
							<textarea name="herbs" id="herbs" class="diet-area" placeholder="Herbs">{!!$herbs!!}</textarea>
							<div id="herbs-list" class="diet-list"></div>
						</td>
						<td>
							<div><label>Remarks/Deviations:</label></div>
							<textarea placeholder="Remarks/Deviations" name="rem_dev" id="remark">{{ old('rem_dev')}}</textarea>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="form-group">
				<button class="btn btn-primary" name="email" {{date('Y-m-d') > $patient->fee->end_date->format('Y-m-d') ? 'disabled' : ''}}>Add Diet</button>
				<!--<input type="text" placeholder="Weight" size="5" name="weight" {{ old('weight')}}>-->
				
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

			</div>
			<a href="/patient/{{$patient->id}}/weight" class="btn btn-success pull-right">Enter Weight</a>
		</form>
	</div>
</div>

@if(!$diets->isEmpty())
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Send Diets</h3>
	</div>
	<div class="panel-body">
		<form id="form-diet" action="/patient/{{$patient->id}}/diets/send" method="post" class="form-inline">
			<table class="table table-bordered blocked">
				<thead>
					<tr>
						<th>#</th>					
						<th width="5%">Date</th>
						<!--<th>Early Morning</th>-->
						<th width="12%">Breakfast</th>
						<th width="12%">Mid Morning</th>
						<th width="12%">Lunch</th>
						<th width="12%">Evening</th>
						<th width="12%">Dinner</th>
						<th width="12%">Herbs (&#8478;)</th>
						<th width="12%">Weight</th>
						<th width="12%">Remarks/Deviations</th>
						<th>Email</th>
						<th>SMS</th>
						<th></th>
						@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl'))
							<th>Delete</th>
						@endif						
					</tr>
				</thead>
				<tbody>
					
			@foreach($diets as $diet)
					<tr>
						<td><input type="checkbox" name="checkbox[]" id="checkbox[]" value="{{$diet->id}}"></td>
						<td>{{date('jS M, Y', strtotime($diet->date_assign))}}</td>
						<!--<td>
							<div class="early_morning">{{$diet->early_morning}}</div>
							<i class="fa fa-copy pull-right blue" title="early_morning"></i>
						</td>-->

						<td>
							<div class="breakfast">{{$diet->breakfast}}</div>
							<i class="fa fa-copy pull-right blue" title="breakfast"></i>
						</td>
						<td>
							<div class="mid_morning">{{$diet->mid_morning or ""}}</div>
							<i class="fa fa-copy pull-right blue" title="mid_morning"></i>
						</td>
						<td>
							<div class="lunch">{{$diet->lunch}}</div>
							<i class="fa fa-copy pull-right blue" title="lunch"></i>
						</td>
						<td>
							<div class="evening">{{$diet->evening}}</div>
							<i class="fa fa-copy pull-right blue" title="evening"></i>
						</td>
						<td>
							<div class="dinner">{{$diet->dinner}}</div>
							<i class="fa fa-copy pull-right blue" title="dinner"></i>
						</td>
						<td>
							<div class="herbs">{{$diet->herbs}}</div>
							<i class="fa fa-copy pull-right blue" title="herbs"></i>
						</td>
						 <td>
                           <?php
                            $date_assign = date('Y-m-d',strtotime($diet->date_assign));
                            $first_weight = $patient->weights->first(function ($key, $value) use($date_assign){return $value['date'] > $date_assign;});
                            ?>
                            @if($first_weight)
                            {{$first_weight->weight}}<br><small>{{date('jS M, Y', strtotime($first_weight->date))}}</small>
                            @endif
                        </td>
						<td>
							<div class="remark">{{$diet->rem_dev}}</div>
							<i class="fa fa-copy pull-right blue" title="remark"></i>
						</td>
						<td style="text-align:center">
						@if($diet->created_at >= '2016-02-03')
							<i class="fa {{$diet->email?'fa-check-square-o green':'fa-close red'}}"></i>
						@endif
						</td>
						<td style="text-align:center">
						@if($diet->created_at >= '2016-02-03')
							<i class="fa {{$diet->sms_response?'fa-check-square-o green':'fa-close red'}}" title="{{$diet->sms_response}}"></i>
						@endif
						</td>
						<td style="text-align:center">						
							<i class="fa fa-edit diet" id="{{$diet->id}}"></i>
						</td>
						@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl'))
						<td>					
							@if(date('Y-m-d', strtotime($diet->date_assign)) > date('Y-m-d'))
								<div class="pull-right">
									<a href="#" id="{{$diet->id}}" onclick="deleteDiet(this.id)"><i class="glyphicon glyphicon-remove red"></i></a>
								</div>
							@endif
						</td>

						@endif						
					</tr>
			@endforeach

				</tbody>
			</table>

			<div class="form-group">
				<button class="btn btn-primary" name="email">Send Email</button>
			</div>
			<div class="form-group">
				Send SMS
				<input type="checkbox" name="sms" checked>
			</div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group pull-right">
				<a href="/patient/{{$patient->id}}/diets" class="btn btn-success">View All Diets</a></button>
			</div>
		</form>
	</div>
</div>
@endif
<script type="text/javascript" src="/js/form-ajax.js"></script>

<script type="text/javascript">
$(document).ready(function() 
{
  $('#date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'D-M-YYYY',
    minDate: new Date()  
  }); 
 });
</script>

<style type="text/css">
	.progress td {
		padding: 8px 0 !important;
	}
	div .checked {
		background-color:#266c8e; 
		color:#fff!important; 
	}
</style>
<script type="text/javascript">
$(document).ready(function() 
{
	$('.fa-copy').on('click', function(){
		var i = $(this).prev();
		var diet = this.title;		

		$('#'+this.title).val(i.text());
		$('.'+this.title).removeClass('checked');
		i.addClass('checked');
		//alert('Copied : ' +i.text());
	});

	//autocomplete
	$( "#form-diet .diet-area" ).autocomplete({
    	source: function( request, response ) {
    		//alert($(this.element).prop("id"));
        	var id = $(this.element).prop("id");
        	$.ajax({
				type: "POST",
				url: "/diet/autocomplete",
				data:{'term' : request.term, id : id, _token : '{{ csrf_token() }}'},
				dataType: "json",
				beforeSend: function(){
					//$("#"+id+"-list").css("background","#f2f2f2");
				},
				success: function(data){
					//console.log(data);
					$("#"+id+"-list").empty();
					$("#"+id+"-list").show();
					response( $.map( data, function(field) {
                    // your operation on data
                
						console.log(field.id);
						$("#"+id+"-list").append("<li class='diet-data' title='"+id+"'>"+ field.diet +"</li>");
					}));
				}
			});
		},
		minLength :4
	});

	$('body').on('click', '.diet-data', function(){
		$("#"+this.title).val($(this).text());
		$("#"+this.title+"-list").hide();
	});

	//Hide diet-list when not in focus
	$("body").click
	(
	  function(e)
	  {
	    if(e.target.className !== "diet-list")
	    {
	      $(".diet-list").hide();
	    }
	  }
	);
});
</script>
<script type="text/javascript">
	function deleteDiet(id) {

	    var r=confirm("Are you sure you want to delete?");
    	if (r==true){
            var url = "/nutritionist/diet/delete"; //
	        $.ajax(
	        {
	           type: "POST",
	           url: url,
	           data: {id : id}, // send Source Id.
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
	};
</script>
<style type="text/css">
	#form-diet textarea {
		font-size: 12px;
		width: 20em;
	}
	.diet-list {		
		position: absolute;
		line-height: 20px;
		background-color: #fff4c5;
		border: 1px solid #e4c94b;
	}
	li.diet-data {
		list-style: none;
		padding: 5px;
		cursor: pointer;
		z-index: 9999;
		min-width: 500px;
		font-size: 12px;
		margin: 5px; 
		background-color: #fff4c5;
	}
	li.diet-data:hover {
		background-color: #F9DD68;
	}
	.ui-helper-hidden-accessible {
		display: none !important;
	}
</style>
@else
<h1>Fee details not available</h1>
@endif
@endsection