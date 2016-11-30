<div class="container">  
	<div class="panel panel-default">
		<div class="panel-heading">
      <h4>Platinum Customers</h4>
		</div>
		<div class="panel-body">        
          <div class="container">
            <p>
            <table id="platinum" class="table table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>References</th>
                  <th>Conversions</th>
                  <th>Percentage</th>
                </tr>
              </thead>
              <tbody>

            @foreach($platinums AS $platinum)  
                <tr>
                  <td><a href="/lead/{{$platinum->id}}/viewReferences" target="_blank">{{$platinum->name}}</a></td>
                  <td>{{$platinum->leads}}</td>
                  <td>{{$platinum->conversions}}</td>

                @if($platinum->conversions <> 0)                    
                  <td>{{round($platinum->conversions/$platinum->leads*100, 2)}} %</td>
                @else
                  <td></td>
                @endif
                  
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
$(document).ready(function() 
{
	$('#platinum').dataTable({
    "iDisplayLength": 100,
	  "bPaginate": false,
    "aaSorting": [[ 2, "desc" ]]
  	}); 
});
</script>