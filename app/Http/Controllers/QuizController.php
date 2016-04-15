<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Reply;
use App\Models\User;
use Session;
use DB;
use Auth;

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
        $reply = Reply::where('user_id', \Auth::user()->id)->first();
        if($reply)
            $alreadyAppear = true;
        else
            $alreadyAppear = false;

        $settings = DB::table('quiz_setting')
                     ->select(DB::raw('*'))
                    ->first();
        $gp = explode(",",$settings->question_group);
        $quiz_duration = $settings->quiz_duration;
        //dd($gp);         
        $questions = Question::orderByRaw("RAND()")->whereIn('id',$gp)->get();
        Session::put('questions', $questions);
        $question = $questions->first();
        $answers = $question->answers()->get();

        $nextQuestionLink = $question->nextQuestionLink(0);
         $total_questions = $questions->count();
        $data = array(
            'menu'      => 'quiz',
            'section'   => "show",
            'questionNumber' => $question->id,
            'questionIndex' => '0',
            'question'       => $question,
            'total_questions' => $total_questions,
            'quiz_duration'   => $quiz_duration,
            'answers'        => $answers,
            'alreadyAppear' => $alreadyAppear,
            'next'           => $nextQuestionLink
        );
        return view('home')->with($data);
    }

    public function showReport() {
        $users_appeared = User::with('employee')
        ->join(DB::raw('(select user_id, sum(is_correct) as is_correct, count(user_id) as total_attempted from quiz_replies group by user_id) AS qr'), function($join) {
                $join->on('users.id', '=', 'qr.user_id');
            })->orderBy('is_correct')->get();
        $data = array(
            'menu'      => 'quiz',
            'section'   => "show_report",
            'users_appeared' => $users_appeared
        );
        return view('home')->with($data);
                //dd( $users);
    }

     public function setAnswer($id) {
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
    }

    public function showUserReport($id) {
        $replies = Reply::with('question','answer')->where('user_id',$id)->orderBy('is_correct','desc')->get();
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
        $question = Question::find($questionId);
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
        $reply = Reply::where('quiz_question_id', $questionId)->where('user_id', \Auth::user()->id)->first();
        if($reply)
            $alreadyAnswered = true;
        else
            $alreadyAnswered = false;
        if($autosubmit == 0)
        if (\Auth::user() && !$reply) {
     
            \Auth::user()->replies()->create(['quiz_question_id' => $questionId, 'is_correct' => $proposedSolutionResult, 'duration' => $quiz_time, 'user_answer' => $user_answer]);
        }

        return response()->json([
            'correctSolution' => $correctSolution,
            'proposedSolutionWithDetailedResult' => $proposedSolutionWithDetailedResult,
            'proposedSolutionResult'=> $proposedSolutionResult,
            'alreadyAnswered'=> $alreadyAnswered
        ]);
    }
}
