<link href="{{ asset('css/quiz_main.css') }}" rel="stylesheet">

            <div class="container">
              


            <div class="panel panel-default">
                <div class="panel-heading timer_bar">
               <h4> {{$user_name}} </h4>
                   </div>
               
               
                <div class="panel-body">
                   
                 <table id="leads" class="table table-striped">
                        <thead>
                                
                                <th>Question</th>
                                <th>Answer</th>
                                <th>User Answer</th>
                                <th>Correct</th>
                                <th>Time </th>
                                
                                
                        </thead>
                        <tbody>
                            <?php
                            $correct = 0;
                            ?>
                            @foreach($replies AS $reply)
                            <?php 
                            $correct += $reply->is_correct;
                            ?>
  
                                 <tr>
                                        <td>
                                         {{$reply->question->description}}
                                        </td>
                                         <td>
                                         {{$reply->question->rightAnswer()->description}}
                                        </td>
                                         <td>
                                         {{$reply->answer->description}}
                                        </td>
                                         <td>
                                         {{$reply->is_correct}}
                                        </td>
                                        <td>
                                         {{$reply->duration}}
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
                                 
                                  </tr>

                            @endforeach
                        </tbody>
                    </table>

                    <div> <h3 style='color: #555'>Attempted {{$replies->count()}} | Correct {{$correct}} | {{round(($correct*100)/$replies->count(),2)}} %</h3></div>
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