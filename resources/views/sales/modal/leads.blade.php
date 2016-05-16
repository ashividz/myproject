<table id="leads" class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>      
            <th>Name</th>
            <th>Lead Entry Date</th>
            <th>Lead Assign Date</th>
            <th>Lead Source</th>
            <th>Status</th>
            <th width="30%">Disposition</th>
        </tr>
    </thead>
    <tbody>
    @foreach($leads as $lead)
        <tr>
            <td>{{ $i++ }}</td>
            <td>
                <a href='/lead/{{ $lead->id }}/viewDispositions' target="_blank">{{ trim($lead->name) <> "" ? $lead->name : "No Name"}}</a>
            </td>
            <td>
                {{ $lead->created_at->format('jS M, Y') }}
            </td>
            <td>
                @if( isset($lead->cre) )
                    {{ $lead->cre->created_at->format('jS M, Y') }}
                @endif
            </td>
            <td>
                {{ $lead->source->master->source_name or "" }}
            </td>
            <td>
                {{ $lead->status->name or ""}}
            </td>
            <td>
                @if(isset($lead->disposition))
                    [{{ $lead->dispositions->count()}}]
                    <b>{{ $lead->disposition->master->disposition or ""}}</b> :
                    {{ $lead->disposition->remarks or ""}}
                    <small class="pull-right">
                        <em>
                            {{ $lead->disposition->name }} [{{date('M j, Y h:i A',strtotime($lead->disposition->created_at))}}]
                        </em>
                    </small>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>