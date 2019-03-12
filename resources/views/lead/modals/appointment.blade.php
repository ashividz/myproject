<div class="col-md-6 col-md-offset-3" id="appointmemt">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title">{{ $lead->id }} : Book Appointment</span> 
        </div>
        <div class="panel-body">
            <div class="col-md-8 col-md-offset-2">
                <form action="/lead/{{ $lead->id }}/appointment" method="post" class="form">
                    <div class="form-group">
                        <label class="col-md-4">Date </label>
                        <input type="text" id="datepicker" name="date" class="" placeholder="Date" required></input>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Time </label>
                        <input type="time" name="usr_time" required></input>
                    </div>
                
                       
                    
                    <div class="form-group">
                        <textarea name="remark" class="form-control" placeholder="Remark" required></textarea>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                       
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}"></input>
                        

                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    $( "#datepicker" ).datepicker({
        minDate: 0 
    });
});

</script>