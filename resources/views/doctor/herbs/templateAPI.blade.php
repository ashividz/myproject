<div class="container">
    @if (Session::has('message2'))
	   <div class="alert alert-success"><h2>{{ Session::get('message2') }}</h2></div>
	@endif
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>API Testing</h4>
      </div>
      <div class="panel-body">
         <!-- <form id="form-template" method="POST" class="form">
            <div class="form-group">
               <select name="herb" id="herb">
                  <option value="">Select Herb</option>
                  
               </select>
            </div>
            <div class="form-group">
               <input type="text" name="quantity" id="quantity" size="3" placeholder="Quantity">
               <select name="unit" id="unit">
                  <option value="">Select Unit</option>
                  
               </select>
            </div>
            <div class="form-group">
               <textarea name="remark" cols="30"></textarea>
            </div>
            <div class="form-group">
               <select name="mealtimes[]" id="mealtimes" multiple size='7'>
                  
               </select>
            </div>
            <div class="form-group">
               <button type='submit' class='btn btn-primary'>Submit</button>
               <button type='reset' class='btn btn-danger'>Cancel</button>
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
         </form> -->
          <form class="form-inline" method="POST" enctype="multipart/form-data" id="form-template" action="{{url('/herb/test')}}" >
            <div class="form-group">
              <label for="Order_No">Order No.</label>
              <input type="text" class="form-control" id="txtOrder" name="txtOrder" placeholder="Enter your order number">
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit">Test</button>
          </form>
      </div>
   </div>
    
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Herb Templates</h4>
      </div>
      <div class="panel-body">
         
      </div>
   </div>
</div>

