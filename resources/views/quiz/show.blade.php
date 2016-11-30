<link href="{{ asset('css/quiz_main.css') }}" rel="stylesheet">

            <div class="container">
              

                 <div class="progress">
                <div class="progress-bar progress-bar-striped" style="width: {{ ( $questionNumber * 100 ) / $total_questions }}%">

                </div>
            </div>

            <div class="panel panel-default" id='panelbody'>
                <div class="panel-heading timer_bar">
                    Question #<span class='questionNumber_span'>{{ ($questionIndex+1) }}</span> <div class="pull-right"><span class='questionNumber_span'>{{ ($questionIndex+1) }}</span> of {{ $total_questions }}</div>
                <div class='timer_div form-control'></div>
                </div>
                <div class="panel-body">
@if($alreadyAppear || $noQuiz || $noGroup)
    @if($alreadyAppear)
        <h1 style='text-align: center'>Already Appearded in Test!</h1>
    @else
        @if($noGroup)
            <h1 style='text-align: center'>Question Group Not Set!</h1>
        @else
            <h1 style='text-align: center'>No Quiz active Right Now!</h1>
        @endif
    @endif
@else
                    <p class="lead question_title">{!! $question->renderDescription() !!}</p>

<form id='quiz_form' class="question ajax challenge {{$question->question_type}}" accept-charset="UTF-8" action="" method="POST" >
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" id='questionId' value="{{ $question->id }}" name="questionId">
<input type="hidden" id='questionIndex' value="{{ $questionIndex }}" name="questionIndex">
<input type="hidden" id='quiz_time' value="" name="quiz_time">

<div class='options'>
      @if($question->question_type == 'one_variant')
                        @foreach($answers as $answer)
                            <div class="radio">
                            

                                <label>
                                    <input type="radio" value="{{$answer->id}}" name="chosenAnswer" class="required" id="{{$answer->id}}" >
                                    {{ $answer->description }}
                                    </label>
                              </div>
                        @endforeach
                    @else
                        @foreach($answers as $key => $answer)
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('chosenAnswers[]', $answer->id, null, ['id' => $answer->id, 'class' => 'required']) !!}
                                    {{ $answer->description }}
                                </label>
                            </div>
                        @endforeach
                    @endif 
                    </div>                                     
<input type="submit" value="Submit" id='submit' class="btn btn-default">
<a class="next-question-button btn {{ $next['class'] }}" href="{{ $next['url'] }}" style="display: none;" role="button">{{ $next['text'] }}</a>
<p id="validation-error-container"></p>
</form>

                    @endif
                </div>

            </div>



            </div>          
<script src="/js/jquery.validate.js"></script>
<script src='/js/timer.jquery.js'></script>
    <script>
        (function(){
            var hasTimer = false;
                $('.timer_div').timer({
                    duration: '{{$quiz_duration}}',
                    format: '%H:%M:%S',
                    editable: true,
                    callback: function() {
        alert('Time Up! Result will publish soon.');
        auto_submit();
        $('input[type=submit]').prop("disabled",true);

        $('.timer_div').timer('pause');
    },
                });
            
        })();
    </script>
    
<script src="/js/quiz_main2.js"></script>
