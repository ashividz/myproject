@extends('patient.index')

@section('top')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Diet Send Preference </h4>
		</div>
        <div class="panel-body">
			<div align="center">
            <form action="/patient/{{$patient->id}}/preference"   method="post"  class="form-horizontal" role="form">
                <div class="checkboxThree">
                    <input type="checkbox" name="app" <?php if($patient->app==1)echo "checked='checked'"; ?>>
                    <label for="checkboxThreeInput">App</label>
                </div>
                <div class="checkboxThree">   
                    <input type="checkbox" name="email" <?php if($patient->email==1)echo "checked='checked'"; ?>>
                    <label for="checkboxThreeInput">Email</label>
                </div>
                <div class="checkboxThree">  
                    <input type="checkbox"  name="sms" <?php if($patient->sms==1)echo "checked='checked'"; ?>>
                    <label for="checkboxThreeInput">SMS</label> 
                </div> 
                <input type="submit" value="Submit">
                {{csrf_field()}} 
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