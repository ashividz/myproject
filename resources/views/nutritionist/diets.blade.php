<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-left">
			@include('nutritionist/partials/daterange_users')
		</div>
		<h4>Diets Sent</h4>
	</div>
	<div class="panel-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Entry Date</th>
					<th>Diet Date</th>
					<th>Breakfast</th>
					<th>Lunch</th>
					<th>Evening</th>
					<th>Dinner</th>
					<th>Herbs</th>
					<th>Remarks</th>
				</tr>
			</thead>
			<tbody>

		@foreach($diets AS $diet)
		<?php
			$i++
		?>

				<tr>
					<td>{{$i}}</td>
					<td><a href="/patient/{{$diet->patient_id}}/diet" target="_blank">{{$diet->patient->lead->name or ""}}</a></td>

					<td>
						<div class="article">
							<div class="description">
								<p>{{date('jS M, Y h:i A', strtotime($diet->date)) }}</p>
							</div>
						</div>							
					</td>
					<td>					
						<div class="article">
							<div class="description">
								<p>{{date('jS M, Y', strtotime($diet->date_assign))}}</p>
							</div>
						</div>							
					</td>
					<td>
						<div class="article">
							<div class="description">
								<p>{{$diet->breakfast or ""}}</p>
								<a href="#more" class="more grad">More...</a>
							</div>
						</div>					

					</td>
					<td>
						<div class="article">
							<div class="description">
								<p>{{$diet->lunch or ""}}</p>
								<a href="#more" class="more grad">More...</a>
							</div>
						</div>						
					</td>
					<td>
						<div class="article">
							<div class="description">
								<p>{{$diet->evening or ""}}</p>
								<a href="#more" class="more grad">More...</a>
							</div>
						</div>
					</td>
					<td>
						<div class="article">
							<div class="description">
								<p>{{$diet->dinner or ""}}</p>
								<a href="#more" class="more grad">More...</a>
							</div>
						</div>
					</td>
					<td>
						<div class="article">
							<div class="description">
								<p>{{$diet->herbs or ""}}</p>
								<a href="#more" class="more grad">More...</a>
							</div>
						</div>
					</td>
					<td>
						<div class="article">
							<div class="description">
								<p>{{$diet->rem_dev or ""}}</p>
								<a href="#more" class="more grad">More...</a>
							</div>
						</div>				
						@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl'))

							@if(date('Y-m-d', strtotime($diet->date_assign)) > date('Y-m-d'))
								<div class="pull-right">
									<a href="#" id="{{$diet->id}}" onclick="deleteDiet(this.id)"><i class="glyphicon glyphicon-remove red"></i></a>
								</div>
							@endif

						@endif
					</td>
				</tr>

		@endforeach

			</tbody>
		</table>
		
	</div>	
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
	        theMore.text(that.showing[index] ? "More..." : "Less...");
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
	function deleteDiet(id) {

	    var r=confirm("Are you sure you want to delete?");
    	if (r==true){
            var url = "/nutritionist/diet/delete"; //
	        $.ajax(
	        {
	           type: "POST",
	           url: url,
	           data: {id : id}, // send Source Id.
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
        };
	};
</script>