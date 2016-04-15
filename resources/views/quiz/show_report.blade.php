<link href="{{ asset('css/quiz_main.css') }}" rel="stylesheet">

            <div class="container">
              


            <div class="panel panel-default">
                <div class="panel-heading timer_bar">
               <h4> Quiz Report</h4>
                   </div>
               
               
                <div class="panel-body">
                   
                 <table id="leads" class="table table-striped">
                        <thead>
                                
                                <th>User</th>
                                <th>Total Questions</th>
                                <th>Attempted</th>
                                
                                <th>Correct </th>
                                <th>Percent</th>
                                
                        </thead>
                        <tbody>
                            @foreach($users_appeared AS $user)
                                 <tr>
                                        <td>
                                        <a href='/quiz/user/{{$user->id}}/report'>{{$user->employee->name}}</a>
                                        </td>
                                         <td>
                                         15
                                        </td>
                                         <td>
                                        {{$user->total_attempted}}
                                        </td>
                                       <!-- <td>
                                        <?php
                                         /*$ppp = App\Models\Reply::select('quiz_question_id', DB::RAW('COUNT(*) AS countq'))
                ->where('user_id',$user->id)
                ->havingRaw('count(*) > 1')
                ->groupBy('quiz_question_id')
                ->first();
                if($ppp)
                echo $ppp->quiz_question_id;*/
                                        ?>
                                        </td> -->
                                         <td>
                                        {{$user->is_correct}}
                                        </td>
                                         <td>
                                         <?php
                                         if(round(($user->is_correct*100)/15,2) >= 80)
                                            $class = 'pass';
                                        else
                                            $class = 'fail';

                                      
                                         ?>
                                        <div class='report_percent {{$class}}' style='width: {{round(($user->is_correct*100)/15,2)}}%'>{{round(($user->is_correct*100)/15,2)}} %</div>
                                        </td>
                                  </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>



            </div>          

<script type="text/javascript">
$(document).ready(function() 
{
    $('#leads').dataTable({      
        "bInfo" : true,
        "iDisplayLength" : 100
    });
});
</script>