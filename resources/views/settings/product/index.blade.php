<div class="container">
    <div class="col-md-12">

        <div>

            <a data-toggle="modal" data-target="#modal" href="/settings/product/add" class="btn btn-primary"  style="margin: 30px 0px"> 
                Add Product
            </a>

            <a data-toggle="modal" data-target="#modal" href="/settings/product/categories" class="btn btn-warning"  style="margin: 30px 10px">Product Categories</a>

            <!--<a data-toggle="modal" data-target="#modal" href="/settings/product/units" class="btn btn-warning"  style="margin: 30px 10px">Product Units</a>-->
        </div>

@foreach($categories as $category)
        <div class="panel panel-default"  width="20%">
            <div class="panel-heading">
                <span class="panel-title">{{ $category->name }}</span>
                <span class="pull-right">
                    
                </span>

            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Duration</th>
                            <th>Domestic Price (₹) </th>
                    @if($category->id == 1)
                            <th>International Price (₹)</th>
                            <th>International Price ($)</th>
                    @endif
                            <th>Offer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                @foreach($category->products as $product)
                        <tr style="border-bottom: 1px solid #d9d9d9;">
                            <td>
                                {{ $product->name }}
                            </td>
                            <td>    
                                {!! html_entity_decode($product->description) !!}
                            </td>
                            <td>
                                {{ $product->duration }}
                            </td>
                            <td>
                                {{ $product->domestic_price_inr }}
                            </td>

                    @if($category->id == 1)
                            <td>
                                {{ $product->international_price_inr }}
                            </td>
                            <td>
                                {{ $product->international_price_usd }}
                            </td>
                    @endif
                            
                            <td>
                                <a data-toggle="modal" data-target="#modal" href="/settings/product/{{ $product->id }}/offer" class="btn btn-{{ $product->offers->isEmpty() ? 'danger' : 'primary'}}">
                                    <i class="fa fa-gift"></i>
                                </a>
                            </td>
                            <td>
                                <a data-toggle="modal" data-target="#modal" href="/settings/product/{{ $product->id }}/offer" class="btn btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                @endforeach

                    </tbody>
                </table>
            </div>
        </div>

@endforeach
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{   
    $.fn.editable.defaults.params = function (params) {
        params._token = "{{ csrf_token() }}";
        return params;
    };

    $(".editable_method").editable("/settings/cart/product/update", { 
        type      : "text",
        submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
        cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
        placeholder: '<span class="placeholder">(Edit)</span>',
    });

});
</script>

@include('partials.modal')