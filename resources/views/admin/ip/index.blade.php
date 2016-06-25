<div class="col-md-12">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">IP Roles</div>
        </div>
        <div class="panel-body">
            <form method="post" class="form" action="/admin/ip/roles/save">
                <div class="form-group">
                    {{csrf_field()}}                    
                    <label for="ip_start">ip start :</label>
                    <input type="text" id="ip_start" name="ip_start" placeholder="192.168.1.1" size="15"/>
                    <label for="ip_end">ip end :</label>
                    <input type="text" id="ip_end" name="ip_end" placeholder="192.168.1.1" size="15"/>
                    <select name="role_id">
                    @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->display_name}}</option>
                    @endforeach
                    </select>                    
                    <button class="btn btn-primary" >Save</button>
                </div>
            </form>
            <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>ip range</th>                    
                </tr>
            </thead>
            <tbody>                    
            @foreach($roles as $role)
                <?php  
                    $ips = $ipRoles->where('role_id',$role->id)->all();
                ?>
                <tr>
                    <td>{{$role->display_name}}</td>
                    <td>
                        @foreach($ips as $ip)
                            <small>[ {{($ip->ip_start == $ip->ip_end) ? $ip->ip_start : $ip->ip_start.' - '.$ip->ip_end }} ]<a href="#" id="{{$ip->id}}" onclick="deleteIPRange(this.id)"><i class="glyphicon glyphicon-remove red"></i></a>&nbsp; </small>                                
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>            
        </div>
    </div>        
</div>
<script type="text/javascript">
    function deleteIPRange(id) {

        console.log(id);
        var r=confirm("Are you sure you want to delete the range?");
        if (r==true){
            var url = "/admin/ip/role/delete"; //
            $.ajax(
            {
               type: "GET",
               url: url,
               data: {id : id}, // send Source Id.
               success: function(data)
               {
                   $('#alert').show();
                   $('#alert').empty().append(data);
                   setTimeout(function()
                    {
                        $('#alert').slideUp('slow').fadeOut(function() 
                        {
                            location.reload();
                         });
                    }, 3000);
               }
            });
        };
    };
</script>
<script>
$(document).ready(function(){
    $("#ip_start").blur(function(){
        ip_start = $("#ip_start").val();
        ip_end   = $("#ip_end").val();
        if( ip_end.trim() == '') {            
            $("#ip_end").val(ip_start);
        }
    });
});
</script>
