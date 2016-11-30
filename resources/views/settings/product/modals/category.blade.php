<div class="container" style="margin-top: 30px">
    <div class="col-md-6">
        <div class="panel panel-default"  width="20%">
            <div class="panel-heading">
                <div class="panel-title">Product Categories</div>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($categories as $category)
                        <tr>
                            <td>
                                <span class='editable_name' id='{{ $category->id }}'>{{ $category->name }}</span>
                            </td>
                            <td>
                                <span class='editable_unit' id='{{ $category->id }}'>{{ $category->unit }}</span>
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
                <div class="panel-title">Add New Product Category</div>
            </div>
            <div class="panel-body">
                <form method="post" action="/settings/product/category/add" class="form-inline">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Product Name"></input>
                    </div>
                    <div class="form-group">
                        <input type="text" name="unit" size="7" class="form-control" placeholder="Unit"></input>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        {{ csrf_field() }}
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

    $(".editable_name").editable("/settings/product/category/name/update", { 
        type      : "text",
        submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
        cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
        placeholder: '<span class="placeholder">(Edit)</span>',
    });

    $(".editable_unit").editable("/settings/product/category/unit/update", { 
        type      : "text",
        submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
        cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
        placeholder: '<span class="placeholder">(Edit)</span>',
    });

});
</script>

@include('partials.modal')