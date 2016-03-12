<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">Search</div>
        <div class="panel-body">
            <form class="form" method="POST"> 
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row"> 
                    <div class="col-md-2">
                        Name
                        <input name="name" class="form-input">
                    </div>
                    <div class="col-md-2 col-md-offset-1">
                        Lead Id / Patient Id 
                        <input name="enquiry_no" class="form-input">
                    </div>

                    <div class="col-md-2 col-md-offset-1">
                        Phone / Mobile
                        <input name="mobile" class="form-input">
                    </div>

                    <div class="col-md-2 col-md-offset-1">
                        Email
                        <input name="email" class="form-input">
                    </div>
                </div>
                <hr>
                <div class="row">
                    
                    <div class="col-md-4">
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        <button type="submit" name="reset" class="btn btn-danger">Reset</button>
                    </div>
                </div>
            </form>                
        </div>
    </div>


<?php $i=0;?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left"><b>Search For : </b> </div>
            <div style="margin-left:80px"> <i>{!! $searchFor or "Nothing To Search For" !!}</i></div>
        </div>
        <div class="panel-body">

            @if($leads)

                <table class="table table-bordered">
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lead Id</th>
                            <th>Patient Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Alt Email</th>
                            <th>Phone</th>
                            <th>Mobile</th>
                        </tr>
                    </thead>
                        
                    <tbody>
            @if($leads <> NULL)
                @foreach($leads as $lead)
                    <?php $i++;?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                <a href="/lead/{{ $lead->id }}/viewDispositions" target="_blank">{{ $lead->id }}</a>
                                <div class="pull-right"><em><small>{{$lead->cre->cre or ''}}</small></em></div>
                            </td>

                            <td>
                                <a href="/patient/{{ $lead->patient->id or ''}}/diet" target="_blank">{{ $lead->patient->id or '' }}</a>
                                <div class="pull-right"><em><small>{{$lead->patient->nutritionist or ''}}</small></em></div>
                            </td>
                            
                            <td>{{ $lead->name }}</td>
                            <td>
                                @if($lead->dnc)
                                    DNC
                                @elseif($lead->patient && $lead->patient->hasTag('VIP'))
                                    VIP
                                @else
                                    {{$lead->email}}
                                @endif
                            </td>
                            <td>
                                @if($lead->dnc)
                                    DNC
                                @elseif($lead->patient && $lead->patient->hasTag('VIP'))
                                    VIP
                                @else
                                    {{$lead->email_alt}}
                                @endif
                            </td>
                            <td>
                                @if($lead->dnc)
                                    DNC
                                @elseif($lead->patient && $lead->patient->hasTag('VIP'))
                                    VIP
                                @else
                                    {{$lead->phone}}
                                @endif
                            </td>
                            <td>
                                @if($lead->dnc)
                                    DNC
                                @elseif($lead->patient && $lead->patient->hasTag('VIP'))
                                    VIP
                                @else
                                    {{$lead->mobile}}
                                @endif

                                @if(Auth::user()->hasRole('admin'))
                                    <div class="pull-right">
                                        <a href="/lead/{{$lead->id}}/delete" target="_blank"><i class="glyphicon glyphicon-remove red"></i></a>
                                    </div>
                                @endif

                            </td>
                        </tr>                
                @endforeach
            @endif

                @if(!$leads)
                    <tr>
                        <td colspan="11">No results found</td>
                    </tr>
                @endif
                    </tbody>

                </table>
            @endif
        </div>
    </div>
</div>