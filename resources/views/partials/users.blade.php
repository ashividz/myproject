<div id="">
	<form class="form-inline" action="" method="POST" role="form" id="formx">
        <div class="form-group">
        	<select name="user" id="user">
        		<option>Select User</option>

        	@foreach($users AS $user)

                @if($user->name == $name)
                    <option value="{{$user->name}}" selected>{{$user->name}}</option>
                @else
                    <option value="{{$user->name}}">{{$user->name}}</option>
                @endif

        	@endforeach	

        	</select>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
<script type="text/javascript">
$(document).ready(function () 
{
    $('select[name="user"]').change(function()
    {    
        $('#formx').submit();
    });
});
</script>