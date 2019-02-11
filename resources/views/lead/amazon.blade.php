<div class="container">
  
  <div class="col-md-5">
      <form class="form-horizontal well" id="form" action="" method="post" enctype="multipart/form-data">     
      <fieldset>
        <legend>Comment</legend>
        <div class="control-group">
          <div>
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="4" id="comment" name="disposition"></textarea>
          </div>
        </div>
        <hr>
        <div class="control-group">
          <div class="controls">
          <button type="submit" id="upload" name="upload" class="btn btn-primary button-loading" data-loading-text="Loading...">Save</button>
          </div>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </div>
      </fieldset>
    </form>
  </div>
  <div class="col-md-7">
    <fieldset class="well">
      <h4>User Details</h4>
      <table class="table table-bordered">
        <thead>
          <tr>
            <td>Name</td>
            <td>Phone</td>
            <td>Amount</td>
            <td>Doctor</td>
            <td>Source</td>
        </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$lead->name}}</td>
            <td>{{$lead->phone}}</td>
            <td>{{$lead->amount}}</td>
            <td>{{$lead->doctor}}</td>
            <td>{{$lead->source}}</td>
          </tr>
        </tbody>
        
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
        <th>#</th>
        <th>Disposition</th>
        <th>Created_by</th>
        <th>Created_at</th>
      </tr>
    </thead>
    <tbody>
      @foreach($lead->dispositions as $disposition)
        <tr>
          <td>{{$x++}}</td>
          <td>{{$disposition->disposition}}</td>
          <td>{{$disposition->created_by}}</td>
          <td>{{$disposition->created_at}}</td>
        </tr>
      @endforeach
    </tbody>
   
  </table>
</div>
  </div>
 