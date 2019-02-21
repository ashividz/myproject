@extends('patient.index')
@section('main')
<div class="container1">
	<div id="carousel" class="carousel" data-ride="carousel">
  	<!-- Indicators -->
  	<ol class="carousel-indicators">
    	<li data-target="#carousel" data-slide-to="0" class="active"></li>
    	<li data-target="#carousel" data-slide-to="1"></li>
    	<li data-target="#carousel" data-slide-to="2"></li>
  	</ol>

  	<!-- Wrapper for slides -->
  	<div class="carousel-inner" role="listbox">
<?php $active = true ?>
@foreach($patient->surveys AS $survey)

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
							<li><strong>Score :</strong> {{$survey->score}}</li>
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
								<th>Score</th>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody>
					@foreach($survey->answers AS $answer)
							<tr>
								<th>{{$answer->answer->question_id or ""}}. {{$answer->answer->question->question or ""}}</th>
								<td>{{$answer->answer->answer or ""}}</td>
								<td>{{$answer->answer->assessment_value or ""}}</td>
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
	<a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
	<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	<span class="sr-only">Previous</span>
	</a>
  <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>



</div>
@endsection
