<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizReply;
use App\Models\User;
use App\Models\QuizSetting;
use App\Models\QuizReattempt;
use App\Models\QuizUser;
use Session;
use DB;
use Auth;
use Excel;

class QuizController extends Controller {

    public function index()
    {
        $data = array(
            'menu'      => 'quiz',
            'section'   => "index"
        );
        return view('home')->with($data);


    }

    public function train($baseTopicName) {

        $baseTopic = BaseTopic::where('name', '=', $baseTopicName)->first();

        $topics = $baseTopic->topics()->orderBy('name')->get();

        return view('quiz.train', compact('baseTopic', 'topics'));
    }

public function setNewid(Request $request) {
$questions = QuizQuestion::orderby('id')->get();
$newId = 16;
$newAId = 61;
$newRId = 883;
foreach($questions as $question)
{

$answers = $question->answers;
foreach($answers as $answer)
{
$answer->quiz_question_id = $newId;
$answer->id = $newAId;
$answer->save();
$newAId++;
}

$replies = QuizReply::where('quiz_question_id', $question->id)->get();
foreach($replies as $reply)
{
$reply->quiz_question_id = $newId;
$reply->id = $newRId;
$reply->save();
$newRId++;
}

$question->id = $newId;
$question->save();
$newId++;
}
}

    public function getQuestion(Request $request) {

        // may needs to be refactored, may check sql queries
        $questionIndex = $request->questionIndex;
        $questionIndex++;
        $questions = Session::get('questions');
        $question = $questions[$questionIndex];

        $questionId = $request->questionId;
        //$question = Question::where('id','>',$questionId)->orderBy('id')->limit(1)->first();
        $answers = $question->answers()->get();

        $nextQuestionLink = $question->nextQuestionLink($questionIndex);
         $total_questions = $questions->count();//Question::count();
         $answer_text = "";
         foreach($answers as $answer)
         {
            $answer_text .= "<div class='radio'><label><input type='radio' value='$answer->id' name='chosenAnswer' class='required' id='$answer->id' >$answer->description</label></div>";
         }

         return response()->json([
            
            'questionDescription' => $question->renderDescription(),
            'questionId' => $question->id,
            'questionIndex' => $questionIndex,
            'questionType' => $question->question_type,
            'total_questions'=> $total_questions,
            'answer_text'=> $answer_text,
            'next'           => $nextQuestionLink
        ]);

       
    }

    public function show() {

        // may needs to be refactored, may check sql queries
        $reply = QuizReply::where('user_id', \Auth::user()->id)->first();
        if($reply)
            $alreadyAppear = true;
        else
            $alreadyAppear = false;

        $setting = QuizSetting::where('active', 1)->get()->first();

        $question = null;
        $total_questions  = 1;
        $quiz_duration = null;
        $answers = null;
        $alreadyAppear = null;
        $nextQuestionLink = null;
        $first_question_id = null;
        $noGroup = null;
        
        if($setting)
        {
        if(date('Y-m-d H:i:s') >= $setting->start_time && (date('Y-m-d H:i:s') <= $setting->end_time))
            $noQuiz = false;
        else
            $noQuiz = true;
        }
        else
            $noQuiz = true;

        if($setting)
        {
        $quizid = $setting->id;

        $quizUser = QuizUser::where('user_id', \Auth::user()->id)
                            ->where('quiz_id', $quizid)
                            ->orderBy('id', 'desc')
                            ->get()->first();
                            //dd($quizUser);
        if(!$quizUser)
            $noGroup = true;
        else
        {
            $noGroup = false;
        
        $reply = QuizReply::with('question.quiz')
                        ->where('user_id', \Auth::user()->id)
                         ->whereHas('question.quiz', function($q) use($quizid) {
                                // Query the department_id field in status table
                                 $q->where('quiz_setting.id', '=', $quizid); // '=' is optional
                                })
                        ->get();

        $reattempt = null;  
        $alreadyAppear = false;
        if($reply->count() > 0)
        {

            $reattempt = QuizReattempt::where('user_id', \Auth::user()->id)->where('quiz_id', '=', $quizid)->get()->first();

        }
        
        if($reply->count() > 0)
        {
            if(isset($reattempt))
                $alreadyAppear = false;

            else
                $alreadyAppear = true;
        }

        //dd($alreadyAppear); 
        //$gp = explode(",",$settings->question_group);
        
        
        //dd($gp);         
        if(isset($reattempt))
        {
            $question_arr = explode(", ", $reattempt->questions);
            $quiz_duration = $reattempt->duration;
            $questions = QuizQuestion::orderByRaw("RAND()")->whereIn('id', $question_arr)->get();
            $reattempt->delete();
        }
        else
        {
            $questions = QuizQuestion::orderByRaw("RAND()")
                                    ->where('quiz_id', $setting->id)
                                    ->where(function($r) use($quizUser){
                                              $r->where('q_group', $quizUser->q_group)
                                              ->orWhere('q_group', 'common');
                                            })
                                    ->get();
            $quiz_duration = $setting->quiz_duration;
        }

        
        Session::put('questions', $questions);
        $question = $questions->first();
        $first_question_id = $question->id;
        $answers = $question->answers()->get();

        $nextQuestionLink = $question->nextQuestionLink(0);
         $total_questions = $questions->count();
        }

     }
        $data = array(
            'menu'      => 'quiz',
            'section'   => "show",
            'questionNumber' => $first_question_id,
            'questionIndex' => '0',
            'question'       => $question,
            'total_questions' => $total_questions,
            'quiz_duration'   => $quiz_duration,
            'answers'        => $answers,
            'alreadyAppear' => $alreadyAppear,
            'next'           => $nextQuestionLink,
            'noQuiz'        => $noQuiz,
            'noGroup'       => $noGroup,
        );
        return view('home')->with($data);
    }

    public function showReport($id) {
      /*  $users_appeared = User::with('employee')
        ->join(DB::raw('(select user_id, sum(is_correct) as is_correct, count(user_id) as total_attempted from quiz_replies group by user_id) AS qr'), function($join) {
                $join->on('users.id', '=', 'qr.user_id');
            })->orderBy('is_correct')->get();*/


        $replies = QuizReply::with('user.employee')
        ->whereHas('question.quiz', function($q) use($id) {
            // Query the department_id field in status table
             $q->where('quiz_setting.id', '=', $id); // '=' is optional
            })
        ->select('user_id', DB::raw('sum(is_correct) as is_correct, count(user_id) as total_attempted'))
        ->groupBy('user_id')
        ->get();

        foreach($replies as $reply)
        {
            $quizUser = QuizUser::where('user_id', $reply->user->id)->where('quiz_id', $id)->orderBy('id', 'desc')->get()->first();
            $totalQuestions = QuizQuestion::where('quiz_id', $id)
                                            ->where(function($r) use($quizUser){
                                                  $r->where('q_group', $quizUser->q_group)
                                                  ->orWhere('q_group', 'common');
                                                })
                                            ->count();
            $reply->totalQuestions = $totalQuestions;
            $reply->group = $quizUser->q_group;
        }

        //dd($users_appeared);
        $data = array(
            'menu'      => 'quiz',
            'section'   => "show_report",
            'replies' => $replies,
            'quiz_id' => $id
        );
        return view('home')->with($data);
                //dd( $users);
    }

    public function downloadReport($id)
    {

         $replies = QuizReply::with('user.employee')
        ->whereHas('question.quiz', function($q) use($id) {
            // Query the department_id field in status table
             $q->where('quiz_setting.id', '=', $id); // '=' is optional
            })
        ->select('user_id', DB::raw('sum(is_correct) as is_correct, count(user_id) as total_attempted'))
        ->groupBy('user_id')
        ->get();

        foreach($replies as $reply)
        {
            $quizUser = QuizUser::where('user_id', $reply->user->id)->where('quiz_id', $id)->orderBy('id', 'desc')->get()->first();
            $totalQuestions = QuizQuestion::where('quiz_id', $id)->where('q_group', $quizUser->q_group)->count();
            $reply->totalQuestions = $totalQuestions;
            $reply->group = $quizUser->q_group;
        }

        if (!$replies->isEmpty()) {
            Excel::create('QuizReport', function($excel) use($replies) {

                $excel->sheet('Report', function($sheet) use($replies) {
                    //$sheet->fromArray($carts);
                        

                    $sheet->appendRow(array(
                           'User',
                           'Group',
                           'Total Questions',
                           'Attempted',
                           'Correct',
                           'Percent',
                         ));
                    foreach ($replies as $reply) {


                        $user     = $reply->user->employee->name;
                        $group    = $reply->group;
                        $totalQuestions   = $reply->totalQuestions;
                        $total_attempted  = $reply->total_attempted;
                        $is_correct = $reply->is_correct;
                        $percent = round(($reply->is_correct*100)/$reply->totalQuestions,2);
                        
                        $sheet->appendRow(array(
                            $user,
                            $group,
                            $totalQuestions,
                            $total_attempted,
                            $is_correct,
                            $percent
                        ));
                    }
                });
            })->download('xls');;
        }
    }

    public function editQuiz(Request $request) {
        $replies = null;
        $setting = QuizSetting::where('id', $request->quiz_id)->with('questions')->get()->first();
        $users = User::getUsersByRole('cre');

        foreach ($users as $user) {
            $quizUser = QuizUser::where('user_id', $user->id)
                                ->where('quiz_id', $request->quiz_id)
                                ->orderBy('id', 'desc')
                                ->get()->first();
            
            
            if($quizUser)
                $user->group =  $quizUser->q_group;
        }


        $questions = $setting->questions;
        foreach ($questions as $question) {
            $uniques[$question->q_group] = $question; // Get unique country by code.
        }

       //dd($questions->unique('q_group'));

        $qid = $request->quiz_id;
        $reattempt = $request->reattempt;
        $replies = QuizReply::with('user.employee')
        ->whereHas('question.quiz', function($q) use($qid) {
            // Query the department_id field in status table
             $q->where('quiz_setting.id', '=', $qid); // '=' is optional
            })
        ->groupBy('user_id')
        ->get();

        $data = array(
            'menu'      => 'quiz',
            'section'   => "quiz_edit",
            'setting' => $setting,
            'replies' => $replies,
            'reattempt' => $reattempt,
            'users'    => $users
        );
        return view('home')->with($data);
                //dd( $users);
    }

   public function addToGroup(Request $request)
    {
        foreach($request->users as $user)
        {
            $quizUser = new QuizUser();
            $quizUser->quiz_id = $request->quiz_id;
            $quizUser->user_id = $user;
            $quizUser->q_group =  $request->group;
            $quizUser->save();

        }
         Session::flash('status', "User Added to Group!");
         return redirect("/quiz/edit/$request->quiz_id");

    }

    public function reattempt(Request $request)
    {
           
        if(isset($request->user_id) && !empty($request->user_id))
        {
        $quiz_id = $request->quiz_id;


        $quizUser = QuizUser::where('user_id', $request->user_id)
                            ->where('quiz_id', $quiz_id)
                            ->orderBy('id', 'desc')
                            ->get()->first();

        $replies = QuizReply::with('question')
        ->where('user_id',$request->user_id)
        ->whereHas('question.quiz', function($q) use($quiz_id) {
                                // Query the department_id field in status table
                                 $q->where('quiz_setting.id', '=', $quiz_id); // '=' is optional
                                })
        ->orderBy('is_correct','desc')
        ->get();

        foreach($replies as $reply)
        {
            $appeared[] = $reply->quiz_question_id;
        }

        $quiz_questions = array();
        $quiz_questions = QuizQuestion::where('quiz_id', $quiz_id)
                                        ->where(function($r) use($quizUser){
                                              $r->where('q_group', $quizUser->q_group)
                                              ->orWhere('q_group', 'common');
                                            })
                                        ->select('id')
                                        ->get();
        foreach($quiz_questions as $question)
            $replied[] = $question->id;

         //dd($replied);
        $set_reattempt = QuizReattempt::where('quiz_id', $quiz_id)
                                        ->where('user_id', $request->user_id)
                                        ->get()
                                        ->first();
        if($set_reattempt)
        {
            Session::flash('status', "Re-attempt Set!");
            $request->reattempt = $set_reattempt;
            //echo "Reattempt Set";
            return $this->editQuiz($request);
        }

        $diffarr = array_diff($replied, $appeared);
        if (!empty($diffarr)) {
            $quiz_setting = QuizSetting::find($quiz_id);
            //dd(intval($quiz_setting->quiz_duration));
            $duration = ceil(round(intval($quiz_setting->quiz_duration)/$quiz_questions->count(),2)*count($diffarr));
            $quiz_reattempt = new QuizReattempt();
            $quiz_reattempt->questions = implode(", ",$diffarr);
            $quiz_reattempt->user_id = $request->user_id;
            $quiz_reattempt->quiz_id = $request->quiz_id;
            $quiz_reattempt->duration = $duration."m";
            //dd($quiz_reattempt);
            $quiz_reattempt->save();
            $request->reattempt = $quiz_reattempt;
            Session::flash('status', "Re-attempt Set!");
            //echo "Reattempt Set";
            }
            else
            {
                Session::flash('status', "All Questions Attempted!");
                //echo "All Questions Attempted!";
            }
        }
            //return redirect("/quiz/edit/$quiz_id");
            return $this->editQuiz($request);
       

    }
     public function saveQuiz(Request $request) {

        $setting = QuizSetting::where('id', $request->id)->get()->first();
        $setting->title = $request->title;
        $setting->start_time = $request->start_time;
        $setting->end_time = $request->end_time;
        $setting->quiz_duration = $request->quiz_duration;
        
        if($request->active ==1)
            $affected = DB::table('quiz_setting')
                            //->where('active', '=', 1)
                            ->update(array('active' => 0));

        $setting->active = $request->active;
        $setting->update();
        Session::flash('status', "Settings Updated Successfully!");
        $request->quiz_id = $request->id;
        return $this->editQuiz($request);
       //dd( $users);
    }

    /* public function setAnswer($id) {
        $replies = Reply::with('question','answer')->where('user_id',$id)->orderBy('is_correct','desc')->get();
       
       // dd($reply->question->rightAnswer()->description);
        foreach($replies as $reply)
        {
        if($reply->is_correct==1)
            $reply->user_answer = $reply->question->rightAnswer()->id;
        else
        {
            $answers = $reply->question->answers;
            $wrong_answer = $answers->first(function ($key, $answer) {
                            return $answer->is_correct == 0;
                            });
            $reply->user_answer = $wrong_answer->id;
        }
            $reply->save();
        }
      
        return ;
                //dd( $users);
    }*/

    public function showUserReport($id, $id2) {

        $replies = QuizReply::with('question','answer')
                    ->where('user_id', $id)
                    ->whereHas('question', function($q) use($id2) {
                                // Query the department_id field in status table
                                 $q->where('quiz_id', '=', $id2); // '=' is optional
                                })
                    ->orderBy('is_correct','desc')
                    ->get();
                    
        // dd($replies);
        //$reply = $replies->first();
        // dd($reply->question->rightAnswer()->description);

        $user_name = User::find($id)->employee->name;
        $data = array(
            'menu'      => 'quiz',
            'section'   => "user_report",
            'user_name' => $user_name,
            'replies' => $replies
        );
        return view('home')->with($data);
                //dd( $users);
    }

    public function proposeSolution(Request $request) {
        
        $questionId = $request->questionId;
        $question = QuizQuestion::find($questionId);
        $answers = $question->answers()->get()->toArray();
        $quiz_time = $request->quiz_time;
        
        // Prepare array of proposed answers
        $proposedSolution = [];
        $user_answer = $request->chosenAnswer;
        $autosubmit = 0;
        if(isset($request->chosenAnswer))
        {
            
        if ($question->question_type == 'one_variant')
        {
            $proposedSolution[] = (int)$request->chosenAnswer;
        }
        else
        {
            $proposedSolution = $request->chosenAnswers;
        }

        // Prepare array of correct answers
        $correctSolution = [];
        foreach($answers as $answer) {
            if ($answer['is_correct']) {
                $correctSolution[] = $answer['id'];
            }
        }

        $proposedSolutionResult = ($proposedSolution == $correctSolution);
        
    }
    else
        $autosubmit = 1;
    
        $proposedSolutionWithDetailedResult = [];
        foreach ($proposedSolution as $answerId) {
            foreach ($answers as $answer) {
                if ($answer['id'] == $answerId) {
                    $is_correct = $answer['is_correct'];
                }
            }
            $proposedSolutionWithDetailedResult[$answerId] = $is_correct;
        }
        $reply = QuizReply::where('quiz_question_id', $questionId)
                            ->where('user_id', \Auth::user()->id)
                            ->first();
        if($reply)
            $alreadyAnswered = true;
        else
            $alreadyAnswered = false;
        if($autosubmit == 0)
        if (\Auth::user() && !$reply) {
     
            \Auth::user()->replies()->create(['quiz_question_id' => $questionId, 
                                                'is_correct' => $proposedSolutionResult, 
                                                'duration' => $quiz_time, 
                                                'user_answer' => $user_answer
                                            ]);
        }

        return response()->json([
            'correctSolution' => $correctSolution,
            'proposedSolutionWithDetailedResult' => $proposedSolutionWithDetailedResult,
            'proposedSolutionResult'=> $proposedSolutionResult,
            'alreadyAnswered'=> $alreadyAnswered
        ]);
    }

    public function admin($messageData=NULL)
    {

        $settings = QuizSetting::with('questions')
                                ->orderBy('created_at', 'desc')
                                ->limit(20)
                                ->get();


        $data = array(
            'menu'              =>  'quiz',
            'section'           =>  'upload',
            'settings'          =>  $settings
        );
        if(isset($messageData) && !is_null($messageData))
          $data = array_merge($data, $messageData);
        return view('home')->with($data);
    }

      public function readfile(Request $request)
    {
        $file = $request->file('file');
        
        $path = $file->getPathName();
        $original_name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $fileName = "quiz_questions-".date("Y-m-d-H-i-s").".".$extension;
        $file->move('uploads', $fileName);
        $date = date("Y-m-d H:i:s");
        //$results  = Excel::selectSheetsByIndex(1)->load("uploads/$fileName")->get();
        $results  = Excel::load("uploads/$fileName")->get();
        $questions = $results->get(0);
        $answers = $results->get(1);
        $skip = 0;
        $i = 1;

        $quiz_setting  = new QuizSetting();
        $quiz_setting->quiz_duration = '20m';
        $quiz_setting->start_time = $date;
        $quiz_setting->end_time = $date;
        $quiz_setting->save();


        foreach($questions as $question)
        {

            if(is_null($question->id))
                break;

            $quiz_question = new QuizQuestion();
            $quiz_question->description = $question->title;
            $quiz_question->q_group = $question->group;
            $quiz_question->quiz_id = $quiz_setting->id;
            $quiz_question->save();
            $question_id = $quiz_question->id;

              
            $options = $answers->slice($skip, 4);
            $skip = $i*4;
            
            foreach($options as $option)
            {

                if(is_null($option->id))
                    break;

                $quiz_answer = new QuizAnswer();
                $quiz_answer->description = $option->title;
                $quiz_answer->is_correct = (is_null($option->correct))?'0':$option->correct;
                $quiz_answer->quiz_question_id = $question_id;
             
                $quiz_answer->save();
            }
            $i++;
          
        }
        $users = User::getUsersByRole('cre');
        //$setting = QuizSetting::with('questions')->orderBy('id', 'desc')->get()->first();
        foreach($users as $user)
        {
            $quizUser = new QuizUser();
            $quizUser->quiz_id = $quiz_setting->id;
            $quizUser->user_id = $user->id;
            $quizUser->q_group = 'GROUP 1';
            $quizUser->save();

        }

         $data = array(
            'status'              =>  'success',
            'message'           =>  'Questions Uploaded Successfully!',
            );
        Session::flash('status', '');
        return $this->admin()->with($data);
    }

}
