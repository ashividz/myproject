<div class="container">
    
    <div class="col-md-5">
            <form class="form-horizontal well" id="form" action="" method="post" enctype="multipart/form-data">         
            <fieldset>
                <legend>Upload Excel file</legend>
                <div class="control-group">
                    <div>
                        <label>Excel File:</label>
                    </div>
                    <div class="controls">
                        <input type="file" name="file" id="file" class="input-large" required>
                    </div>
                </div>
                <hr>
                <div class="control-group">
                    <div class="controls">
                    <button type="submit" id="upload" name="upload" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </fieldset>
        </form>
    </div>


    <div class="col-md-7">
        <fieldset class="well">
            <h4>Table Format</h4>
            <table class="table table-bordered">
                <tr>
                    <td>Title</td>
                    <td>created_at</td>
                    <td>start</td>
                    <td>end</td>
                    <td>duration</td>
                    <td>Acticve</td>
                    <td>Action</td>
                </tr>
                 @foreach($settings AS $setting)
                                 <tr>
                                        
                                        <td>
                                           @if($setting->title)
                                           {{$setting->title}}
                                           @endif
                                        </td>
                                          <td>
                                           @if($setting->created_at)
                                           {{$setting->created_at}}
                                           @endif
                                        </td>
                                          <td>
                                           @if($setting->start_time)
                                           {{$setting->start_time}}
                                           @endif
                                        </td>
                                          <td>
                                           @if($setting->end_time)
                                           {{$setting->end_time}}
                                           @endif
                                        </td>
                                          <td>
                                           @if($setting->quiz_duration)
                                           {{$setting->quiz_duration}}
                                           @endif
                                        </td>

                                          <td>
                                         
                                           @if($setting->active)
                                            <span class="glyphicon glyphicon-ok" style="color:green;"></span>
                                          @else
                                            <span class="glyphicon glyphicon-remove" style="color:red;"></span>
                                           @endif
                                           
                                        </td>
                                          <td>
                                          <a href='/quiz/edit/{{$setting->id}}'>Edit</a>&nbsp;&nbsp;<a href='/quiz/report/{{$setting->id}}'>Report</a>
                                        </td>



                @endforeach
            </table>
        </fieldset>
    </div>
    <table id="table" class="table table-bordered">
        
    </table>
</div>

<style type="text/css">
    table td {
        background-color: #f9f9f9;
        font-weight: 600;
    }
</style>
