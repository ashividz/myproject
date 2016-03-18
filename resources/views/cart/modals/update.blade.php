<div id="product-edit">
    <div class="panel panel-default">
        <div class="panel-heading">
            Update Registration Process
        </div>
        <div class="panel-body">
            <form class="form-inline" action="/cart/{{$cart->id}}/approval/update" method="POST">
                <textarea name="remark" class="form-control"></textarea>
                <div class="form-group">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button> 
                {{ csrf_field() }}  
                </div>           
            </form>
        </div>
    </div>
</div>