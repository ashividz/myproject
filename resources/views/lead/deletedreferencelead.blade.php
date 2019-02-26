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
          <td>Lead id</td>
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
        <th>Name</th>
        <th>Phone</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Refree</th>
        <th>Nutritionist</th>
        <th>Created_by</th>

      </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
      <tr>
        <td>{{$x++}}</td>
        <td>{{$user->lead_id}}</td>
        <td>{{$user->name}}</td>
        <td>{{$user->phone}}</td>
        <td>{{$user->mobile}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->refree_lead_id}}</td>
        <td>{{$user->nutritionist}}</td>
        <td>{{$user->created_by}}</td>
      </tr>
     @endforeach
    </tbody>
  </table>
</div>
  </div>
 