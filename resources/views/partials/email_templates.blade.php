  <form class="form-inline" action="" method="POST" role="form" id="formx">
        
        <div class="form-group">
          <select name="template_id" id="template_id">
            <option>Select template</option>

          @foreach($templates AS $template)

                @if($template->id == $template_id)
                    <option value="{{$template->id}}" selected>{{$template->subject}}</option>
                @else
                    <option value="{{$template->id}}">{{$template->subject}}</option>
                @endif

          @endforeach 

          </select>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      </form>

<script type="text/javascript">
$(document).ready(function () 
{
    $('select[name="template_id"]').change(function()
    {    
        $('#formx').submit();
    });
});
</script>