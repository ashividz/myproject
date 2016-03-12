@extends('lead.index')
@section('top')
<div id="product-edit">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title">Program</span></div>
        </div>
        <div class="panel-body">
            <form id="form-program" class="form-inline" method="POST">
                <div class="form-group">
                @foreach($programs as $program)

                    <div class="col-md-6">
                        <input type="checkbox" name="programs[{{$program->id}}]" {{ in_array($program->id, $array) ? 'checked' : '' }}> {{$program->name}}
                    </div>                                    
                @endforeach
                </div>
                <hr>
                <div class="form-group">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button id="submit" type="submit" class="btn btn-primary" disabled>Save</button> 
                {{ csrf_field() }}  
                </div>           
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$("input[type=checkbox]").on('change', function(e){
    if ($("input[type=checkbox]:checked").length === 0) {
        e.preventDefault();
        $("#submit").prop('disabled', true);
        return false;
    } else {
        $("#submit").prop('disabled', false);
    }
});
$('#form-program').on('submit', function (e) {
  if ($("input[type=checkbox]:checked").length === 0) {
      e.preventDefault();
      alert('Program required');
      return false;
  }
});
</script>
@endsection