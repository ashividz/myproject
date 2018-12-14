@extends('lead.index')
@section('main')
<!-- Emails Sent Details -->

@endsection
@section('top')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">         
        </div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline">
                <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">SMS</span>
                      </div>
                      <textarea class="form-control" aria-label="With textarea" rows="4" cols="50" name="sms"></textarea>
                    </div>
                    
                </div>
                 <button class="btn btn-primary">Send</button>
                <div class="container">

                

                </div>
                <input type="hidden" id="rtodetails" name="rtodetails"/>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
       
        </div>
    </div>
</div>
@endsection

