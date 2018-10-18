
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
			         
			         <form class="form-inline" method="POST" enctype="multipart/form-data" id="form-template" action="{{url('/shipping/orderStatus')}}" >
			            <div class="form-group">
			               <label for="Order_No">Order No.</label>
			               <input type="text" class="form-control" id="txtOrderNo" name="txtOrderNo" placeholder="Enter your order number" required="required">
			            </div>
			            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			            <button type="submit" class="btn btn-success" name="btnSubmit" id="btnSubmit">Test</button>
			            <button type='reset' class='btn btn-danger'>Reset</button>
			            
			         </form>
			      </div>
			   </div>
			</div>
		</div>
		<!-- Inner Container End -->
 
		
	</div>
	<!-- Main Container End -->
 
</div>
@endsection
