@extends('master')

@section('content')
<a href="/lead/{{$lead->id or $order->lead->id}}/cart/create/" class="dropdown-toggle" data-toggle="modal" data-target="#myModal">Order</a><hr>

<a href="/lead/{{$lead->id or $order->lead->id}}/program/add/" class="dropdown-toggle" data-toggle="modal" data-target="#myModal">Programs</a>
<script type="text/javascript">

new Vue({
    el : 'body',
    data : {
        products : []
    },
    methods : {
        formSubmit : function(){
            var product_ids = [];

            $("input[name='product_ids[]']:checked").each(function () {
                product_ids.push(parseInt($(this).val()));
            });

            this.setProducts(product_ids);  
        },

        setProducts : function(product_ids){
            var data = { ids : product_ids };

            this.$http.post('/api/products', data, function(products){
                
            })
        }
    }
})
</script>
<style type="text/css">
    #alert {
        position: absolute;
        z-index: 9999;
    }
</style>
    <form>
    <div class="container">
    		<div class="row">

    			<div class="col-md-4">

    				<div class="form-group">
    					<label class="control-label col-lg-4 col-sm-4">Name </label>
    					<label class="control-label">: {{$lead->name or ""}}</label>
    				</div>

    				<div class="form-group">
    					<label class="control-label col-lg-4 col-sm-4">Lead Id </label>
    					<label class="control-label">: {{$lead->id or ""}}</label>
    				</div>

    				<div class="form-group">
    					<label class="control-label col-lg-4 col-sm-4">Patient Id </label>
    					<label class="control-label">: {{$lead->patient->id or ""}}</label>
    				</div>
    				
    			</div>

    			<div class="col-md-4">
    				
    				@{{message}}
    			</div>

    			<div class="col-md-4">
    				
    				
    			</div>
    			
    		</div>
    		
			<table class="table table-striped">
		        <tr>
		            <th>Product</th>
		            <th>Description</th>
		            <th>Quantity</th>
		            <th>Price</th>
		            <th>Discount (%)</th>
		            <th>Total</th>
		            <th>@{{products}}</th>
		        </tr>
		        <tr>
		        	<td>
		        		<div id="el">
						  	<select v-select="selected" :options="options">
						    	<option value="0">default</option>
						  	</select>
						</div>
		        	</td>    
		            <td>@{{ item.description }}</td>           
		            <td><input type="text" size="3" ng:model="item.quantity" ng:required name="quantity"></td>
		            <td><input type="text" size="7" ng:model="item.price" ng:required name="price"></td>
		            <td><input type="text" size="4" ng:model="item.discount" ng:required name="discount"></td>
		            <td>
		            	@{{ total($index) | currency :currencySymbol}}
		            	<input type="hidden" ng-model="item.amount" readonly>
		            </td>
		            <td>
		                <a href ng:click="removeItem($index)"><i class="fa fa-minus-circle redlink" title="Remove Item"></i></a>
		            </td>
		        </tr>
		        <tr>		            
		            <td colspan="4"></td>
		            <td><h4>Total:</h4></td>
		            <td>
		            	<h4>@{{ invoice.total | currency:currencySymbol }}</h4>
		            	<input type="hidden" ng-model="invoice.total">
		            </td>
		            <td></td>
		        </tr>
		    </table>
		    <div class="form-actions">
		    	<a data-toggle="modal" data-target="#myModal" href="/lead/{{$lead->id}}/products/modal" class="btn btn-success">Add Product <i class="fa fa-save"></i></a>
		    	<a href ng:click="addItem()" class="btn btn-primary">Download PDF <i class="fa fa-download"></i></a>
		    	<a href ng:click="addItem()" class="btn btn-warning">Email Order <i class="fa fa-paper-plane"></i></a>
		    </div>

		</form>

	</div>

<!-- Modal Template-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Modal title</h4>

            </div>
            <div class="modal-body"><div class="te"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<style type="text/css">
    .modal-dialog {
        /* new custom width */
        width: 90%;
    }
</style>

<script type="text/javascript">
	$('#myModal').on('hidden.bs.modal', function () {
    //alert('fdsafds');
    })
</script>
@endsection