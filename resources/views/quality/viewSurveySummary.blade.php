@extends('master')

@section('content')

@include('partials/daterange')

<style type="text/css">
	#survey{
		width: 70%;
	}
	table tfoot {
		background-color: #f2f2f2;
	}
	.comment {
	}
	.comment-header{
		font-weight: 800;
		padding: 5px;	
	}

	.comment-body{
		font-style: italic;
		padding: 5px;	
	}
	.comment-footer {
		padding: 5px;		
		border-bottom: 1px dotted;
		font-size: 10px;
		color: #888;
	}
</style>

<?php $surveys = json_decode($surveys) ;?>

<div class="container">
	<div id="survey">
	@foreach($surveys AS $survey)
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>{{$survey->title}}</h4>
			</div>

			<div class="panel-body">
				<div id="{{$survey->title}}">
					<!-- Chart placeholder -->				
				</div>	
				<div class="container">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Answer Choices</th>
								<th>Responses</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($survey->answers AS $answer)
							<tr>								
								<td>{{$answer->answer}}</td>
								<td>{{$survey->count ? round($answer->count*100/$survey->count, 2) . "%": "0"}}</td>
								<td>{{$answer->count}}</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Total</th>
								<th></th>
								<th>{{$survey->count}}</th>
							</tr>
						</tfoot>
					</table>
				</div>
			
			@if(!empty($survey->comments))

				<div class="container">
					<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapse{{$survey->title}}" aria-expanded="false" aria-controls="collapse{{$survey->title}}">
					  Read Comments
					</a>
					
						<div class="collapse" id="collapse{{$survey->title}}">
						  	<div class="comment well">
								@foreach($survey->comments AS $comment) 
									<div class="comment-header">
										<a href="http://crm/patient.php?clinic={{$comment->clinic}}&registration_no={{$comment->registration_no}}" target="_blank">{{$comment->name}}</a><span class="pull-right">Nutritionist: {{$comment->nutritionist}}</span>
									</div>								  
									<div class="comment-body">
										{{$comment->comment}}
									</div>
									<div class="comment-footer">
										{{date('D d M Y, h:i A', strtotime($comment->created_at))}}
									</div> 
								@endforeach
						  	</div>
						</div>
				</div>

			@endif

			</div>
			@if(!empty($answers->comments))

				<div class="container">
					<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapse{{$survey->title}}" aria-expanded="false" aria-controls="collapse{{$survey->title}}">
					  Read Comments
					</a>
					
						<div class="collapse" id="collapse{{$survey->title}}">
						  	<div class="comment well">
								@foreach($survey->comments AS $comment) 
									<div class="comment-header">
										<a href="http://crm/patient.php?clinic={{$comment->clinic}}&registration_no={{$comment->registration_no}}" target="_blank">{{$comment->name}}</a><span class="pull-right">Nutritionist: {{$comment->nutritionist}}</span>
									</div>								  
									<div class="comment-body">
										{{$comment->comment}}
									</div>
									<div class="comment-footer">
										{{date('D d M Y, h:i A', strtotime($comment->created_at))}}
									</div> 
								@endforeach
						  	</div>
						</div>
				</div>

			@endif
		@include('quality/barchart', array('id' => $survey->title, 'question' => $survey->question, 'count' => $survey->count, 'survey' => $survey->answers))

		</div>	
	@endforeach
	</div>
@endsection
