<style type="text/css">
	.audit {
		font-size: 9px;
	}
	.red {
		background-color: red;
		text-align: center;
	}
	.yellow {
		background-color: yellow;
		text-align: center;
	}
	.green {
		background-color: green;
		text-align: center;
	}
	h3 {
		margin-top: 5px;
		color: #fff;
	}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Csv</a>
		<h4>Audit</h4>
	</div>
	<div class="panel-body">
    	  <table id="table-age" class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th style="width: 10%"  >Start Date</th>
					<th style="width: 10%" >End Date</th>
					<th>Nutritionist</th>
					<th>Last Diet</th>
					<th>DOB</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Blood Group</th>
					<th>Medical</th>
					<th>Blood Test</th>
					<th>Measurement</th>
					<th>Prakriti</th>
					<th style="width: 10%">Notes</th>
					<th style="width: 8%">Tags</th>

				</tr>
			</thead>
			<tbody>

	@foreach($patients AS $patient)
				<tr>
					<td>{{$i++}}</td>
					<td><a href="/lead/{{ $patient->lead_id }}/viewDetails" target="_blank"> {{$patient->lead->name or "" }} </a></td>
					<td>{{ $patient->fee ? $patient->fee->start_date->format('jS M Y') : "" }}</td>
					<td>{{ $patient->fee ? $patient->fee->end_date->format('jS M Y') : "" }}</td>
					<td>{{ $patient->nutritionist or "" }}</td>
					<td></td>

				@if(trim($patient->lead->dob) == '')
					<td class="red">N</td>
				@elseif(!Helper::validateDate($patient->lead->dob->format('Y-m-d')))
					<td class="yellow" title="{{$patient->lead->dob->format('jS M, Y') }}"><span style="color:yellow">X</span></td>
				@else
					<td class="green" title="{{$patient->lead->dob->format('jS M, Y')}}"><span style="color:green">Y</span></td>
				@endif

				@if(trim($patient->lead->email) == '')
					<td class="red" title="Email">N</td>
				@else
					<td class="green" title="{{$patient->lead->email or ''}}"><span style="color:green">Y</span></td>
				@endif

				@if(trim($patient->lead->phone) == '')
					<td class="red" title="Phone}}">N</td>
				@else
					<td class="green" title="{{$patient->lead->phone or ''}}"><span style="color:green">Y</span></td>
				@endif

					<td class="{{ $patient->blood_group_id == '' || $patient->rh_factor_id == '' ? 'red' : 'green'}}">{{$patient->blood_type->name or "" }} {{$patient->rh_factor->code or ""}}</td>

				@if ($patient->constipation == NULL || $patient->gas == NULL || $patient->water_retention == NULL || $patient->digestion_type == NULL || $patient->allergic == NULL || $patient->wheezing == NULL || $patient->acidity == NULL || $patient->diseases_history == NULL || $patient->energy_level == NULL || $patient->diagnosis == NULL || $patient->medical_problem == NULL || $patient->previous_weight_loss == NULL || $patient->medical_history == NULL || $patient->sweet_tooth == NULL || $patient->routine_diet == NULL || $patient->special_food_remark == NULL)
					<td class='red' title='Medical Details'>N</td>
				@else
					<td class='green'  title='Medical Details'><span style="color:green">Y</span></td>
				@endif

				@if(isset($patient->medical_date))
					<td class="{{$patient->medical_date >= $patient->fee->fee_date ? 'green' : 'yellow'}}" title="Medical on {{$patient->medical_date }}">{{ $patient->medical_date }}</td>
				@else
					<td class='red'  title='Blood Test'></td>
				@endif

				@if(isset($patient->measurement_date))
					<td class="{{$patient->measurement_date >= $patient->fee->created_at ? 'green' : 'yellow'}}" title="Measurement on {{ $patient->measurement_date }}">{{ $patient->measurement_date }}</td>
				@else
					<td class='red'  title='Measurement Details'></td>
				@endif
					<td class="{{ isset($patient->prakriti) ? 'green' :'red'}}" title="Prakriti"></td>

					<td>{{$patient->note['text']}}</td>
					<ul>
						<td>
							@foreach($patient->tags as $tag)
								<li>{{ $tag->name }} </li>
							@endforeach
						</td>
					</ul>


				</tr>
	@endforeach

			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/buttons/1.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.flash.min.js"></script>
<script type="text/javascript" src = "//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.print.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#table-age').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
             'csv'
        ]
    } );
} );
</script>
