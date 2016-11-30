<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-right">
                @include('partials/daterange')
            </div>
            <h4>Dialer Push Report</h4>
        </div>
        <div class="panel-body">

            <table class="table table-bordered">                
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Patient Id</th>
                        <th>CRE</th>
                        <th>Dispositions</th>
                        <th>Fee Entry Date</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                    
                <tbody>

            @foreach($calls as $call)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><a href="/lead/{{$call->lead_id}}/viewDispositions" target="_blank">{{$call->lead->name}}</a></td>                        
                        <td>{{$call->lead->patient_id or ""}}</td>
                         <td>{{$call->name or ""}}</td>
                    
                    @if($call->lead->dispositions->isEmpty())
                        <td></td>
                    @else
                        <td>
                            <ul>
                            @foreach($call->lead->dispositions as $disposition)
                                <li>{{$disposition->name}} : <b>({{$disposition->master->disposition}})</b> {{$disposition->remarks}}
                                    <span class="pull-right"><em><small>{{$disposition->created_at}}</em></small></span>
                                </li>
                            @endforeach
                            </ul>
                        </td>
                    @endif

                        <td>{{$call->lead->patient->fee->entry_date or ""}}</td>
                        <td>{{$call->lead->patient->fee->total_amount or ""}}</td>
                        <td>{{$call->created_at}}</td>
                    </tr>

            @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>