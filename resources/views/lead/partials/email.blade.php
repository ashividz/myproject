@extends('lead.index')
@section('main')
<!-- Emails Sent Details -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            
        </div>
        <div class="panel-body">        
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th width="10%">Sender</th>
                        <th width="65%">Email</th>
                        <th>SMS</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>

            @foreach($emails AS $email)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>
                            {$email->user ? $email->user->employee->name : 'cron'}}
                        </td>
                        <td>
                            <pre>{!!$email->email!!}</pre>
                        </td>
                        <td>
                            {!!$email->sms_response!!}
                        </td>
                        <td>
                            {{date('jS M, Y h:i A', strtotime($email->created_at))}}
                        </td>                       
                    </tr>
            @endforeach

                </tbody>
                
            </table>
        </div>
    </div>
</div>
@endsection
@section('top')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">         
        </div>
        <div class="panel-body">
         @if(!$lead->dnc)
            <form action="" method="POST" class="form-inline">
                <div class="form-group">
                    <select name="template_id">
                        <option>Select Template</option>

                    @foreach($templates AS $template)
                        <option value="{{$template->id}}">{{$template->subject}}</option>
                    @endforeach

                    </select>
                    <button class="btn btn-primary">Send</button>
                </div>

                <div class="container">

                @foreach($templates AS $template)
                    <div id="{{$template->id}}" class="template" style="display:none;">
                        <div style="border:1px solid #aaa;padding:5px">
                            <div>
                                {!!str_replace('$customer', $lead->name, Helper::nl2p($template->email))!!}
                            </div>
                            <ul>
                            @foreach($template->attachments AS $attachment)
                                <li><img class="attachment" src="/images/cleardot.gif"> {{$attachment->name}}</li>
                            @endforeach
                            </ul>
                        </div>
                        <hr>
                        <div style="border:1px solid #aaa;padding:5px;background-color:#f9f9f9" title="SMS Content">
                            {{$template->sms}}
                        </div>
                    </div>
                @endforeach

                </div>
                <input type="hidden" id="rtodetails" name="rtodetails"/>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        @else
            <div class="blacklisted"></div>
        @endif
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#rtodetails").val('');
        $("select").change(function(){
            $('.template').hide();
            $('#' + this.value).show();
            if($("select").val()==77)
                $("#divdeps").dialog('open');
        });
    });

    $("#divdeps").dialog({
autoOpen: false,
show: 'slide',
resizable: false,
position: 'center',
stack: true,
height: 'auto',
width: 'auto',
modal: true
});

</script>

<script>
$(function(){
    $("#save").click(function(){
        var arr = [];
        arr.push($("#inv").val());
        arr.push($("#tracking").val());     
        arr.push($('input[name=courier]:checked').val());
        arr.push($('input[name=payment]:checked').val());
        arr.push($("#product").val());
        arr.push($("#amount").val());
        arr.push($("#reason").val());
        $('.rto').val('');
        $(':radio').removeAttr('checked');
        $("#divdeps").dialog('close');
        $("#rtodetails").val(JSON.stringify(arr));
    });
});
</script>
@endsection

<div id="divdeps" style="display:none" title="">
        
<fieldset>
                    <ol>
                        <li>
                            <label>Invoice Number</label>
                            <input type="text" id="inv" class ="rto" name="inv">
                        </li>
                        <li>
                            <label>Tracking Number</label>
                            <input type="text" id="tracking" class ="rto" name="tracking">
                        </li>
                        <li>
                            <label>Courier Name</label>
                            <input type="radio" name="courier" id="courier" value="Fedex" checked="checked" > Fedex &nbsp;
                            <input type="radio" name="courier" id="courier" value="Overnite"> Overnite
                        </li>
                        <li>
                            <div class="pay">
                            <label>Mode of Payment</label>
                            <input type="radio" name="payment" id="payment" value="COD" checked="checked"> COD &nbsp;
                            <input type="radio" name="payment" id="payment" value="Already-Paid"> Already-Paid
                            </div>
                        </li>
                        <li>
                            <label>Product</label>
                            <input type="text" class="rto" id="product" name="product" >
                        </li>
                        <li>
                            <label>Amount</label>
                            <input type="text" id="amount" class="rto" name="amount" >
                        </li>
                        <li>
                            <label>RTO Reason</label>
                            <input type="text" id="reason" class ="rto" name="reason">
                        </li>
                    </ol>
                </fieldset>                 
                <div class="col-md-3">
                    <button id="save" type="submit" name="save" class="btn btn-success"> Save</button>
                </div>
                <div class="alert alert-warning" id="alert" role="alert"></div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
</div>