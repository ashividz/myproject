<style type="text/css">
	.question-wrapper {
		width: 75%;
    	margin: 10px auto 10px auto;
    	border: 1px solid;
    	border-color: #ddd;
    	border-collapse: collapse;
    	background-color: #FFFFFF;
	}
	td.questiontext {
	    font-family: verdana;
	    font-size: 12px;
	    font-weight: bold;
	    background-color: #4B4E54;
	    color: #fff;
	    text-align: left;
	    padding: 0.5em 1em;
	}
	.asterisk {
		display: inline;
	    color: red;
	    font-size: 9px;
	    font-family: verdana;
	    padding-right: 1em;
	}
	td.answer {
	    padding: 0.5em 1.5em;
	}
	div.list {
	    width: 47%;
	    float: left;
	    padding: 0.5em 0.5em 0.5em 0%;
	}
	ul {
	    list-style: disc inside;
	}
	li {
		list-style-type: none;
	    text-align: left;
	    margin: 0% 0% 0.5em 0.5em;
	}
	.sumbit-buttons {
		text-align: center;
	}

</style>
<form id="form" action="" method="POST">
	<div class="container">
		<table class='question-wrapper'>
			<tr>
				<td class='questiontext'>Name</td>
				<td class='answer'> <a href='/lead/{{ $patient->lead->id }}/viewDispositions' target="_blank">{{$patient->lead->name or ""}}</td>
				<td class='questiontext'>Nutritionist</td>
				<td class='answer'>{{$patient->nutritionist or ""}}</td>
			</tr>
			<tr>
				<td class='questiontext'>Phone</td>
				<td class='answer'>{{$patient->lead->phone or ""}}</td>
				<td class='questiontext'>Mobile</td>
				<td class='answer'>{{$patient->lead->mobile or ""}}</td>
			</tr>
		</table>
		<table class='question-wrapper'>

<?php $i = 0 ?>

		@foreach($questions AS $question)
<?php $i++ ?>
			<tr>
				<td class='questiontext'>
					{{$question->title}} 
					{!!$question->mandatory == 'Y' ? "<span class='asterisk'>*</span>" : ""!!}
					{{$question->question}}
				</td>
			</tr>
			<tr>
				<td class='answer'>
					<div class='list'>
						<ul>
			@foreach ($question->answers as $answer)
				<li>
				<input type='radio' name='answer[{{$i}}]' value='{{$answer->id}}'
				{{$question->mandatory ? " required" : "" }}
				/>
				 {{$answer->answer}}
				</li>
			@endforeach

			@if($question->other)			
				<li>
				<input type='radio' name='answer[{{$i}}]' value='Others' />
				 Others
				</li>
			@endif
			</ul>
			</div>
			@if ($question->comment == "Y")
				<div class='comment'>
				<label>Please enter your comment here:</label>
				<textarea name='comment[{{$i}}]' rows='4' cols='30'></textarea>
				</div>
			@endif
			</td>
		</tr>
	@endforeach

		<input type="hidden" name="patient_id" value="{{$patient->id}}">
		<input type="hidden" name="nutritionist" value="{{$patient->nutritionist}}">
		<input type="hidden" name="source" value="calls">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<table class="table">
			<tr>
				<td class="sumbit-buttons">
					<button type="submit" class="btn btn-primary">Submit</button>
				</td>
			</tr>
		</table>
	</div>
</form>