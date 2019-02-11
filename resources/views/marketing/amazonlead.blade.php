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
          <td>Name</td>
          <td>Phone</td>
          <td>Amount</td>
          <td>Doctor</td>
          <td>Source</td>
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
        <th>Amazon Lead id</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Amount</th>
        <th>Doctor</th>
        <th>Source</th>
        <th>Created_by</th>
        <th>Created_at</th>

      </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
      <tr>
        <td>{{$x++}}</td>
        <td>{{$user->id}}</td>
        <td>{{$user->name}}</td>
        <td>{{$user->phone}}</td>
        <td>{{$user->amount}}</td>
        <td>{{$user->doctor}}</td>
        <td>{{$user->source}}</td>
        <td>{{$user->created_by}}</td>
        <td>{{$user->created_at}}</td>
      </tr>
     @endforeach
    </tbody>
  </table>
</div>
  </div>
 