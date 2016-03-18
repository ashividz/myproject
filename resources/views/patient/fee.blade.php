@extends('patient.index')

@section('main')

@if($patient && !$patient->fees->isEmpty())
	<div class="container">
		<div class="col-md-12">
			<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h1 class="panel-title">Fees</h1>
			  	</div>
			  	<div class="panel-body">
			  		<table class="table table-bordered">
			  			<thead>
			  				<tr>
			  					<th>Entry Date</th>
			  					<th>Start Date</th>
			  					<th>End Date</th>
			  					<th>Amount</th>
			  					<th>Duration</th>
			  					<th>CRE</th>
			  					<th>Source</th>
			  				</tr>
			  			</thead>
			  			<tbody>

					@foreach($patient->fees as $fee)
							<tr>
			  					<td>{{$fee->entry_date->format('jS M, Y')}}</td>
			  					<td>{{$fee->start_date->format('jS M, Y')}}</td>
			  					<td>{{$fee->end_date->format('jS M, Y')}}</td>
			  					<td>{{$fee->total_amount}}</td>
			  					<td>{{$fee->valid_months or ""}}</td>
			  					<td>{{$fee->cre}}</td>
			  					<td>{{$fee->source->source_name or ""}}</td>
			  				</tr>
					@endforeach
			  				
			  			</tbody>
			  		</table>
			  	</div>
			</div>
		</div>
	</div>
@endif
@endsection

@section('top')
@if($patient)
<script type="text/javascript" src="/js/form-ajax.js"></script>
	<div class="panel panel-default">
	  	<div class="panel-heading">
	    	<h1 class="panel-title">Add Fee</h1>
	  	</div>
	  	<div class="panel-body">
	  		<form id="form" method="post" class="form-inline" action="/patient/{{$patient->id}}/fee">
	  			<div class="form-group">
	  				<label>Payment Date</label>
	  				<input type="text" id="entry_date" name="entry_date" size="10" value="{{date('d-m-Y')}}">
	  			</div>
	  			<div class="form-group">
	  				<label>Start Date</label>
	  				<input type="text" id="start_date" name="start_date" size="10" value="{{date('d-m-Y')}}">
	  			</div>
	  			<div class="form-group">
	  				<label>End Date</label>
	  				<input type="text" id="end_date" name="end_date" size="10" value="" readonly>
	  			</div>
	  			<div class="form-group">
	  				<label>Duration</label>
	  				<select id="duration" name="duration">
	  					<option value="">Select Duration</option>
	  					<option value="1">1 day</option>
	  					<option value="7">7 days</option>
	  					<option value="30">1 month</option>
	  					<option value="60">2 months</option>
	  					<option value="90">3 months</option>
	  					<option value="180">6 months</option>
	  					<option value="365">12 months</option>
	  				</select>
	  			</div>

	  			<div class="form-group">
	  				<label>Discount</label>
	  				<input type="text" name="discount" size="5">
	  			</div>
	  			<div class="form-group">
	  				<label>Amount</label>
	  				<input type="text" name="amount" size="5">
	  			</div>

	  			
	  			<div class="form-group">
					<label>CRE</label>
					<select id="cre" name="cre">
						<option value="">Select CRE</option>
					@foreach($users as $user)
						@if(old('cre'))
							<option value="{{$user->name}}" {{$user->name == old('cre')?'selected':''}}>{{$user->name}}</option>
						@else
							<option value="{{$user->name}}" {{$patient->lead->cre && $user->name == $patient->lead->cre->cre?'selected':''}}>{{$user->name}}</option>
						@endif
					@endforeach
					</select>
		<?php
			$cres = "";
			foreach ($patient->lead->cres as $cre) {
				$cres .= "<li>";
				$cres .= $cre->cre;
				$cres .= " : ";
				$cres .= $cre->created_at->format('jS M, Y');
				$cres .= "</li>";
			}
		?>
					<div data-html="true" data-toggle="popover" title="Lead CREs" data-content="{{$cres}}" style="display:inline-block" data-placement="left"><i class="fa fa-info-circle"></i></div>
				</div>

				<div class="form-group">
					<label>Source</label>
					<select id="source" name="source">
						<option value="">Select Source</option>
					@foreach($sources as $source)
						@if(old('source'))
							<option value="{{$source->id}}" {{$source->source_name == old('source')? 'selected':''}}>{{ $source->source_name or "" }}</option>
						
						@else
							<option value="{{$source->id}}" {{$patient->lead->source && $source->source_name == $patient->lead->source->master->source_name ? 'selected':''}}>{{$source->source_name}}</option>
						@endif
					@endforeach
					</select>

        <?php
			$sources = "";
			foreach ($patient->lead->sources as $source) {
				$sources .= "<li>";
				$sources .= $source->master?$source->master->source_name:'';
				$sources .= " : ";
				$sources .= $source->created_at->format('jS M, Y');
				$sources .= "</li>";
			}
		?>
					<div data-html="true" data-toggle="popover" title="Lead Sources" data-content="{{$sources}}" style="display:inline-block" data-placement="left"><i class="fa fa-info-circle"></i></div>
				</div>
				<div style="text-align:center">
					<input type="hidden" name="name" value="{{ $patient->lead->name }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<button class="btn btn-primary">Save</button>
				</div>
	  		</form>
	  	</div>
	</div>
@endif
<style type="text/css">
	#form .form-group{
		margin: 10px 20px;
	}
	/*Bootstrap Popover*/
	.popover {
		text-align: left;
	    max-width: 1250px;
	}
</style>
<script type="text/javascript">
$(document).ready(function() 
{
  $('#entry_date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'D-M-YYYY',
    maxDate: new Date() 
  }); 
  $('#start_date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'DD-MM-YYYY'
  }); 
  $('[data-toggle="popover"]').popover({ trigger: "hover" }); 

  $('#duration').on('change', function(){
  	var start_date = $('#start_date').val();
  	setEndDate(start_date);
  })

  function setEndDate(start_date) {
  	var days = $('#duration').val();

  	var end_date = moment(start_date, 'DD-MM-YYYY').add(days, 'days');

  	$('#end_date').val(end_date.format('DD-MM-YYYY'));	
  }

 });
</script>
@endsection