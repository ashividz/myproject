<div class="panel panel-default">
    <div class="panel-heading">                
        <h5>Dispositions</h5>
    </div>  
    <div class="panel-body">        
        <div class="col-md-12">
            Name : <a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{$lead->name}}</a><br/>
            
            Lead Id : <a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{$lead->id}}</a><br/><br/>
            Patient Id :
            @if($lead->patient)
             <a href="/patient/{{$lead->patient->id}}/diet" target="_blank">{{$lead->patient->id}}</a>
            @endif
        </div>
        
        <div class="col-md-12">
            <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">                
                    <h5>CRM Dispositions</h5>
                </div>  
                <div class="panel-body">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th width="55%">Disposition</th>
                                <th>Name</th>
                                <th>Email/SMS</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php $i=0 ?>
                @foreach($lead->dispositions as $disposition)
                    <?php $i++ ?>
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('jS-M-y h:i A', strtotime($disposition->created_at)) }}</td>
                                <td><b>{{ $disposition->master->disposition_code or "" }}</b>  : 
                                    {{ $disposition->remarks }}
                                    <small class="pull-right">{!! $disposition->callback ? "Callback On : " . date('jS-M-Y h:i A', strtotime($disposition->callback)) : "" !!}</small>
                                </td>
                                <td>{{$prefix or ''}} {{ $disposition->name }}</td>
                                <td> 
                                    {!! $disposition->email ? "<span class='label label-success'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Email Sent'></span></span>" : "" !!}
                                    {!! $disposition->sms ? "<span class='label label-success'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='" . $disposition->sms . "'></span></span>" : "" !!}
                                </td>
                            </tr>
                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>      
            </div>
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">                
                        <h5>Dialer Dispositions</h5>
                    </div>  
                    <div class="panel-body">
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Disposition</th>
                                    <th>Duration</th>
                                    <th>Name</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                    <?php $i=0 ?>
                    @foreach($dialer_dispositions as $disposition)
                        <?php $i++ ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ date('jS-M-y h:i A', strtotime($disposition->eventdate)) }}</td>
                                    <td><b>{{ $disposition->disposition or "" }}</b>
                                        
                                    </td>
                                    <td>{{ $disposition->duration }}</td>
                                    <td>{{ $disposition->user->userfullname }}</td>                                    
                                </tr>
                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>      
            </div>
        </div>
    </div>
</div>