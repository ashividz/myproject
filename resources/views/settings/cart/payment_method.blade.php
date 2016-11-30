<div class="container">
    <div class="col-md-6">
        <div class="panel panel-default"  width="20%">
            <div class="panel-heading">
                <div class="panel-title">Payment Method</div>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Approvers</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                @foreach($methods as $method)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>
                                <span class='editable_method' id='{{ $method->id }}'>{{ $method->name }}</span>
                            </td>
                            <td>
                        
                        @foreach($method->approvers as $approver)
                                {{ $approver->display_name }} | 
                        @endforeach     

                            </td>
                            <td>
                                <a data-toggle="modal" data-target="#modal" href="/settings/cart/payment/method/{{ $method->id }}/approver"> <i class="fa fa-edit"></i></a>
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
                <div class="panel-title">Add New Payment Method</div>
            </div>
            <div class="panel-body">
                <form method="post" action="/settings/cart/payment/method/add" class="form-inline">
                    <div class="form-group">
                        <label>Name : </label>
                        <input type="text" name="name" class="form-control"></input>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
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

    $(".editable_method").editable("/settings/cart/payment/method/update", { 
        type      : "text",
        submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
        cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
        placeholder: '<span class="placeholder">(Edit)</span>',
    });

});
</script>

@include('partials.modal')