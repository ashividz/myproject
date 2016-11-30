<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Duplicate Email</h4>
        </div>
        <div class="panel-body">

            <table class="table table-bordered">                
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lead Id</th>
                        <th>Patient Id</th>
                        <th>Clinic</th>
                        <th>Enquiry No</th>
                        <th>Registration No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Alt Email</th>
                        <th>Phone</th>
                        <th>Mobile</th>
                    </tr>
                </thead>
                    
                <tbody>

            @foreach($leads as $lead)
                    <tr class="{{$lead->patient ? 'red' : ''}}">
                        <td></td>
                        <td><a href="/lead/{{ $lead->id }}/viewContactDetails" target="_blank">{{ $lead->id }}</a></td>
                        <td>{{ $lead->patient->id or "" }}</td>
                        <td>{{ $lead->clinic }}</td>
                        <td>{{ $lead->enquiry_no }}</td>
                        <td>
                    @if(isset($lead->registration_no))
                            <a href="http://crm/patient.php?clinic={{ $lead->clinic }}&registration_no={{ $lead->registration_no }}" target="_blank">{{ $lead->registration_no }}</a>
                        </td>
                    @endif    
                        <td>{{ $lead->name }}</td>
                        <td>{{ $lead->email }}</td>
                        <td>{{ $lead->email_alt }}</td>
                        <td>{{ $lead->phone }}</td>
                        <td>
                            {{ $lead->mobile }}
                            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
                                <div class="pull-right">
                                    <a href="/lead/{{$lead->id}}/delete" target="_blank"><i class="glyphicon glyphicon-remove red"></i></a>
                                </div>
                            @endif

                        </td>
                    </tr>                
            @endforeach

            </table>
        </div>
    </div>


</div>