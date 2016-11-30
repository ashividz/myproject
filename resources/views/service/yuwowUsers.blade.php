<div class="container"> 
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left">
            <h4>yuwow Users({{$name}})</h4> 
            </div>
            <div class="pull-right">
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('service') || Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('yuwow_support'))
                    @include('partials/users')
                @endif
            </div>            
        </div>

        <div class="panel-body">                                               
                    <table id="yuwowUsers" class="table table-striped table-bordered">
                        
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Patient id</th>
                                <th>YuWoW Registered date</th>
                                <th>First Use</th>
                                <th>Last Use</th>
                                <th>Total Usage</th>
                                <th>Program Start</th>
                                <th>Program End</th>
                            </tr>
                        </thead>
                        <tbody>

                
                <?php $x=0;?>
                @if($yuwowUsers)
                @foreach($yuwowUsers AS $yuwowUser)
                    <tr>
                        <td>{{++$x}}</td>
                        <td><a href="{{url('lead/'.$yuwowUser->lead->id.'/viewContactDetails')}}" target="blank">{{$yuwowUser->lead->name}}</a></td   >
                        <td><a href="{{url('lead/'.$yuwowUser->lead->id.'/viewContactDetails')}}" target="blank">{{$yuwowUser->id}}</a></td>
                        @if($yuwowUser->lead->yuwow)
                            <td>{{date("F j, Y ",strtotime($yuwowUser->lead->yuwow->user_registered))}}</td>
                            @if($yuwowUser->lead->yuwow->firstUseDate())
                                <td>{{date("F j, Y ",strtotime($yuwowUser->lead->yuwow->firstUseDate()))}}</td>
                            @else
                                <td></td>
                            @endif                            
                            @if($yuwowUser->lead->yuwow->lastUseDate())
                                <td>{{date("F j, Y ",strtotime($yuwowUser->lead->yuwow->lastUseDate()))}}</td>
                            @else
                                <td></td>
                            @endif                            
                            <td>{{$yuwowUser->lead->yuwow->totalUsage()}}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        
                        <td>{{date("F j, Y",strtotime($yuwowUser->fee->start_date))}}</td>
                        <td>{{date("F j, Y",strtotime($yuwowUser->fee->end_date))}}</td>
                    </tr>
                @endforeach
                @endif
                


                        </tbody>
                </table>
        </div>

            
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#yuwowUsers').dataTable({
        bPaginate : false,
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
        },
    });
});

</script>
