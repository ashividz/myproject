<div class="container">	
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-right">
				@include('nutritionist/partials/users')
			</div>
			<h4>Program End</h4>
		</div>
		<div class="panel-body">
			<table id="table" class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>CRE</th>
						<th>Notes</th>
						<th width="10%">Remark</th>
					</tr>
				</thead>
				<tbody>

				@foreach($patients as $patient)

				<?php
					$tags = ""	;	
					foreach($patient->tags as $tag) {
						$tags .= $tag->name . "<p>";
					}

					$fee = $patient->cfee ? $patient->cfee : $patient->fee;
				?>

					<tr>
						<td>{{$i++}}</td>
						<td><a href="/lead/{{$patient->lead->id}}/viewDetails" target="_blank">{{$patient->lead->name}}</a><div class="pull-right" data-html="true" data-toggle="tooltip" title="{!!$tags!!}"><a href="/patient/{{$patient->id}}/tags" target="_blank"><span class="label {{$patient->tags->isEmpty() ? 'label-danger': 'label-success'}}"><i class="fa fa-tags"></i></span></a></div></td>

						<td>{{$fee->start_date->format('Y-m-d')}}</td>
						<td>{{$fee->end_date->format('Y-m-d')}}</td>
						
						<td>{{$patient->lead->cre->cre or ""}}</td>
						<td>
						@foreach($patient->notes as $note)
							{{$note->text}}<br>
							<div class="pull-right">
								<em><small>{{$note->created_by}} : {{$note->created_at->format('jS M Y, h:i A')}}</small></em>
							</div>
						@endforeach
						</td>
						<td>
							{{$patient->suit->remark or ""}}
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#table").dataTable({
		bPaginate : false
	});
</script>