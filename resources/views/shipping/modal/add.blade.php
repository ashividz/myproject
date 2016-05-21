<div class="col-md-4 col-md-offset-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Add Shipping</div> 
        </div>
        <div class="panel-body">
            <form class="form" action="/cart/{{ $id }}/shipping" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                <div class="form-group">
                    <label class="col-md-5">Cart Id</label>
                    <input type="text" name="cart_id" value="{{ $id }}" readonly="true">
                </div>
                <div class="form-group">
                    <label  class="col-md-5">Carrier</label>
                    <select name="carrier_id">
                @foreach($carriers as $carrier)
                        <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label  class="col-md-5">Tracking Id</label>
                    <input type="text" name="tracking_id" required>
                </div>
                <div>
                    <button class="btn btn-primary">Save</button>
                    <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>