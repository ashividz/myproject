<!-- <div>		
	<div class="container" style="margin-left: 35%">
		<form method="post" action="">
			<h4 style="margin-left: 8%"><strong>Enter Details</strong></h4>
		    <div class="form-group">
				    <input type="text"  name="lead" placeholder="Lead Id" maxlength="50" size="50" required>
		    </div>
		    <div class="form-group">
		        
		        <input type="text" name="cart"  placeholder="Cart ID" maxlength="50" size="50" required>
		    </div>
		    <div class="form-group">
		      
		        <input type="text" name="amount" placeholder="Amount"  maxlength="50" size="50" required>
		    </div>
		    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	    	<button type="submit" class="btn btn-primary" style="margin-left: 8%">Submit</button>
		</form>
	</div>
	<?php
	  $x //= 1;
	?>
	<table class="table table-bordered">
    <thead>
      <tr>
        <td>#</td>
        <th>Lead id</th>
        <th>Cart id</th>
        <th>Amount</th>
        <th>Cre Name</th>
        <th>Created_by</th>
        <th>Created_at</th>
      </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
      <tr>
      	
        <td>{{$user->lead_id}}</td>
        <td>{{$user->cart_id}}</td>
        <td>{{$user->amount}}</td>
        <td>{{$user->cre_name}}</td>
        <td>{{$user->created_by}}</td>
        <td>{{$user->created_at}}</td>
      </tr>
     @endforeach
    </tbody>
  </table>
</div>

 -->


 <div class="container">
  
  <div class="col-md-5">
      <form class="form-horizontal well" id="form" action="" method="post" enctype="multipart/form-data">     
      <fieldset>
        <legend>Upload CSV/Excel file</legend>
        <div class="control-group">
          <div>
            <label>CSV/Excel File:</label>
          </div>
          <div class="controls">
            <input type="file" name="file" id="file" class="input-large" required>
          </div>
        </div>
        <hr>
        <div class="control-group">
          <div class="controls">
          <button type="submit" id="upload" name="upload" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
          </div>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
      </fieldset>
    </form>
  </div>
  <div class="col-md-7">
    <fieldset class="well">
      <h4>Table Format</h4>
      <table class="table table-bordered">
        <tr>
          <td>LeadID</td>
          <td>Cart</td>
          <td>Amount</td>
          <td>Date</td>
        </tr>
      </table>
    </fieldset>
  <?php
    $x = 1;
  ?>
  </div>
  <div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <td>#</td>
        <th>Lead id</th>
        <th>Cart id</th>
        <th>Amount</th>
        <th>Cre Name</th>
        <th>Created_by</th>
        <th>Created_at</th>
      </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
      <tr>
        <td>{{$x++}}</td>
        <td>{{$user->lead_id}}</td>
        <td>{{$user->cart_id}}</td>
        <td>{{$user->amount}}</td>
        <td>{{$user->cre_name}}</td>
        <td>{{$user->created_by}}</td>
        <td>{{$user->created_at}}</td>
      </tr>
     @endforeach
    </tbody>
  </table>
</div>
  </div>
 