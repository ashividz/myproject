@extends('patient.index')
@section('top')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Prakriti Analysis</h4>
		</div>
		<div class="panel-body">
			<div align="center">
				<table class="table table-bordered" style="width:70%; background-color:#fff4c5">
					<tr>
						<th>First Dominant</th>
						<td>{{$patient->first_dominant_name}}</td>
						<th>Count</th>
						<td>{{$patient->first_dominant_count}}</td>
						<th>Percentage</th>
						<td>{{round($patient->first_dominant_percentage, 2)}} %</td>
					</tr>
					<tr>
						<th>Second Dominant</th>
						<td>{{$patient->second_dominant_name}}</td>
						<th>Count</th>
						<td>{{$patient->second_dominant_count}}</td>
						<th>Percentage</th>
						<td>{{round($patient->second_dominant_percentage, 2)}} %</td>
					</tr>
					<tr>
						<th>Recessive</th>
						<td>{{$patient->recessive_name}}</td>
						<th>Count</th>
						<td>{{$patient->recessive_count}}</td>
						<th>Percentage</th>
						<td>{{round($patient->recessive_percentage, 2)}} %</td>
					</tr>
				</table>
				<div>

				@if($patient->prakritis->isEmpty())
					<button class="btn btn-primary" id="prakriti-copy" value="{{$patient->id}}">Copy from Old CRM</button>
				@endif

				</div>
			</div>
		</div>
	</div>
@endsection
@section('main')
<div class="container1">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Prakriti Questions</h4>
		</div>
		<div class="panel-body">
			<form method="POST" action="/patient/{{$patient->id}}/prakriti/save">
				<table class="table table-bordered">
					
			@foreach($questions AS $question)
					
					<tr>
						<td>{{$i++}}</td>
						<td><b>{{$question->trait}}<b></td>
						<td><b><em>{{$question->statement}}</em></b></td>
						<td>
							<label class="radio" for="a-{{$question->id}}-1" >
								<input name="answer[{{$question->id}}]" id="a-{{$question->id}}-1" value="1" type="radio">
								{{$question->vata}}
							</label>
						</td>
						<td>
							<label class="radio" for="a-{{$question->id}}-2">
								<input name="answer[{{$question->id}}]" id="a-{{$question->id}}-2" value="2" type="radio">
								{{$question->pitta}}
							</label>
						</td>
						<td>
							<label class="radio" for="a-{{$question->id}}-3">
								<input name="answer[{{$question->id}}]" id="a-{{$question->id}}-3" value="3" type="radio">
								{{$question->kapha}}
							</label>
						</td>
					</tr>

			@endforeach
					<tr>
						<td colspan="6" align="center">
							<button class="btn btn-primary">Save</button>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</td>
					</tr>

				</table>
			</form>
		</div>
	</div>
</div>
<style type="text/css">
	input {
		display: none;
	}
	label.radio-inline, label.radio, label.checkbox { 
		margin: 0px;
		margin-right:2%; 
		cursor:pointer; 
		font-weight:400; 
		padding:10px 10px 10px 30px; 
		margin-bottom:10px!important 
	} 
	label.radio-inline.checked, label.checkbox-inline.checked, label.radio.checked, label.checkbox.checked { 
		background-color:#266c8e; 
		color:#fff!important; 
		text-shadow:#000 1px 1px 2px!important 
	}
</style>
<script type="text/javascript">
	//When checkboxes/radios checked/unchecked, toggle background color 
	$('.table').on('click','input[type=radio]',function() { 
		$(this).closest('tr').find('.radio-inline, .radio').removeClass('checked');
		$(this).closest('.radio-inline, .radio').addClass('checked'); 
	});

	@foreach($patient->prakritis as $prakriti)
		$('#a-{{$prakriti->question_id}}-{{$prakriti->prakriti_id}}').closest('.radio-inline, .radio').addClass('checked');
	@endforeach
</script>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#prakriti-copy').on('click', function(){
		
		var url = '/patient/'+this.value+'/prakriti/copy';
		$.ajax(
        {
           type: "POST",
           url: url,
           data: {_token : '{{ csrf_token() }}'},
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
           },
           error : function(data) {
           		var errors = data.responseJSON;

        		console.log(errors);

           		$('#alert').show();
               	$('#alert').empty();
               	$.each(errors, function(index, value) {
		            $('#alert').append("<li>"+value+"</li>");
		        });

               	setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        //location.reload();
                     });
                }, 3000);
           }
        });
	});
});
</script>
@endsection