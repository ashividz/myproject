<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class QuizQuestion extends Model {
    protected $table = 'quiz_questions';
    protected $fillable = ['description', 'quiz_question_type', 'quiz_id'];

    public function answers() {
        return $this->hasMany(QuizAnswer::class, 'quiz_question_id');
    }

    public function topic() {
        return $this->belongsTo('App\Topic');
    }
    

    public function quiz() {
        return $this->belongsTo(QuizSetting::class, 'quiz_id', 'id');
    }

    public static function getByTopicAndQuestionNumber(Topic $topic, $questionNumber)
    {
        return QuizQuestion::where('topic_id', '=', $topic->id)->orderBy('created_at', 'DESC')->get()[$questionNumber - 1];
    }

    public function rightAnswer() {
        $answers = $this->answers;
        $right_answer = $answers->first(function ($key, $answer) {
                            return $answer->is_correct == 1;
                            });
        return $right_answer;
    }
    public function nextQuestionLink($questionNumber) {

        $nextQuestionLink = [];
        $total_questions = QuizQuestion::count();
        if ($total_questions != $questionNumber) {
            $nextQuestionLink['url']   = '/quiz/'. ++$questionNumber;
            $nextQuestionLink['text']  = 'Next';
            $nextQuestionLink['class'] = 'btn-default';
        } else {
            $nextQuestionLink['url']   = '/';
            $nextQuestionLink['text']  = 'Finish';
            $nextQuestionLink['class'] = 'btn-primary';
        }

        return $nextQuestionLink;
    }

    /**
     * Returns an array of all possible question types.
     *
     * @return array
     */
    public static function possibleTypes()
    {
        $type = \DB::select(\DB::raw('SHOW COLUMNS FROM questions WHERE Field = "question_type"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $values = [];
        foreach(explode(',', $matches[1]) as $value){
            $values[] = trim($value, "'");
        }
        return $values;
    }

    public function renderDescription() {

        $type = $this->question_type;
        $description = $this->description;

        switch($type) {
            case "one_variant":
                return $description;
                break;
            case "two_variants":
                return $description . ' <b><i>(Выберите два варианта ответа)</i></b>';
                break;
            case "all_that_apply":
                return $description . ' <b><i>(Выберите все подходящие варианты)</i></b>';
                break;
        }
    }

}
