<div class="container" style="margin-top: 30px">
    <div class="col-md-6">
        <div class="panel panel-default"  width="20%">
            <div class="panel-heading">
                <div class="panel-title">Product offers</div>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>                            
                            <th>Min Quantity</th>
                            <th>Offer Name</th>
                            <th>Offer Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($offers as $offer)
                        <tr>
                            <td>
                                {{$offer->minimum_quantity}}
                            </td>
                            <td>
                                {{ $offer->product->category->name or "" }} : {{ $offer->product->name or "" }}
                            </td>
                            <td>
                                {{ $offer->product_offer_quantity }}
                            </td>
                        </tr>
                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default"  width="20%">
            <div class="panel-heading">
                <div class="panel-title">Add New Product Offer</div>
            </div>
            <div class="panel-body">
                <form method="post" action="/settings/product/{{ $product_id }}/offer/add" class="form-inline">
                    <div class="form-group">
                        <input type="text" name="minimum_quantity" class="form-control" placeholder="Min Quantity" size="6" required></input>
                    </div>
                    <div class="form-group">
                        <select name="product_offer_id" class="form-control" required>
                            <option value="">Select Product</option>
                    
                    @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->category->name }} : {{ $product->name }}</option>
                    @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="product_offer_quantity" class="form-control" placeholder="Offer Quantity" size="6" required></input>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        {{ csrf_field() }}
                        <input type="hidden" name="product_id" value="{{ $product_id }}"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>
            
</div>
<script type="text/javascript">
$(document).ready(function() 
{   
    $.fn.editable.defaults.params = function (params) {
        params._token = "{{ csrf_token() }}";
        return params;
    };

    $(".editable_name").editable("/settings/product/offer/update", { 
        type      : "text",
        submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
        cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
        placeholder: '<span class="placeholder">(Edit)</span>',
    });

});
</script>

@include('partials.modal')