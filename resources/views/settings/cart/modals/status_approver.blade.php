<div class="container">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <div class="col-md-6">
        <div class="panel panel-default"  width="20%">
            <div class="panel-heading">
                <div class="panel-title">Discount Approver </div>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Approver Role</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                @foreach($status->approvers as $approver)
                        <tr id="tr{{ $approver->pivot->id }}">
                            <td>
                                <span class='editable_approver' id='{{ $approver->pivot->id }}'>{{ $approver->display_name }}</span>
                            </td>
                            <td>
                                <a href="#" onclick="deleteApprover({{ $approver->pivot->id }})">
                                    <i class="fa fa-close red"></i>
                                </a>
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
                <div class="panel-title">Add Approver</div>
            </div>
            <div class="panel-body">
                <form method="post" action="/settings/cart/status/{{ $status->id }}/approver/add" class="form-inline">
                    <div class="form-group">
                        <label>Approver : </label>
                        <select name="approver_role_id" class="form-control">
                            <option value="">Select Approver</option>
                    
                    @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                    @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        {{ csrf_field() }}
                        <input type="hidden" name="status_id", value="{{ $status->id }}"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    function deleteApprover(id) {
        var r=confirm("Are you sure you want to delete?");
        if (r==true){
            var url = "/settings/cart/status/approver/"+id+"/delete"; //
            $.ajax(
            {
               type: "POST",
               url: url,
               data: {_token : "{{ csrf_token() }}"},
               success: function(data)
               {
                    $('#alert').show();
                    $('#alert').empty().append(data);
                    $("#alert").removeClass("alert-danger");
                    $("#alert").addClass("alert-success");
                    setTimeout(function()
                    {
                        $('#alert').slideUp('slow').fadeOut(function() 
                        {
                            $('#tr'+id).remove();

                         });
                    }, 3000);
               }
            });
        }
    }
</script>