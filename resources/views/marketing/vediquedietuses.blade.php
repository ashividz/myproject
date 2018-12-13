<div class="container"> 
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left">
            <h4>VediqueDiet Users({{$name}})</h4> 
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
                                <th>Nutritionist</th>
                                <th>Program Start Date</th>
                                <th>Program End Date</th>
                            </tr>
                        </thead>
                        <tbody>

                
                <?php $x=0;?>
                @if($patients )
                @foreach($patients  AS $patient)
                    <tr>
                        <td>{{++$x}}</td>
                        <td><a href="{{url('lead/'.$patient->lead->id.'/viewContactDetails')}}" target="blank">{{$patient->lead->name}}</a></td >
                        <td>{{$patient->nutritionist or " "}}</td>
                        <td>{{$patient->cfee->start_date or " "}}</td>
                        <td>{{$patient->cfee->end_date or " "}}</td>
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
