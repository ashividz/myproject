<div class="jumbotron" style="margin:0;padding:0;">
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
                <h4>Customer Feedback on VediqueDiet App</h4>
            </div>
            <div class="pull-right">
                @include('partials/daterange')
            </div>
        </div>
        <div class="panel-body">
                <table id="feedback" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Nutritionist</th>
                            <th>Feedback/Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($feedbacks as $key=>$feedback)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$feedback->date}}</td>
                            <td><a href="{{url('patient/'.$feedback->lead->patient->id.'/diet')}}" target="blank">{{$feedback->lead->name}}</a></td>
                            <td>{{$feedback->lead->patient->nutritionist}}</td>
                            <td>{{$feedback->feedback}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
        </div>
    </div>
</div>
<style type="text/css">
    .popover {
        max-width: 1024px;  
    }
</style>
<script>
$(document).ready(function(){
    $('#feedback').dataTable({
        bPaginate : false,
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
        },
    });
});
</script>