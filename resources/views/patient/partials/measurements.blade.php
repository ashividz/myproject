@extends('patient.index')
@section('top')
<div class="col-md-12">  
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Measurements</div>
        </div>
        <div class="panel-body">           
            
            <div style="height:0.1em;"></div>
            @if($patient->measurements->isEmpty() and DB::table('fitness_details')->where('clinic', $patient->clinic)->where('registration_no', $patient->registration_no)->get())
            <div>
                <div class="col-md-4"></div>
                <button class="btn btn-primary" id="measurements-copy" value="{{$patient->id}}" >Copy from Old CRM</button>            
            </div>
            @else
            <form id="form-measurements" method="POST" class="form" >
                <div class="form-group">
                    {{csrf_field()}}
                    <input type="text" name="arms" placeholder="Arms(cm)" size="6">
                    <input type="text" name="chest" placeholder="Chest(cm)" size="6">
                    <input type="text" name="waist" placeholder="Waist(cm)" size="6">
                    <input type="text" name="abdomen" placeholder="Abdomen(cm)" size="6">
                    <input type="text" name="thighs" placeholder="Thighs(cm)" size="6">                    
                    <input type="text" name="hips" placeholder="Hips(cm)" size="6">
                    <input type="text" name="bp_systolic" placeholder="BP(Systolic, mm Hg)" size="18">                    
                    <input type="text" name="bp_diastolic" placeholder="BP(Diastolic, mm Hg)" size="18">                    
                    <button class="btn btn-primary" >Save</button>
                </div>
            </form>
            @endif            
        </div>
    </div>        
</div>
</div>  
@endsection

@section('main')
<div>

    @if($vediqueDiet)
    <div class="container">  
        <div class="panel panel-default">
            <div class="panel-heading1">
            </div>
            <h3>Vedique Diet mesurement</h3>
            <div class="panel-body">
                <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Arms(cm)</th>
                                <th>Chest(cm)</th>
                                <th>Waist(cm)</th>
                                <th>Abdomen(cm)</th>
                                <th>Thighs(cm)</th>
                                <th>Hips(cm)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($vediqueDiet as $measurement)
                            <tr>
                                <td>{{date("jS M, Y, g:i a",strtotime($measurement->created_at))}}</td>
                                <td>{{$measurement->arms!=0 ? $measurement->arms :''}}</td>
                                <td>{{$measurement->chest!=0 ? $measurement->chest:''}}</td>
                                <td>{{$measurement->waist!=0 ? $measurement->waist:''}}</td>
                                <td>{{$measurement->abdomen!=0 ? $measurement->abdomen:''}}</td>
                                <td>{{$measurement->thighs!=0 ?  $measurement->thighs : ''}}</td>
                                <td>{{$measurement->hips!=0 ? $measurement->hips : ''}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    @endif
    @if($patient->measurements->count()>0)
    <div class="container">  
        <div class="panel panel-default">
            <div class="panel-heading1">
            </div>
            <h3>Amikus Mesurement</h3>
            <div class="panel-body">
                <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Arms(cm)</th>
                                <th>Chest(cm)</th>
                                <th>Waist(cm)</th>
                                <th>Abdomen(cm)</th>
                                <th>Thighs(cm)</th>
                                <th>Hips(cm)</th>
                                <th>BP(Systolic, mm Hg)</th>
                                <th>BP(Diastolic, mm Hg)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($patient->measurements as $measurement)
                            <tr>
                                <td>{{date("jS M, Y, g:i a",strtotime($measurement->created_at))}}</td>
                                <td>{{$measurement->arms!=0 ? $measurement->arms :''}}</td>
                                <td>{{$measurement->chest!=0 ? $measurement->chest:''}}</td>
                                <td>{{$measurement->waist!=0 ? $measurement->waist:''}}</td>
                                <td>{{$measurement->abdomen!=0 ? $measurement->abdomen:''}}</td>
                                <td>{{$measurement->thighs!=0 ?  $measurement->thighs : ''}}</td>
                                <td>{{$measurement->hips!=0 ? $measurement->hips : ''}}</td>
                                <td>{{$measurement->bp_systolic!=0 ?  $measurement->bp_systolic : '' }}</td>
                                <td>{{$measurement->bp_diastolic!=0 ?  $measurement->bp_diastolic : ''}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    @endif
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#measurements-copy').on('click', function(){
        
        var url = '/patient/'+this.value+'/measurements/copy';
        $.ajax(
        {
           type: "POST",
           url: url,
           data: {_token : '{{ csrf_token() }}'},
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
           },
           error : function(data) {
                var errors = data.responseJSON;

                console.log(errors);

                $('#alert').show();
                $('#alert').empty();
                $.each(errors, function(index, value) {
                    $('#alert').append("<li>"+value+"</li>");
                });

                setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        //location.reload();
                     });
                }, 3000);
           }
        });
    });
});
</script>

@endsection