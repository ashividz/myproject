
<div class="" id="order">
    <div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="panel-title">Create Cart : <h4 style="display: inline">{{$lead->name}}</h4></div>
        </div>
        <div class="panel-body">
            <validator name="validation">
                <form id="form-order" class="form-inline" action="/lead/{{$lead->id}}/cart/create" method="post">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                
                                <td width="25%">
                                    <label>DOB <div class='asterix'>*</div> :</label>   
                                    {!!$lead->dob == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewPersonalDetails" target="_blank" class="required"></a>' : $lead->dob!!}
                                    </div>
                                </td>
                                <td>
                                    <label>Gender <div class='asterix'>*</div> : </label>   
                                    {!!$lead->gender == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewPersonalDetails" target="_blank" class="required"></a>' : $lead->gender!!}
                                </td>
                                <td>
                                    <label>Email <div class='asterix'>*</div> : </label>  
                                    {!!$lead->email == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="required"></a>' : $lead->email!!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Phone <div class='asterix'>*</div> :</label>   
                                    {!!$lead->phone == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="required"></a>' : $lead->phone!!}
                                </td>
                                <td>
                                    <label>Mobile : </label>  
                                    <div class="{{$lead->mobile == '' ? 'warning' : 'available'}}">{{$lead->mobile}}</div>
                                </td>
                                <td>
                                    <label>Address :</label>   
                                    {!! $lead->address == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="warning"></a>' : $lead->address !!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Country <div class='asterix'>*</div> :</label>   
                                    {!! $lead->country == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="required"></a>' : $lead->country !!}
                                </td>
                                <td>
                                    <label>Region/State <div class='asterix'>*</div> :</label>   
                                    {!! $lead->state == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="required"></a>' : $lead->state !!}
                                </td>
                                <td>
                                    <label>City <div class='asterix'>*</div> :</label>   
                                    {!! $lead->city == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="required"></a>' : $lead->city !!}
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <label>PIN/ZIP :</label>  
                                    {!! $lead->zip == '' ? '<a href="http://amikus1/lead/'.$lead->id.'/viewContactDetails" target="_blank" class="warning"></a>' : $lead->zip !!}
                                </td>
                                <td>
                                    <label>Source <div class='asterix'>*</div> :</label> 
                                    {!! $lead->sources->isEmpty() ? '<a href="http://amikus1/lead/'.$lead->id.'/viewDetails" target="_blank" class="required"></a>' : $lead->source->source_name !!}
                                </td>
                                <td>
                                    <label>CRE <div class='asterix'>*</div> :</label> 
                                    {!! $lead->cres->isEmpty() ? '<a href="http://amikus1/lead/'.$lead->id.'/viewDetails" target="_blank" class="required"></a>' : $lead->cres->first()->cre !!}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label>Currency :</label>
                                    <select class="form-control" name="currency" required>
                                        <option value="">Select Currency</option>
                                    @foreach($currencies as $currency)
                                        @if($currency->id == 1 && $lead->country == 'IN')
                                            <option value="{{$currency->id}}" selected>
                                            {{ $currency->name }} ({{ $currency->symbol }})
                                        </option>
                                        @else
                                        <option value="{{$currency->id}}">
                                            {{ $currency->name }} ({{ $currency->symbol }})
                                        </option>
                                        @endif
                                    @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            @if($lead->dob <> '' && $lead->gender <> '' && $lead->email <> '' && $lead->phone <> '' && $lead->country <> '' && $lead->state <> '' && $lead->city <> '' && $lead->source_id <> '')
                    <button type="submit" class="btn btn-primary">Create</button>
            @endif
                    {{ csrf_field() }}
                </form>
            </validator>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function() {
    $( "#datepicker" ).datepicker({  
        maxDate: 0,
        dateFormat: 'dd-mm-yy' 
    });
});
</script>
<style type="text/css">
    .available {
        display: inline;
    }
    .asterix {
        display: inline;
        color: #D43F3A;
    }
    .required {
        display: inline;
    }
    .required:before {
        content: "This field is required";
        color: #D43F3A;
        font-weight: 700;
    }
    .warning {
        display: inline;
    }
    .warning:before {
        content: "This field is important";
        color: #2E6DA4;
        font-weight: 700;
    }
</style>