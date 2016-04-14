<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Question extends Model {
    protected $table = 'quiz_questions';
	protected $fillable = ['description', 'quiz_question_type'];

    public function answers() {
        return $this->hasMany(Answer::class, 'quiz_question_id');
    }

    public function topic() {
        return $this->belongsTo('App\Topic');
    }

    public static function getByTopicAndQuestionNumber(Topic $topic, $questionNumber)
    {
        return Question::where('topic_id', '=', $topic->id)->orderBy('created_at', 'DESC')->get()[$questionNumber - 1];
    }

    public function nextQuestionLink($questionNumber) {

        $nextQuestionLink = [];
        $total_questions = Question::count();
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
