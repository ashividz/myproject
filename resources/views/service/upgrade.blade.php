<div class="jumbotron" style="margin:0;padding:0;">
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
                <h4>Upgrade Report</h4>
            </div>
            <div class="pull-right">
                @include('partials/daterange')
            </div>
        </div>
        <div class="panel-body">
                <table id="feedback" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lead</th>
                            <th>Name</th>
                            <th>Nutritionist</th>
                            <th>Doctor</th>
                            <th>Pkg Taken</th>
                            <th>Wt Loss</th>
                            <th>Final BMI</th>
                            <th>Upgraded on</th>
                            <th>Total upgrade count</th>
                            <th>Total weight loss</th>
                            <th>CSAT score</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key=>$user)

                        <?php 

                           // $show_date = date('Y-m-d', strtotime($end_date));
                            $show_date = date_format($user->fee->end_date,"Y/m/d H:i:s");

                            if($show_date >= $end_date)
                            {
                                $pkg_taken = $user->fees[1]->end_date->diffInDays($user->fees[1]->start_date);
                                $upgradeon =  date("F jS, Y", strtotime($user->fee->start_date));
                            }
                            else
                            {
                                $pkg_taken = $user->fee->end_date->diffInDays($user->fee->start_date);
                                $upgradeon = " ";
                            }

                            // $upgradeon = " ";
                            // if($user->cfee)
                            // {
                            //     if($user->cfee->end_date < $user->fee->start_date)
                            //     {
                            //         $upgradeon = $user->fee->start_date;
                            //     }
                            // }
                            // else
                            // {
                            //     if($user->fee->start_date > date("Y-m-d H:i:s", time()))
                            //     {
                            //         $upgradeon =  $user->fee->start_date;
                            //     }
                            //     else
                            //     {
                            //          $upgradeon = " ";
                            //     }
                            // }
                                                        
                        ?>
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$user->lead->id}}</td>
                            <td><a href="{{url('lead/'.$user->lead->id.'/viewDetails')}}" target="blank">{{$user->lead->name}}</a></td>
                            <td>{{$user->nutritionist}}</td>
                            <td>{{$user->doctor}}</td>
                            <td>{{$pkg_taken}}</td>
                            <td>{{round($user->current_program_initial_weight['weight'] - $user->current_program_final_weight['weight'] , 2)}}</td>
                            <td>{{$user->finalBMI}}</td>
                            <td>{{$upgradeon or " "}}</td>
                            <td>{{$user->totalUpgrade}}</td>
                            <td>{{round($user->initialWeight['weight'] - $user->finalWeight['weight'], 2 )}}</td>
                            <td>{{$user->survey->score or " "}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
        </div>
    </div>
</div>
<style type="text/css">
    .popover {
        max-width: 1024px;  
    }
</style>
<script>
$(document).ready(function(){
    $('#feedback').dataTable({
        bPaginate : false,
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
        },
    });
});
</script>