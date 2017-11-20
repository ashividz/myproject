<div class="container1">
	<div class="col-md-6">
		@include('partials/daterange')

	@foreach($questions AS $question)
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>{{$question->tittle}} {{$question->question}}</h4> 
			</div>

			<div class="panel-body">
				<div id="{{$question->title}}">			
				</div>	
				<div class="">
					<table class="table table-bordered" >
						<thead>
							<tr>
								<th>Answer Choices</th>
								<th>Responses</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($question->answers AS $answer)
							<tr>								
								<td>{{$answer->answer}}</td>
								<td>{{$question->total_answers_count <> 0 ? round($answer->count*100/$question->total_answers_count, 2) . "%": "0"}}</td>
								<td>{{$answer->count}}</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Total</th>
								<th></th>
								<th>{{$question->total_answers_count}}</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	@endforeach

	</div>
	<div class="col-md-6">
		<div id="comments">
			<h3>Comments</h3>
			<div class="panel panel-default">
				<div class="panel-heading">
					<div id="comments-question"></div>
				</div>
				<div class="panel-body">
					<div id="comments-body" class='comment well'></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#comments').hide();

	$('.btn-comment').click(function(){
		getComments(this.id);
	});

	function getComments(id) {
	    $.ajax(
	    {
	   		type : 'get',
	   		url : "/api/survey/comments",
           	data: {id : id, start_date : '{{$start_date}}', end_date :'{{$end_date}}'},
	        beforeSend: function () { 
           		$('#alert').show();
           		$('#alert').empty().append('<i class="fa fa-spinner fa-spin fa-5x"></i> Processing...');
           	},
           	complete: function () { 
         		$('#alert').hide();
           		$('#alert').empty()
         	},
	        success: function(data)
           	{               
				$(window).scrollTop($('#comments').offset().top);
				$('#comments').show();
				$("#comments-body").empty();
				var i = 1;
				


				$.each(data, function(i, field) { 
					if(field.comment != '') {
						$("#comments-question").empty();
						$("#comments-question").append("<h4>"+field.question.title+". "+field.question.question+"</h4>");

						$("#comments-body").append("<div class='comment-header'><a href='/patient/"+field.survey.patient_id+"/survey' target='_blank'>"+field.survey.patient.lead.name+"</a>");
						
						if(field.answer) {
							$("#comments-body div:last").append(" ("+field.answer.answer+")");
						}

						$("#comments-body div:last").append("<span class='pull-right'>Nutritionist : "+field.survey.nutritionist+"</span></div>");

						$("#comments-body").append("<div class='comment-body'>"+field.comment+"</div>");

						var created_at = new Date(field.created_at);
						$("#comments-body").append("<div class='comment-footer'>"+moment(created_at).format("MM MMM YYYY, hh:mm a")+"</div>");
					};
				});
           	}
        });
	}
});
</script>
<style type="text/css">
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