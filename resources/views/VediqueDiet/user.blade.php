<div class="container">
	<div class="panel panel-default">
			<div class="panel-heading">
				<h4>VediqueDiet Users</h4>
			</div>	
			<div class="panel-body">			
				<!-- <form id="form" class="form-inline" action="/marketing/leads/churn/save" method="post"> -->
		<table id="leads" class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Prakriti</th>
                <th>Preference</th>
                <th>Goal</th>
                <th>Craeted_at</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->email}}</td>
                <td>{{ $user->gender or " "}}</td>
                <td>{{ $user->phone or " "}}</td>
                <td>{{ $user->prakriti or " "}}</td>
                <td>{{ $user->preference or " "}}</td>
                <td>{{ $user->goal or " "}}</td>
                <td>{{ $user->created_at or " "}}</td>
            </tr>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Prakriti</th>
                <th>Preference</th>
                <th>Goal</th>
                <th>Craeted_at</th>
            </tr>
        </tfoot>
    </table>
			<script type="text/javascript">
				$(document).ready(function() {
   					 $('#leads').dataTable({
        				"aLengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        				"iDisplayLength": 100
    					});
					} );
			</script>
			</div>			
	</div>
	
</div>
