@include('../partials/header')
@include('../partials/menu')
<link type="text/css" rel="stylesheet" href="{{url('css/jquery-te-1.4.0.css')}}">
<style tyle="text/css">
#email {
    display:block;
    margin:0 0 10px;
    padding:6px;
    width:100%;
    height:1000px;
    background:#FFF;
    border:#AAA 1px solid;
    font-size:13px;
}
</style>
<script type="text/javascript" src="{{url('js/jquery-te-1.4.0.min.js')}}" charset="utf-8"></script>
<script type="text/javascript" src="/js/form-ajax.js" charset="utf-8"></script>
<div class="container" style="margin-top:1em;">
    @include('../partials/email_templates')
</div>

@if($template !=null)

<div class="container" style="width:80%;border:solid 1px black;">


  <h2>Edit email template({{$template->id}})</h2>
  
    <form action="/email/template/update" method="POST" id="form">
    {{csrf_field()}}
        <input type="hidden" name="template_id" value="{{$template->id}}" />
        <div class="checkbox">
            <label for ="bulk"><input type="checkbox" name="bulk" {{$template->bulk?"checked":""}} /> Bulk</label>
        </div>

        <div>
          <label for="from">From:</label>
          <input type="text"  name="from" value="{{$template->from}}" style="width:50em;"/>
        </div>
        <br/>
        <div >
          <label for="subject">Subject:</label>
          <input type="text" name="subject"value="{{$template->subject}}" style="width:50em;"/>
        </div>
        <div >
            <label for="email">Email:</label>
            <div><button id="toggleHTML">Toggle HTML</button> </div>
            <textarea  id="email" name="email">{!!$template->email!!}</textarea>
        </div>

        <div>
            <label for="sms">SMS:</label>
            <textarea name="sms" id="sms" style="width:100%" maxlength="160">{!!$template->sms!!}</textarea>
        </div>

        <ul>
            @foreach($template->attachments AS $attachment)
            <li><img class="attachment" src="/images/cleardot.gif"> {{$attachment->name}} <a class='popup_gallery' href='/show/emailAttachment/{{$attachment->id}}'>View</a> &nbsp;<a class='popup_gallery' href='/update/emailAttachment/{{$attachment->id}}?attachment_name={{urlencode($attachment->name)}}'>Change</a></li>
            @endforeach
        </ul>

        <button class ="btn btn-primary"type="submit">Update</button>

      </form>
</div>


<script>
    $('#email').jqte();
    
    // settings of status
    var jqteStatus = true;
    $("#toggleHTML").click(function(event)
    {
        event.preventDefault();
        jqteStatus = jqteStatus ? false : true;
        $('#email').jqte({"#toggleHTML" : jqteStatus})
    });
</script>
@endif

<link href="/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="/js/jquery-ui.min.js"></script>

<link href="/css/popup.css" media="all" type="text/css" rel="stylesheet">
<script src="/js/jquery.popup.js"></script>
<script>


    /*---------------------
      SETTINGS
    */
    var gallerySettings = {
        
        height: 300,
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
            width   :'700px'
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
@include('partials/footer')
    