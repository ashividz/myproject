
@extends('master')

@section('content')

<!-- Start Main Wrapper -->
<div id="mws-wrapper">
	<!-- Necessary markup, do not remove -->
	<div id="mws-sidebar-stitch"></div>

 
	<!-- Main Container Start -->
	<div id="mws-container" class="clearfix">
		<!-- Date Select -->



		<!-- Inner Container Start -->
		<div class="inner">

			<div class="container" style="margin-top: 6em">
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
			         
			         <form class="form-inline" method="POST" enctype="multipart/form-data" id="form-template" action="{{url('/shipping/trackOrder')}}" >
			            <div class="form-group">
			               <label for="Order_No">Order No.</label>
			               <input type="text" class="form-control" id="txtOrderNo" name="txtOrderNo" placeholder="Enter your order number" required="required">
			            </div>
			            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			            <button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit">Test</button>
			            <button type='reset' class='btn btn-danger'>Reset</button>
			            
			         </form>
			      </div>

			      
				      <div class="panel panel-default">
				      <div class="panel-heading">
				         <h4>Delivery Status</h4>
				      </div>
				      <div class="panel-body">
				        <div class="row shop-tracking-status">
				            <div class="col-md-12">
				               <div class="well">
				               	  @if($orderData['response']>0)
				                  @foreach($orderData['response'] as $ord_data) 
				                  @if(($ord_data['response'])>0)
				                  @foreach($ord_data['response'] as $ord )
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
				                                 @if($ord_data['status']=='SUCCESS')
				                                 <h5><span class="label label-success">{{ $ord_data['status'] }}</span></h5>
				                                 @else
				                                 <h5>
				                                 <span class="label label-danger">
				                                    <h5>{{ $ord_data['status'] }}
				                                 </span>
				                                 </h5>
				                                 @endif
				                              </td>
				                              <td>
				                                 <h5><span class="label label-info">{{ $ord_data['awbNumber'] }}</span></h5>
				                              </td>
				                              <td>
				                                 <h5><span class="label label-primary">{{ $ord['statusCode'] }}</span></h5>
				                              </td>
				                              <td>
				                                 <h5><span class="label label-info">{{ $ord['status'] }}</span></h5>
				                              </td>
				                              <td>
				                                 <h5><span class="label label-warning">{{ $ord['date'] }}</span></h5>
				                              </td>
				                              <td>
				                                 <h5><span class="label label-info">{{ $ord['location'] }}</span></h5>
				                              </td>
				                              <td>
				                                 <h5><span class="label label-info">{{ $ord['reasonCode'] }}</span></h5>
				                              </td>
				                              <td>
				                                 <h5><span class="label label-info">{{ $ord['reason'] }}</span></h5>
				                              </td>
				                              <td>
				                                 <h5>
				                                    @if($ord['reason']!= '')
				                                    <span class="label label-info">{{ $ord['remark'] }}</span>
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
				                  <div class="alert alert-warning" id="success-alert">
				                      <button type="button" class="close" data-dismiss="alert">x</button>
				                      <strong><h1>Warning! </h1></strong>
				                      <h3>You entered the wrong key</h3>
				                  </div>
				                  @endif
				                  @endforeach
				                  @endif
				               </div>
				            </div>
				         </div>         
				      </div>
				   </div>
      


			   </div>
			</div>
		</div>
		<!-- Inner Container End -->
 
		
	</div>
	<!-- Main Container End -->
 
</div>
@endsection