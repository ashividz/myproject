<div class="container" style="margin-top: 30px">
    <div class="col-md-6">
        <div class="panel panel-default"  width="20%">
            <div class="panel-heading">
                <div class="panel-title">Product Units</div>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($units as $unit)
                        <tr>
                            <td>
                                <span class='editable_name' id='{{ $unit->id }}'>{{ $unit->name }}</span>
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
                <div class="panel-title">Add New Product Unit</div>
            </div>
            <div class="panel-body">
                <form method="post" action="/settings/product/unit/add" class="form-inline">
                    <div class="form-group">
                        <label>Name : </label>
                        <input type="text" name="name" class="form-control"></input>
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

    $(".editable_name").editable("/settings/product/unit/update", { 
        type      : "text",
        submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
        cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
        placeholder: '<span class="placeholder">(Edit)</span>',
    });

});
</script>

@include('partials.modal')