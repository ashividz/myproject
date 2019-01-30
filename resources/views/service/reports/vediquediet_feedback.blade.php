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
                        <?php 
                            $i = 0;
                        ?>
                    @foreach($feedbacks as $feedback)
                       @if($feedback->lead)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$feedback->date or " "}}</td>
                                <td><a href="{{url('lead/'.$feedback->lead->id.'/viewDetails')}}" target="blank">{{$feedback->lead->name or " "}}</a></td>
                                <td>{{$feedback->lead->patient->nutritionist or " "}}</td>
                                <td>{{$feedback->feedback or " "}}</td>
                            </tr>
                        @endif
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