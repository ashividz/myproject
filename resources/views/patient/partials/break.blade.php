@extends('patient.index')

@section('top')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Break Adjustment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Allowed Break Days : {{$break->remaing}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Program duration : {{$break->months}}</h4>
		</div>
        <div class="panel-body">
			<div align="center">
            <form action="/patient/{{$patient->id}}/break"   method="post"  class="form-horizontal" role="form">
            	<div class="form-check">
				  <label class="form-check-label">
				    <input class="form-check-input" type="checkbox" name="mail" id="exampleRadios1" value="option1" >
				    Confirmation mail
				  </label>
				</div>
				<div class="form-check">
				  <label class="form-check-label">
				    <input class="form-check-input" type="checkbox" name="break" id="exampleRadios2" value="option2">
				    Break Adjustment
				  </label>
				</div>
				<br>
				<br>
               	<label for="Break">Start Date </label><input type="text" id="dt1" name="start_date">
		        <label for="Break">End Date </label> <input type="text" id="dt2" name="end_date">
		        <br> <br>
                <input type="submit" class="btn btn-primary" value="Submit">
                {{csrf_field()}}
            </form>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
		<div class="panel-heading">
			<h4>Breaks </h4>
		</div>
        <div class="panel-body">
			<div align="center">
               	<table id="table-age" class="table table-bordered">
                    <thead>
                        <tr>

                            <th>Patients Name</th>
                            <th>Days</th>
                            <th>Break Start Date</th>
                            <th>Break End Date</th>
                            <th>Created By</th>
                            <th>Break Created at</th>


                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($breaks AS $break)
		                    <tr>

		                    	<td>{{$break->lead->name}}</td>
		                    	<td>{{$break->break_days}}</td>
		                    	<td>{{$break->start_date}}</td>
		                    	<td>{{$break->end_date}}</td>
		                    	<td>{{$break->created_by}}</td>
		                    	<td>{{$break->created_at}}</td>
		                    </tr>
		                 @endforeach
                    </tbody>
                </table>
            </form>

            </div>
        </div>
    </div>
@endsection

<style>
.checkboxThree {
	width: 120px;
	height: 40px;
	background: #333;
	margin: 20px 60px;

	border-radius: 50px;
	position: relative;
}

.checkboxThree:before {
	content: 'On';
	position: absolute;
	top: 12px;
	left: 13px;
	height: 2px;
	color: #26ca28;
	font-size: 16px;
}

.checkboxThree:after {
	content: 'Off';
	position: absolute;
	top: 12px;
	left: 84px;
	height: 2px;
	color: #111;
	font-size: 16px;
}

.checkboxThree label {
	display: block;
	width: 52px;
	height: 22px;
	border-radius: 50px;

	transition: all .5s ease;
	cursor: pointer;
	position: absolute;
	top: 9px;
	z-index: 1;
	left: 12px;
	background: #ddd;
}
.checkboxThree input[type=checkbox]:checked + label {
	left: 60px;
	background: #26ca28;
}

</style>

<script type="text/javascript">
	 $(document).ready(function () {

        $("#dt1").datepicker({
            dateFormat: "dd-M-yy",
            minDate: 0,
            onSelect: function (date) {
                var dt2 = $('#dt2');
                var startDate = $(this).datepicker('getDate');
                var minDate = $(this).datepicker('getDate');
                dt2.datepicker('setDate', minDate);
                startDate.setDate(startDate.getDate() + 30);
                //sets dt2 maxDate to the last day of 30 days window
                dt2.datepicker('option', 'maxDate', startDate);
                dt2.datepicker('option', 'minDate', minDate);
                $(this).datepicker('option', 'minDate', minDate);
            }
        });
        $('#dt2').datepicker({
            dateFormat: "dd-M-yy"
        });
    });
</script>
