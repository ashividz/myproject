$(document).ready(function() 
{
 $('form.ajax').validate({
            errorLabelContainer: "#validation-error-container",
            messages: {
                chosenAnswer: "Please select any Option",
                'chosenAnswers[]': "Выберите все подходящие варианты"
            }
        });
$('.challenge').on('click','input[type=radio]',function() {
        $(this).closest('form.question').find('.radio-inline, .radio').removeClass('checked');
        $(this).closest('.radio-inline, .radio').addClass('checked');
        });

$('.challenge').on('click','input[type=checkbox]',function() {
        $(this).closest('.checkbox-inline, .checkbox').toggleClass('checked');
    });


$('form').on('submit', function(e) {
        var formMethod = $(this).find('input[name="_method"]').val();
        if (formMethod == 'DELETE') {
            var confirmAction = confirm('Are you sure you want to delete it?');
            if (confirmAction == false) {
                e.preventDefault();
            }
        }
    });

$('.next-question-button').on('click', function(e){
e.preventDefault();
var url = "/getQuestion";
var form = $('#quiz_form');
            var url = url;
            var type = "POST";

            $.ajax({
                url: url,
                type: type,
                dataType:'json',
                data: form.serialize(),
                success: function(response) {
                    var qn = response.questionIndex + 1;
                $('.questionNumber_span').html(qn);
                $('.question_title').html(response.questionDescription);
                $('#questionId').val(response.questionId);
                $('#questionIndex').val(response.questionIndex);
                $('.options').html(response.answer_text);
                var width = (qn *100) / response.total_questions;

                $('.progress-bar').css('width', width+'%');
                if(response.total_questions == response.qn)
                $('.next-question-button').html('Finish');
                $('.next-question-button').hide();
                $('.next-question-button').css('display','none');
                $('input[type=submit]').show();
                     }
            });
});

$('form.ajax').on('submit', function(e){

      $('form.ajax').validate();

            var form = $(this);
            var url = form.attr('action');
            var type = form.find('input[name="_method"]').val() || "POST";
            $('#quiz_time').val($('.timer_div').html());
            $.ajax({
                url: url,
                type: type,
                dataType:'json',
                data: form.serialize(),
                success: function(response) {

                    var proposedSolutionResultIcon;
                    if(response.proposedSolutionResult) {
                        proposedSolutionResultIcon = "<span class=\"glyphicon glyphicon-ok\" style=\"color:green;\"></span>";
                    } else {
                        proposedSolutionResultIcon = "<span class=\"glyphicon glyphicon-remove\" style=\"color:red;\"></span>";
                    }
                    $('.panel-heading').find('span.glyphicon').remove();
                    $('.panel-heading').prepend(proposedSolutionResultIcon + ' ');
                    var proposedSolutionWithDetailedResult = response.proposedSolutionWithDetailedResult;
                    var proposedSolutionDetaledIcon;
                    var resultClass;
                    for (var chosenAnswerId in proposedSolutionWithDetailedResult) {
                        if (!proposedSolutionWithDetailedResult.hasOwnProperty(chosenAnswerId)) {
                            continue;
                        }
                        var isCorrect = proposedSolutionWithDetailedResult[chosenAnswerId];
                        console.log('chosenAnswerId: ' + chosenAnswerId + ' isCorrect: ' + isCorrect);
//alert('chosenAnswerId: ' + chosenAnswerId + ' isCorrect: ' + isCorrect);
                        if(isCorrect==1) {
                            proposedSolutionDetaledIcon = "<span class=\"glyphicon glyphicon-ok\" style=\"color:green;\"></span>";
                            resultClass = 'correct';
                        } else {
                            proposedSolutionDetaledIcon = "<span class=\"glyphicon glyphicon-remove\" style=\"color:red;\"></span>";
                            resultClass = 'incorrect';
                        }

                        $("label input[value=" + chosenAnswerId + "]").addClass(resultClass).parent().prepend(proposedSolutionDetaledIcon);

                    }

                    var correctSolution = response.correctSolution;
                    var correctSolutionLength = correctSolution.length;
                    for (var i = 0; i < correctSolutionLength; i++) {
                        $("input[value=" + correctSolution[i] + "]:not(.correct)").parent().prepend('<span class="glyphicon glyphicon-ok" style="color:green;"></span>');
                    }

                    form.removeClass('challenge');
                    form.find('input[type=radio]').remove();
                    form.find('input[type=checkbox]').remove();
                    form.find('input[type=submit]').hide();
                    form.find('.next-question-button').show();
                }
            });
       

    e.preventDefault();
});
});

function auto_submit()
{
     $('#quiz_time').val($('.timer_div').html());
     var form = $('#quiz_form');
            var url = form.attr('action');
            var type = form.find('input[name="_method"]').val() || "POST";

            $.ajax({
                url: url,
                type: type,
                dataType:'json',
                data: form.serialize(),
                success: function(response) {

                    var proposedSolutionResultIcon;
                    if(response.proposedSolutionResult) {
                        proposedSolutionResultIcon = "<span class=\"glyphicon glyphicon-ok\" style=\"color:green;\"></span>";
                    } else {
                        proposedSolutionResultIcon = "<span class=\"glyphicon glyphicon-remove\" style=\"color:red;\"></span>";
                    }
                    $('.panel-heading').find('span.glyphicon').remove();
                    $('.panel-heading').prepend(proposedSolutionResultIcon + ' ');
                    var proposedSolutionWithDetailedResult = response.proposedSolutionWithDetailedResult;
                    var proposedSolutionDetaledIcon;
                    var resultClass;
                    for (var chosenAnswerId in proposedSolutionWithDetailedResult) {
                        if (!proposedSolutionWithDetailedResult.hasOwnProperty(chosenAnswerId)) {
                            continue;
                        }
                        var isCorrect = proposedSolutionWithDetailedResult[chosenAnswerId];
                        console.log('chosenAnswerId: ' + chosenAnswerId + ' isCorrect: ' + isCorrect);
//alert('chosenAnswerId: ' + chosenAnswerId + ' isCorrect: ' + isCorrect);
                        if(isCorrect==1) {
                            proposedSolutionDetaledIcon = "<span class=\"glyphicon glyphicon-ok\" style=\"color:green;\"></span>";
                            resultClass = 'correct';
                        } else {
                            proposedSolutionDetaledIcon = "<span class=\"glyphicon glyphicon-remove\" style=\"color:red;\"></span>";
                            resultClass = 'incorrect';
                        }

                        $("label input[value=" + chosenAnswerId + "]").addClass(resultClass).parent().prepend(proposedSolutionDetaledIcon);

                    }

                    var correctSolution = response.correctSolution;
                    var correctSolutionLength = correctSolution.length;
                    for (var i = 0; i < correctSolutionLength; i++) {
                        $("input[value=" + correctSolution[i] + "]:not(.correct)").parent().prepend('<span class="glyphicon glyphicon-ok" style="color:green;"></span>');
                    }

                    form.removeClass('challenge');
                    form.find('input[type=radio]').remove();
                    form.find('input[type=checkbox]').remove();
                    form.find('input[type=submit]').hide();
                    form.find('.next-question-button').show();
                }
            });
       

   
}