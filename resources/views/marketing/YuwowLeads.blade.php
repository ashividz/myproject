<div class="container">  
    <div class="panel panel-default">
        <div class="panel-heading">             
                <div class="pull-right">
                   @include('partials/daterange')
                </div>
                <h4 style="margin-left:400px">Yuwow Leads</h4>
        </div>
        <div class="panel-body">
            <form id="form2" class="form-inline" method="POST" action="">
					<b>FILTER</b>	

                    <div class="checkbox">
                        <label>
                        <input type="checkbox" id="incrm" checked="true" onchange="filter(this.id)"> In CRM
                        </label>
                    </div>  	
				  	<div class="checkbox">
				    	<label>
				      	<input type="checkbox" id="notincrm" checked="true" onchange="filter(this.id)"> Not In CRM
				    	</label>
				  	</div>		
			</form>
            <!-- Tab panes -->
            <div class="tab-content">
                <form id="myform" method="post" action="marketing/saveYuwowLeads">
                <div role="tabpanel" class="tab-pane  active" id="age">
                     <table id="table-age" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>SNo</th>
                            <th>LeadID</th>
                            <th>UserName</th>
                            <th>UserEmail</th>
                            <th>Contact No</th>
                            
                        </tr>
                        </thead>
                        <tbody>

                            
                        @foreach ($users as $user)
                        <?php
                            $filter = "";
                            if(!is_null($user->CRMLeads))
                            {
                                $filter = "incrm";
                                $checkboxclass = "crmcheck";
                            }
                            else if(is_null($user->CRMLeads))
                            {
                                $filter = "notincrm";
                                $checkboxclass = "notcrmcheck";
                            }
                        ?>
                        <tr class="{{$filter}}">
                           <td>{{$i++}}</td>
                           <td><a href="/lead/{{$user->CRMLeads->id or ""}}/viewDetails" target="_blank">{{$user->CRMLeads->id or ""}}</a></td>
                           <td>{{$user->user_first_name}} {{$user->user_last_name}}</td>
                           <td>{{$user->user_email}}</td>
                           <td>{{$user->mobile}}</td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div> 
</div> 


<script type="text/javascript">
	function filter(id)
	{
		if($("#" + id).is(':checked'))
	    	$('tr.' + id).show();  // checked
		else{
	    	$('tr.' + id).hide();// unchecked
		    if(id=="incrm")
                $(".ncrcheck").prop('checked', false);
            if(id=="notincrm")
		    	$(".notincrm").prop('checked', false);
		}
	}
</script>

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