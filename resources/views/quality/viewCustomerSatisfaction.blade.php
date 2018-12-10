<div class="container">	
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="pull-right">
                    <a href='/quality/report/survey_result/download?start_date={{$start_date}}&end_date={{$end_date}}' class="btn btn-primary" v-on:click="download">Download</a>
            </span> 
			<div class="pull-left">
				@include('/partials/daterange') 
			</div>
			<h4>Date Range</h4> 
		</div>
		<div class="panel-body">
			<?php $surveys = json_decode($surveys) ;?>
				@foreach($surveys as $survey)
						<div class="col-md-4">
							<div class="stage">
								<div class="stagehead">
									<span class="stagename"> {{$survey->title}} <em> ( {{$survey->count}} )</em></span> 
								</div>
							</div>
							@foreach($survey->patients as $patient)
								<div class="leads d">
									<div class='icon'>
										<img src='/images/user-green-icon.png'>
									</div>
									<div class='block'>
										<strong>
											<a href='/patient/{{$patient->patient_id}}/survey' target='_blank'>{{ $patient->patient_id }}
											</a>
											<em>&nbsp; ( {{ $patient->nutritionist }} ) </em>
										</strong>
									</div>
									@if($patient->comment)
										<span> [ {{ $patient->comment or "" }} ] </span>
									@endif
								</div>
							@endforeach
						</div>   
				@endforeach        
		</div>   
	</div>
</div>	
<style type="text/css">
.panel-body {
	background-color: #F0F0F0;
}
.col-md-4 {
	padding: 0px;
}
.stage {
	background: url('/images/editable-area-bg.png');
    height: 55px;
}
.stage .stagehead {
	background: url('/images/stage-cone-large.png') right center repeat-x;	
    height: 55px;
    vertical-align: middle;
    padding: 15px 30px 6px 12px;
}
.stage .stagename {
	font-family: Michroma,Cantarell;
	font-size: 14px;
    line-height: 21px;
    font-weight: 900;
    text-transform: uppercase;
}
.leads {
	background-color: #fff;
	min-height: 50px;
	margin: 1px;
}
.block {
    display: block;
    width: 100%;
    position: relative;
    min-height: 50px;
    margin: 0;
    border: none;
    padding-left: 20px;
}
.block strong {
    position: relative;
    font-weight: 700;
    display: block;
    cursor: pointer;
    font-family: Oswald;
    font-size: 12px;
    line-height: 18px;
    transition: color 0.1s ease-in-out;
    padding: 6px 25px 6px 12px;
}
.block em {
    font-size: 12px;
    line-height: 16px;
    padding: 2px 0;
    color: #888A8D;
}
.icon
{
	position: absolute;
	width: 50px;
	height: 50px;
}
.icon img {
    height: 32px;
    vertical-align: middle;
    padding: 5px;
}
</style>