<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Prakriti Analysis</h4>
		</div>
		<div class="panel-body">
			<form method="POST">
				<table class="table table-bordered">
					
			@foreach($questions AS $question)
					
					<tr>
						<td>{{$i++}}</td>
						<td>{{$question->trait}}</td>
						<td>{{$question->statement}}</td>
						<td>
							<label class="radio" for="v{{$i}}">
								<input name="{{$question->id}}" id="v{{$i}}" value="v" type="radio">
								{{$question->vata}}
							</label>
						</td>
						<td>
							<label class="radio" for="p{{$i}}">
								<input name="{{$question->id}}" id="p{{$i}}" value="p" type="radio">
								{{$question->pitta}}
							</label>
						</td>
						<td>
							<label class="radio" for="k{{$i}}">
								<input name="{{$question->id}}" id="k{{$i}}" value="k" type="radio">
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
</script>