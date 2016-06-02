<div class="container" style="padding:20px">
    <form action="/cart/{{ $cart->id }}/comment" method="POST" class="form-inline">
        {{ csrf_field() }}
        <input type="hidden" name="created_by" value="{{ Auth::id() }}">
        <div class="form-group">
            <label>Comment</label>
            <div>
                <textarea name="text" cols="30"> </textarea>
            </div>            
        </div>
        <div class="form-group">
            <button data-dismiss="modal" class="btn btn-danger">Cancel</button>
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
</div>