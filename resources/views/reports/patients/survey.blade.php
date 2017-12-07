

<div class="container1">

<?php $active = true ?>
@foreach($patient->cresurveys AS $survey)

    	<div class="item {{$active?'active':''}}">
 <?php $active = false ?>
    		<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Survey</h4>
				</div>
				<div class="panel-body">
					
					<div class="col-md-6">
						<ul>
							<li><strong>Name :</strong> {{$survey->patient->lead->name}}</li>
							<li><strong>Nutritionist :</strong> {{$survey->nutritionist}}</li>
							
						</ul>
					</div>
					<div class="col-md-6">
						<ul>
							<li><strong>Created By :</strong> {{$survey->created_by}}</li>
							<li><strong>Date :</strong> {{$survey->created_at}}</li>
							<li><strong>Source :</strong> {{$survey->source}}</li>
						</ul>
					</div>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Question</th>
								<th>Answer</th>
								
								<th>Comment</th>
							</tr>
						</thead>
						<tbody>
					@foreach($survey->answers AS $answer)
							<tr>
								<th>{{$answer->question->title or ""}}. {{$answer->question->question or ""}}</th>
								<td>{{$answer->answer->answer or ""}}</td>
								<td>{{$answer->comment}}</td>
							</tr>
					@endforeach

						</tbody>				
					</table>
				</div>
			</div>
    	</div>
	@endforeach

		</div>

	<!-- Controls -->
	
</div>



</div>
