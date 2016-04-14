<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Reply;
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
            'answers'        => $answers,
            'alreadyAppear' => $alreadyAppear,
            'next'           => $nextQuestionLink
        );
        return view('home')->with($data);
    }

    public function proposeSolution(Request $request) {
        

        $questionId = $request->questionId;
        $question = Question::find($questionId);
        $answers = $question->answers()->get()->toArray();
        $quiz_time = $request->quiz_time;
        
        // Prepare array of proposed answers
        $proposedSolution = [];

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
        $proposedSolutionResult = 0;
    //return $proposedSolutionResult;

        // pass to response detailed results on proposed solution
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
        if (\Auth::user() && !$reply) {
     
            \Auth::user()->replies()->create(['quiz_question_id' => $questionId, 'is_correct' => $proposedSolutionResult, 'duration' => $quiz_time]);
        }

        return response()->json([
            'correctSolution' => $correctSolution,
            'proposedSolutionWithDetailedResult' => $proposedSolutionWithDetailedResult,
            'proposedSolutionResult'=> $proposedSolutionResult,
            'alreadyAnswered'=> $alreadyAnswered
        ]);
    }
}
