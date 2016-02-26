@extends('lead.index')

@section('top')
<link href="/css/popup.css" rel="stylesheet">
<script src="/js/jquery/jquery.popup.min.js"></script>
<script type="text/javascript" src="/js/modals/mynutritionist.js"></script>
<script type="text/javascript" src="/js/modals/mycre.js"></script>
<div class="row">
	<div class="col-md-10">
		<div class="panel panel-default">
			<div class="panel-heading"><span class="panel-title">Add Disposition</span></div>
			<div class="panel-body">
			 @if(!$lead->dnc)	
				<form method="POST" action="/lead/{{ $lead->id }}/saveDisposition" role="form" class="form-inline" id="form">
		            <div class="row" style="padding-top:20px;">
		                <div class="col-md-6 col-sm-6">
		                    <div class="form-group col-md-12">
		                        <div class="input-group">
		                            <span>CONNECTED : </span><input type="radio" name="status" value="1" checked onclick="selectDisposition(1)">
		                            <br><span>NOT CONNECTED : </span><input type="radio" name="status" value="2" onclick="selectDisposition(2)">
		                        </div>
		                    </div> 
		                    <hr> 
		                    <p>&nbsp;</p>                        
		                    <div class="form-group col-md-12">
		                        <div class="input-group">
		                            <label class="input-group-addon"></label>
		                            <div class="dropdown">
		                                <select id="disposition" name="disposition" class="form-control disposition" size=15 required>
		                                </select>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-6 col-sm-6"> 
		                	<div class="row">     
			                    <div class="form-group col-md-12">
			                        <div class="input-group col-md-12">
			                            <div class="input-group-addon">Remarks *</div>
			                            <textarea class="form-control" type="textarea" rows="2" id="remarks" name="remarks" required></textarea>
			                        </div>
			                    </div> 
			                </div>
			                <hr>
			                <div class="row">     

			                    <div class="form-group col-md-12">
			                        <div class="input-group col-md-8">
			                            <div class="input-group-addon">Callback</div>
			                            <input class="form-control" type="text" id="callback" name="callback" value="">
			                        </div>
			                	</div>     
		                    </div>
		                </div>
		            </div> 
		            <p></p>
		            <div class="row">                       
		                <div class="col-md-12 col-sm-12" align="center">
		                    <button id="save" type="submit" name="save" class="btn btn-success"> Save Disposition</button>
		                </div>			                
		            </div>          
		            <input type="hidden" name="_token" value="{{ csrf_token() }}">

		        </form>
			@else
				<div class="blacklisted"></div>
			@endif
			</div>
		</div>
	</div>	
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading"><span class="panel-title">Quick Links</span></div>
			<div class="panel-body links">
				<ul>
					<li title="Compose New Message" data-placement="left" data-toggle="tooltip">
						<button id="message" value="{{$lead->id}}" class="btn btn-primary"><i class=" fa fa-envelope fa-2x"></i></button>
					</li>
					<li title="Call For My CRE, Nutritionist, Secondary Nutritionist, Doctor">
						<button id="mynutritionist" value="{{$lead->id}}" class="btn btn-primary"><i class=" fa fa-user-md fa-2x"></i></button>
					</li>
                    <li title="Validate Coupon">
                        <a href="http://coupon/validation?lead_id={{$lead->id}}&amp;name={{$lead->name}}&amp;phone={{$lead->phone}}&amp;email={{$lead->email}}&amp;user_id={{Auth::User()->id}}" class="btn btn-primary default_popup"><i class='fa fa-gift fa-2x'></i></a>
                    </li>
				</ul>
			</div>
		</div>
	</div>	
</div>
@endsection
@section('main')
<div class="container">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>#</th>
							<th>Date</th>
							<th width="60%">Disposition</th>
							<th>Name</th>
							<th>Email</th>
							<th>SMS</th>
						</tr>
					</thead>
					<tbody>
			<?php $i=0 ?>
			@foreach($lead->dispositions as $disposition)
				<?php $i++ ?>
						<tr>
							<td>{{ $i }}</td>
							<td>{{ date('jS-M-y H:i', strtotime($disposition->created_at)) }}</td>
							<td><b>{{ $disposition->master->disposition or "" }}</b>  : 
								{{ $disposition->remarks }}
								<small class="pull-right">{!! $disposition->callback ? "Callback On : " . date('jS-M-Y h:i A', strtotime($disposition->callback)) : "" !!}</small>
							</td>
							<td>{{ $disposition->name }}</td>
							<td>{!! $disposition->email ? "<span class='label label-success'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Email Sent'></span></span>" : "" !!}</td>
							<td align="center">{!! $disposition->sms ? "<span class='label label-success'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='" . $disposition->sms . "'></span></span>" : "" !!}</td>
						</tr>
			@endforeach
					</tbody>
				</table>
			</div>
		</div>		
	</div>
</div>
<style type="text/css">
	.links li {
		line-height: 60px;
	}
</style>

<script type="text/javascript">
$(document).ready(function() 
{    
	
    var form = $("#form");

	getDispositionList(1); //Connected Calls Disposition List
    $('#alert').hide();

    $('#callback').datetimepicker({
        format : 'j-M-Y H:i',
        minDate:0
    });

     
    $("#form").submit(function(event) {
        
        /* stop form from submitting normally */
        event.preventDefault();

        if (($("#disposition").val() == 8 || $("#disposition").val() == 15) && $("#callback").val().trim() == "") 
        {
            $('#alert').show();
            $('#alert').empty().append('Callback Date/Time Required');
            setTimeout(function()
            {
                $('#alert').slideUp('slow').fadeOut(function() 
                {
                    //location.reload();
                 });
            }, 3000);
            //alert('Callback Date/Time Required');
            return false;
        };


        
        var url = $("#form").attr('action'); // the script where you handle the form input.
        $.ajax(
        {
           type: "POST",
           url: url,
           data: $("#form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               $('#alert').show();
               $('#alert').empty().append(data);
               setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        location.reload();
                     });
                }, 3000);
           }
        });
        form.find(':enabled').each(function() 
        {
            $(this).attr("disabled", "disabled");
        });
        $('#edit').prop("disabled", false);
        $('#alert').hide();
        return false; // avoid to execute the actual submit of the form.
    });         
});

function getDispositionList(status) 
{
    $.getJSON("/master/dispositions", { status: status, dept: {{ $dept }} }, function(result){
        $("#disposition").empty();
        $.each(result, function(i, field) {
            $("#disposition").append("<option value='" + field.id + "'> " + field.disposition + "</option>");
        });
    });  
}

function selectDisposition(status)
{
    getDispositionList(status);
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>
<script>
    $(function(){
        // popup for coupon
        var options = { width : '700px',height:'400px' };
        $('.default_popup').popup(options);

});
</script>
@endsection