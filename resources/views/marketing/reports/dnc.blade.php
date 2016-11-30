<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left">
                @include('partials/daterange')
            </div>
        </div>
        <div class="panel-body">

            <table class="table table-bordered">                
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Patient Id</th>
                        <th width="40%">Dispositions</th>
                        <th width="40%">Remark</th>
                        <th></th>
                    </tr>
                </thead>
                    
                <tbody>

            @foreach($dncs as $dnc)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><a href="/lead/{{$dnc->lead->id}}/viewDispositions" target="_blank">{{$dnc->lead->name}}</a></td>                        
                        <td><a href="/patient/{{$dnc->lead->patient->id or ""}}/diets" target="_blank">{{$dnc->lead->patient->id or ""}}</a></td>
                    
                    @if($dnc->lead->dispositions->isEmpty())
                        <td></td>
                    @else
                        <td>
                            <ul>
                            @foreach($dnc->lead->dispositions as $disposition)
                                <li>{{$disposition->name}} : <b>({{$disposition->master->disposition}})</b> {{$disposition->remarks}}
                                    <span class="pull-right"><em><small>{{$disposition->created_at->format('jS M, Y h:i A')}}</em></small></span>
                                </li>
                            @endforeach
                            </ul>
                        </td>
                    @endif
                        <td>{{$dnc->remark}}</td>
                        <td>
                            <div data-toggle="popover" data-html="true" data-placement="left" data-content="<b>Created By</b> : {{$dnc->user->employee->name}}<p><b>Created At</b> : {{$dnc->created_at->format('jS, M Y h:i A')}}">
                                <i class="fa fa-info-circle"></i>
                            </div>
                        </td>
                    </tr>

            @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" }); 
});
</script>
<style type="text/css">
    .popover {
        text-align: left;
        max-width: 1250px;
    }
</style>