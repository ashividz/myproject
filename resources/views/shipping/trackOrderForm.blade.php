<div class="container">
   @if (Session::has('message2'))
   <div class="alert alert-success">
      <h2>{{ Session::get('message2') }}</h2>
   </div>
   @endif
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Check Your Order Status</h4>
      </div>
      <div class="panel-body">
         
         <form class="form-inline" method="POST" enctype="multipart/form-data" id="form-template" action="{{url('/shipping/orderStatus')}}" >
            <div class="form-group">
               <label for="Order_No">Order No.</label>
               <input type="text" class="form-control" id="txtOrder" name="txtOrder" placeholder="Enter your order number">
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit">Test</button>
            <button type='reset' class='btn btn-danger'>Reset</button>
            
         </form>
      </div>
   </div>
</div>