<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">{{ $product ? 'Edit' : 'Add' }} Product</div>
        </div>
        <div class="panel-body">
            <form method="post" action="/settings/product/{{ $product ? $product->id : 'add' }}" class="form-inline">
            @if($product)
                {{ method_field('patch') }}
            @endif

                <div class="class-form">
                    <select name="product_category_id" class="form-control" required>
                        <option value="">Select Category*</option>
                @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product && $product->product_category_id == $category->id? "selected": "" }}>{{ $category->name }}</option>
                @endforeach
                    </select>

                    <input name="name" class="form-control" value="{{ $product->name or ""}}" placeholder="Name*" required>

                    <textarea name="description" placeholder="Description" class="form-control">{{ $product->description or "" }}</textarea>

                    <input name="duration" class="form-control" value="{{ $product->duration or "" }}" placeholder="Duration" size="4">

                    <input type="text" name="domestic_price_inr" class="form-control" value="{{ $product->domestic_price_inr or "" }}" placeholder="DOM ₹" size="5" required>

                    <input type="text" name="international_price_inr" class="form-control" value="{{ $product->international_price_inr or "" }}" placeholder="INT ₹" size="5">

                    <input type="text" name="international_price_usd" class="form-control" value="{{ $product->international_price_usd or "" }}" placeholder="INT $" size="5">
                </div>

                <div class="class-form">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary">Save</button> 
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
            
</div>
<style type="text/css">
.form-control {
    margin: 20px 5px;
}
</style>

@include('partials.modal')