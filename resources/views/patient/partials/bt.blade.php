@extends('patient.index')

@section('top')
<style type="text/css">
    .tags li {
        list-style: none;
        display: inline-block;
    }
    .tags li .tag {
        text-transform: uppercase;
        font-size: 14px;
        margin-left: 20px; 
        background-color: rgba(111, 128, 168, 1);
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.15), inset 0 1px 2px rgba(0, 0, 0, 0.5);
        padding: 2px 20px;  
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
        <form id="form-upload" enctype="multipart/form-data" method="post" action="/patient/{{$patient->id or ''}}/bt">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            <label> Upload Report : </label> 
                            <input type='file' id='bt_report' name="bt_report" />
                        </td>
                        <td>
                            <label> Report Date : </label> 
                            <input type='text' name="report_date" value='{{date('Y-m-d')}}' />
                        </td>
                        </tr>
                    <tr>
                    <td><label> Remark : </label> <br>
                            <textarea name="remark" cols="40"></textarea>
                        </td>
                        <td colspan="2">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

@endsection

@section('main')



    
    
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">BT Reports</h3>
    </div>
    <div class="panel-body">
    <div style='display: inline-block !important;margin-left: 30%'>
        <form id="form-diet" action="/patient/{{$patient->id}}/bt/send" method="post" class="form-inline" >
            <table class="table table-bordered blocked" >
                <thead>
                    <tr>
                        <th>#</th>                  
                        <th>Report Date</th>
                        <th>Report File</th><!--
                        <td>Mid Morning</td>-->
                        <th>Remarks</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
            @foreach($bts as $bt)
                    <tr>
                        <td><input type="checkbox" name="checkbox[]" id="checkbox[]" value="{{$bt->id}}"></td>
                        <td>{{date('jS M, Y', strtotime($bt->report_date))}}</td>
                        <td>
                            <div class="breakfast"><a  class='popup_gallery' data=''  href='/bt/report/{{$bt->id}}'>Open Report</a></div>
                            
                        </td><!--
                        <td>
                            <div class="mid_morning">{{$diet->mid_morning or ""}}</div>
                            <i class="fa fa-copy pull-right blue" title="mid_morning"></i>
                        </td>-->
                        <td>
                            <div class="lunch">{{$bt->remark}}</div>
                            
                        </td>
                        
                        <td><a href='/patient/bt/edit/{{$bt->id}}' class="popup_gallery"><i class="fa fa-edit diet" id="{{$bt->id}}"></i></a></td>
                    </tr>
            @endforeach

                </tbody>
            </table>

            
        </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/form-ajax.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{
  $('#date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'D-M-YYYY' 
  }); 
 });
</script>

<style type="text/css">
    .progress td {
        padding: 8px 0 !important;
    }
    div .checked {
        background-color:#266c8e; 
        color:#fff!important; 
    }
</style>
<script type="text/javascript">
$(document).ready(function() 
{

    $('#bt_report').bind('change', function() {
            var fileExtension = ['pdf', 'jpeg', 'jpg', 'png'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("Only PDF Files Allowed");
                $('#form-upload .btn').prop('disabled', true);
                return false;
          }

        if(this.files[0].size > 1024 * 1024 * 2) {
                alert('Report size is: ' + (this.files[0].size/1024/1024).toFixed(2) + "MB, shouldn't be more that 2 MB.");
                $('#form-upload .btn').prop('disabled', true);
          }
        else
                $('#form-upload .btn').prop('disabled', false);
    });

    $('input[name="report_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD'
            },
        endDate: '2016-12-31'
    });

    $('input[name="report_date"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });
    
    $('.fa-copy').on('click', function(){
        var i = $(this).prev();
        var diet = this.title;      

        $('#'+this.title).val(i.text());
        $('.'+this.title).removeClass('checked');
        i.addClass('checked');
        //alert('Copied : ' +i.text());
    });

    //autocomplete
    $( "#form-diet .diet-area" ).autocomplete({
        source: function( request, response ) {
            //alert($(this.element).prop("id"));
            var id = $(this.element).prop("id");
            $.ajax({
                type: "POST",
                url: "/diet/autocomplete",
                data:{'term' : request.term, id : id, _token : '{{ csrf_token() }}'},
                dataType: "json",
                beforeSend: function(){
                    //$("#"+id+"-list").css("background","#f2f2f2");
                },
                success: function(data){
                    //console.log(data);
                    $("#"+id+"-list").empty();
                    $("#"+id+"-list").show();
                    response( $.map( data, function(field) {
                    // your operation on data
                
                        console.log(field.id);
                        $("#"+id+"-list").append("<li class='diet-data' title='"+id+"'>"+ field.diet +"</li>");
                    }));
                }
            });
        },
        minLength :4
    });

    $('body').on('click', '.diet-data', function(){
        $("#"+this.title).val($(this).text());
        $("#"+this.title+"-list").hide();
    });

    //Hide diet-list when not in focus
    $("body").click
    (
      function(e)
      {
        if(e.target.className !== "diet-list")
        {
          $(".diet-list").hide();
        }
      }
    );
});
</script>
<style type="text/css">
    textarea {
        font-size: 12px;
    }
    .diet-list {        
        position: absolute;
        line-height: 20px;
        background-color: #fff4c5;
        border: 1px solid #e4c94b;
    }
    li.diet-data {
        list-style: none;
        padding: 5px;
        cursor: pointer;
        z-index: 9999;
        min-width: 500px;
        font-size: 12px;
        margin: 5px; 
        background-color: #fff4c5;
    }
    li.diet-data:hover {
        background-color: #F9DD68;
    }
    .ui-helper-hidden-accessible {
        display: none !important;
    }
</style>

<link href="/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="/js/jquery-ui.min.js"></script>

<link href="/css/popup.css" media="all" type="text/css" rel="stylesheet">
<script src="/js/jquery.popup.js"></script>
<script>


    /*---------------------
      SETTINGS
    */
    var gallerySettings = {
        
        height: 500,
        markup    : '' +
          '<div class="popup">' +
            '<div class="popup_wrap">' +
              '<div class="popup_content"/>' +
            '</div>' +
            '<a href="#next" class="ignore-hash">Next →</a>' +
            '<a href="#prev" class="ignore-hash">← Previous</a>' +
          '</div>',
        // This is a custom variable
        gallery : '.popup_gallery',
        replaced : function($popup, $back){
          var plugin = this,
              $wrap = $('.popup_wrap', $popup);
          // Animate the popup to new size
          $wrap.animate({
            width : $wrap.parent().outerWidth(true),
            height : $wrap.children().children().outerHeight(true)
          }, {
            duration : 500,
            easing : 'easeOutBack',
            step : function(){
              // Need to center the poup on each step
              $popup
                .css({
                  top : plugin.getCenter().top,
                  left : plugin.getCenter().left
                });
            },
            complete : function(){
              // Fade in!
              $wrap
                .children()
                .animate({opacity : 1}, plugin.o.speed, function(){
                  plugin.center();
                  plugin.o.afterOpen.call(plugin);
                });
            }
          });
        },
        show    : function($popup, $back){
          var plugin = this,
            $wrap = $('.popup_wrap', $popup);
          // Center the plugin
          plugin.center();
          // Default fade in
          $popup
            .animate({opacity : 1}, plugin.o.speed, function(){
              plugin.o.afterOpen.call(plugin);
            });
          // Set the inline styles as we animate later
           $popup.css({
            width   :'500px'
          });
          $wrap.css({
            width   :'100%',
            height   : $wrap.outerHeight(true)
          });
        },
        afterClose    : function(){
          this.currentIndex = undefined;
        }
      };
    $(function(){
      /*---------------------
        POPUP
      */
      $('.popup_gallery').popup(gallerySettings);
      /*---------------------
        NEXT & PREVIOUS LINKS
      */
      $(document).on('click', '[href="#next"], [href="#prev"]', function(e){
        e.preventDefault();
        var $current = $('.popup_active'),
            popup = $current.data('popup'),
            $items = $(popup.o.gallery);
        // If this is the first time
        // and we don't have a currentIndex set
        if( popup.currentIndex === undefined ){
          popup.currentIndex = $items.index($current);
        }
        // Fade the current item out
        $('.'+popup.o.contentClass)
          .animate({opacity : 0}, 'fast', function(){
            // Get the next index
            var newIndex = $(e.target).attr('href') === '#next'
              ? popup.currentIndex + 1
              : popup.currentIndex - 1;
            // Make sure the index is valid
            if( newIndex > $items.length -1 ){
              popup.currentIndex = 0;
            }else if( newIndex < 0 ){
              popup.currentIndex = $items.length - 1;
            }else{
              popup.currentIndex = newIndex;
            }
            // Get the new current link
            $current = $($items[popup.currentIndex]);
            // Load the content
            popup.open($current.attr('href'), undefined, $current[0]);
          });
      });
    });


</script>

@endsection