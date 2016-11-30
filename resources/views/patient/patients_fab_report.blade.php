<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right">
			@include('nutritionist/partials/users')
		</div>
		<h4>FAB Report</h4>
	</div>
	<div class="panel-body">
	<!-- Nav tabs -->
      	<ul class="nav nav-tabs" role="tablist">
        	<li role="presentation" class="active"><a href="#fabsent" aria-controls="appointment" role="tab" data-toggle="tab">FAB Sent</a></li>
        	<li role="presentation"><a href="#notsent" aria-controls="primary" role="tab" data-toggle="tab">Not Sent</a></li>
        	
      	</ul>

      	<!-- Tab panes -->
      	<div class="tab-content">
    	<!-- Appointments -->
        	
    	<!-- Appointments End -->

      	<!-- Primary Nutritionist Start -->
        	<div role="tabpanel" class="tab-pane active" id="fabsent">
    @if(!$patientsFab->isEmpty())    	
          	
				<table id="primary_table" class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Nutritionist</th>
							<th>FAB Sent</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>CRE</th>
							<th>Notes</th>
							<th width="10%">Remark</th>
						</tr>
					</thead>
					<tbody>

	@foreach($patientsFab as $patient)					
			<?php
				$fee = $patient->cfee ? $patient->cfee : $patient->fee;
                $fee = $patient->fees->sortByDesc('end_date', 0)->first();
                //dd($patient);
			?>	
		<tr>
			<td>{{$y++}}</td>
			<td><a href="/lead/{{$patient->lead->id}}/viewDetails" target="_blank">{{$patient->lead->name}}</a></td>
			<td>{{$patient->nutritionist}}</td>
            <td>{{$patient->fab->created_at->format('Y-m-d')}}
                <a data-toggle="modal" data-target="#myModal" href="/getSentFab/{{$patient->fab->id}}" class=""><i class="fa fa-download fa-2x"></i></a>
            </td>
			<td>{{$fee->start_date->format('Y-m-d')}}</td>
			<td>{{$fee->end_date->format('Y-m-d')}}</td>
			
				
			</td>
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
	@endif
        	</div>

        	<div role="tabpanel" class="tab-pane " id="notsent">
    @if(!$patients->isEmpty())    	
          	
				<table id="appointment_table" class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Nutritionist</th>
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
     $fee = $patient->fees->sortByDesc('end_date', 0)->first();
?>
		
	<tr>
		<td>{{$y++}}</td>
		<td><a href="/lead/{{$patient->lead->id}}/viewDetails" target="_blank">{{$patient->lead->name}}</a></td>
		<td>{{$patient->nutritionist}}</td>
		<td>
            @if(isset($fee->start_date))
                {{$fee->start_date->format('Y-m-d')}}
            @endif
        </td>
		<td>
            @if(isset($fee->start_date))
                 {{$fee->end_date->format('Y-m-d')}}</td>
		    @endif
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
	@endif
        	</div>
        	<!-- Primary Nutritionist End -->

        	<!-- Secondary Nutritionist Begin -->
	        	
			<!-- Secondary Nutritionist End -->
		</div>
	</div>	
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Modal title</h4>

            </div>
            <div class="modal-body"><div class="te"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
	function checkLength() {
	    this.showing = new Array();
	}

	checkLength.prototype.check = function() {
	    var that = this;
	    $('.article').each(function (index) {
	        var article = $(this);
	        var theP = article.find('p');
	        var theMore = article.find('.more');
	        if (theP.width() > article.width()) {
	            theMore.show();
	            that.showing[index] = true;
	        } else {
	            if (!article.hasClass('active')) {
	                theMore.hide();
	                that.showing[index] = false;
	            } else {
	                that.showing[index] = false;
	            }
	        }
	        theMore.text(that.showing[index] ? ">>" : "<<");
	    });
	};

	$(function () {
	    var checker = new checkLength();
	    checker.check();
	    $('.more').each(function () {

	        $(this).on('click', function (e) {
	            $(this).closest('.article').toggleClass('active');
	            checker.check();
	        });
	    });

	    $(window).resize(function() {
	        checker.check()
	    });
	});
</script>
<style type="text/css">
	.today {
		background-color:#DFF0D8;
		color: #333;
		/*background-image: -webkit-linear-gradient(top, #5d9ed2, #4386bc);*/
	}
	.hide {
		display: none;
	}
	.article {
    max-width:11em;
    font-size: 10px;
}
.description {
    position: relative;
    overflow:hidden;
}
.more {
    position: absolute;
    bottom:0;
    right:0;
    padding-left:2em;
}
.article p {
    padding:0;
    margin:0;
    white-space:nowrap;
    float:left;
}
.active.article p {
    white-space:normal;
}
.active.article .more {
    position: static;
    padding:0;
}
/* long messy gradient background code */
 .grad {
    background: -moz-linear-gradient(left, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(241, 241, 241, 0)), color-stop(19%, rgba(241, 241, 241, 0.53)), color-stop(36%, rgba(241, 241, 241, 1)));
    /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(left, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(left, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* Opera 11.10+ */
    background: -ms-linear-gradient(left, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* IE10+ */
    background: linear-gradient(to right, rgba(241, 241, 241, 0) 0%, rgba(241, 241, 241, 0.53) 19%, rgba(241, 241, 241, 1) 36%);
    /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00f1f1f1', endColorstr='#f1f1f1', GradientType=1);
    /* IE6-9 */
}
</style>
<script type="text/javascript">
$(document).ready(function() 
{
	$( "#app" ).bind( "click", function() 
  	{
    	var csv_value = $('#appointment_table').table2CSV({
                delivery: 'value'
            });
    	downloadFile('appointment.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  	});
  	
  	$( "#pri" ).bind( "click", function() 
  	{
    	var csv_value = $('#primary_table').table2CSV({
                delivery: 'value'
            });
    	downloadFile('primary.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  	});

  $( "#sec" ).bind( "click", function() 
  {
    var csv_value = $('#secondary_table').table2CSV({
                delivery: 'value'
            });
    downloadFile('secondary.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    $("#csv_text").val(csv_value);  
  });

  function downloadFile(fileName, urlData){
    var aLink = document.createElement('a');
    var evt = document.createEvent("HTMLEvents");
    evt.initEvent("click");
    aLink.download = fileName;
    aLink.href = urlData ;
    aLink.dispatchEvent(evt);
}
});
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" }); 
    $("[name='advance_diet1']").bootstrapSwitch();

    $('.advance').on('click', function() {
    	var id = this.id;
    	var state = 0;
    	if (this.checked) {state = 1};
    	
    	//var r=confirm("Are you sure you want to delete?");
    	//if (r==true){
            var url = "/patient/" + id + "/advance_diet"; //
	        $.ajax(
	        {
	           type: "POST",
	           url: url,
	           data: {state : state, "_token" : "{{ csrf_token()}}"}, // send Source Id.
	           success: function(data)
	           {
	               $('#alert').show();
	               $('#alert').empty().append(data);
	               setTimeout(function()
	                {
	                    $('#alert').slideUp('slow').fadeOut(function() 
	                    {
	                        //location.reload();
	                     });
	                }, 3000);
	           }
	        });
        //};
	});
});
</script>