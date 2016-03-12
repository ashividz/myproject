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
            <li><img class="attachment" src="/images/cleardot.gif"> {{$attachment->name}}</li>
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


@include('partials/footer')
    