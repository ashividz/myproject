<div class="container-fluid">
    <div class="row" style='width: 95%;margin: 0px auto'>

            <div class="panel panel-default">
                <div class="panel-heading"><h4 style='display: inline-block;width: 70%'>Quiz Setting</h4> <a href='/quiz/admin' style='color: #eee;display: inline-block;font-size: 16px;padding: 3px 10px;background: #2487a8;border-radius: 7px'>Quiz Admin</a></div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class='row'>
                         <div class="col-md-6">
           <form class="form-horizontal" role="form" method="POST" action='/quiz/edit/addToGroup'>
           <input type="hidden" name="_token" value="{{ csrf_token() }}">
             <ul class='list-group'>
               <div class='list-group-item' style='background: #ccc; color: #222;border: 1px solid #bbb;padding-top: 2px;padding-bottom: 15px' >
                   @if($setting->questions)
                      @foreach($setting->questions->unique('q_group') AS $question)
                        @if($question->q_group != "common")
                         &nbsp; <input type='radio' name='group' value='{{$question->q_group}}' /> {{$question->q_group}}
                         @endif
                      @endforeach
                  @endif
                   <button class="btn btn-primary pull-right" style='display: inline-block;margin-bottom: 10px' type="submit">
                              Add to Group
                  </button>
                </div>
            </ul>
            <div class='row' style='margin-bottom: 20px;margin-top: 10px'>
            <div class='col-md-6'>
            <p style='font-weight: bold'><input type='checkbox' id='checkAll' name='checkAll' value='all' />  &nbsp;Select All</p>
            </div><div class='col-md-6'><label>Filter:</label> 
            <select id='filter_group' style='width: 120px'>
              <option value='allgroup'>Show All</option>
              @foreach($setting->questions->unique('q_group') AS $question)
              <?php
              $qgp = strtolower($question->q_group);
              $group_class = preg_replace('/\s+/', '_', $qgp);
              ?>
                  <option value='{{$group_class}}'>{{$question->q_group}}</option>
               @endforeach

            </select>
            </div>
            </div>
            <?php
                  $i=1;
                       
                       ?>
            <ul class='list-group'>
            
                 @if($users)
                 <div class='col-md-4'>
                    @foreach($users->sortBy('name') AS $user)
                       <?php
                        
                          $qgp = strtolower($user->group);
                          $group_class = preg_replace('/\s+/', '_', $qgp);
                        ?>
                       <p class='usersp {{$group_class}}' style='border-bottom: 1px solid #ddd;padding-bottom: 3px' ><input type='checkbox' name='users[]' value='{{$user->id}}' /> {{$user->name}}<br>
                       <em style='font-size: 9px;font-weight: bold' > {{$user->group}}</em>
                       </p>
                       <?php
                       if($i%40==0)
                       {

                        echo "</div><div class='col-md-4'>";
                       }
                       $i++;
                       ?>
                    @endforeach
                    </div>
                @endif
              
            </ul>
            <input type='hidden'  name='quiz_id'  value='{{$setting->id}}' />
             <button class="btn btn-primary" type="submit">
                            Add Group
            </button>
            </form>
 </div>
 <div class="col-md-6">
 
<form class="form-horizontal" role="form" method="POST" action='/quiz/edit/{{$setting->id}}'>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Title</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="title" value="{{ $setting->title }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Start Time</label>
                            <div class="col-md-6">
                                <input type="text" class="date form-control" name="start_time" value="{{ $setting->start_time }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">End Time</label>
                            <div class="col-md-6">
                                <input type="text" class="date form-control" name="end_time" value="{{ $setting->end_time }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Duration</label>
                            <div class="col-md-6">
                               <input type="text" class="form-control" name="quiz_duration" value="{{ $setting->quiz_duration }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">&nbsp;</label>
                            <div class="col-md-6">
                                Active <input type="radio"  name="active" <?php if($setting->active=='1') echo "checked='checked'"; ?> value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inactive <input type="radio"  name="active" <?php if($setting->active=='0') echo "checked='checked'"; ?> value="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>


                    @if($replies->count())
                    <div class='col-md-12' style='background: #ccc; padding: 10px 10px;margin-top: 70px'>
                        <form   id='reattempt_form' action='/quiz/reattempt' method='post' style='margin: 0px'> 
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                         <select name='user_id'>
                         <option value=''>Select User</option>
                            @foreach($replies AS $reply)
                                <option value='{{$reply->user_id}}'>{{$reply->user->employee->name}}</option>
                            @endforeach
                         </select>&nbsp;
                         <button class="btn btn-primary" type="submit">
                            Re-Attempt
                        </button>
                        <input type='hidden' name='quiz_id' value='{{$setting->id}}' />
                        <span id='reattempt_status'>{{\Session::get('status')}}</span>
                      </form>
                      @if($reattempt)
                      <p style='margin-top: 10px;padding-left: 10px'><b>Questions left:</b> {{substr_count($reattempt->questions, ", ")+1}}</p>
                      <p style='margin-top: 10px;padding-left: 10px'><b>Duration left:</b> {{$reattempt->duration}}</p>
                      @endif
                      </div>
                    @endif
 </div>
                    </div>
                </div>
            </div>
       
    </div>
</div>
<script type="text/javascript">
$(function() {
    $('.date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        format: 'YYYY-MM-DD h:mm'         
    });


     $("#reattempt_form2").submit(function(event) {


        event.preventDefault();
        var url = $("#reattempt_status").attr('action'); 
         $('#reattempt_status').html("Please Wait..");
        $.ajax(
        {
           type: "post",
           url: url,
           data: $("#reattempt_form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               //alert(data);

               setTimeout(function()
                {
                   $('#reattempt_status').html(data);
                }, 100000);
           }
        });
        return false; // avoid to execute the actual submit of the form.
    }); 

      $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

      $("#filter_group").change(function () {
        var group_class = $(this).val();
        if(group_class=='allgroup')
          $('.usersp').fadeIn();
        else
        {
          $('.usersp').fadeOut();
          $('.'+group_class).fadeIn();
        }
    });
    
});
</script>