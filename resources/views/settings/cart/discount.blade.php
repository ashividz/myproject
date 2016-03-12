<div class="container">
    <div class="col-md-6">
        <div class="panel panel-default"  width="20%">
            <div class="panel-heading">
                <div class="panel-title">Discount Approver</div>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Discount</th>
                            <th>Approvers</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                @foreach($discounts as $discount)
                        <tr>
                            <td>
                                <span class='editable_method' id='{{ $discount->id }}'>{{ $discount->value }}</span>
                            </td>
                            <td>
                        
                        @foreach($discount->approvers as $approver)
                                {{ $approver->display_name or ""}} |
                        @endforeach     

                            </td>
                            <td>
                                <a data-toggle="modal" data-target="#modal" href="/settings/cart/discount/{{ $discount->id }}/approver"> <i class="fa fa-edit"></i></a>
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
                <div class="panel-title">Add Discount</div>
            </div>
            <div class="panel-body">
                <form method="post" action="/settings/cart/discount/add" class="form-inline">
                    <div class="form-group">
                        <label>Discount Value : </label>
                        <input type="text" name="value" class="form-control"></input>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        {{ csrf_field() }}
                    </div>
                </form>
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

    $(".editable_method").editable("/settings/cart/discount/update", { 
        type      : "text",
        submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
        cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
        placeholder: '<span class="placeholder">(Edit)</span>',
    });

});
</script>

@include('partials.modal')