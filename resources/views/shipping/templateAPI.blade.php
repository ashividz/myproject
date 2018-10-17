<div class="container">
   @if (Session::has('message2'))
   <div class="alert alert-success">
      <h2>{{ Session::get('message2') }}</h2>
   </div>
   @endif
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Delivery Status</h4>
      </div>
      <div class="panel-body">
        <div class="row shop-tracking-status">
            <div class="col-md-12">
               <div class="well">
                  @foreach($abc['response'] as $a) 
                  @if(($a['response'])>0)
                  @foreach($a['response'] as $ar )
                  <h4>Your order status:</h4>
                  <div class="row">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Status</th>
                              <th>AWB Number</th>
                              <th>Status Code</th>
                              <th>Delivery Status</th>
                              <th>Date</th>
                              <th>Location</th>
                              <th>Reason Code</th>
                              <th>Reason</th>
                              <th>Remark</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>
                                 @if($a['status']=='SUCCESS')
                                 <h5><span class="label label-success">{{ $a['status'] }}</span></h5>
                                 @else
                                 <h5>
                                 <span class="label label-danger">
                                    <h5>{{ $a['status'] }}
                                 </span>
                                 </h5>
                                 @endif
                              </td>
                              <td>
                                 <h5><span class="label label-info">{{ $a['awbNumber'] }}</span></h5>
                              </td>
                              <td>
                                 <h5><span class="label label-primary">{{ $ar['statusCode'] }}</span></h5>
                              </td>
                              <td>
                                 <h5><span class="label label-info">{{ $ar['status'] }}</span></h5>
                              </td>
                              <td>
                                 <h5><span class="label label-warning">{{ $ar['date'] }}</span></h5>
                              </td>
                              <td>
                                 <h5><span class="label label-info">{{ $ar['location'] }}</span></h5>
                              </td>
                              <td>
                                 <h5><span class="label label-info">{{ $ar['reasonCode'] }}</span></h5>
                              </td>
                              <td>
                                 <h5><span class="label label-info">{{ $ar['reason'] }}</span></h5>
                              </td>
                              <td>
                                 <h5>
                                    @if($ar['reason']!= '')
                                    <span class="label label-info">{{ $ar['remark'] }}</span>
                                    @else
                                    @endif
                                    <span class="label label-danger">No Remark</span>
                                 </h5>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  
                  @endforeach
                  @else
                  <h1>You entered the wrong key</h1>
                  @endif
                  @endforeach
               </div>
            </div>
         </div>         
      </div>
   </div>
   
   
</div>