<style type="text/css">
    .audit {
        font-size: 9px;
    }
    .red {
        background-color: red;
        text-align: center;
    }
    .yellow {
        background-color: yellow;
        text-align: center;
    }
    .green {
        background-color: green;
        text-align: center;
    }
    h3 {
        margin-top: 5px;
        color: #fff;
    }
</style>
<div class="container">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-right">
                    @include('partials.daterange')
                </div>
                <h4>Patient Fee Audit</h4>
            </div>
            <div class="panel-body">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Nutritionist</th>
                         <th>Fee</th>
                        </tr>
                    </thead>
                    <tbody>

            @foreach($patients AS $patient)
                        <tr>
                            <td>{{$i++}}</td>
                            <td><a href="/patient/{{$patient->id}}/fee" target="_blank"> {{$patient->lead->name}} </a></td>   
                            <td>{{$patient->nutritionist}} </td> 
                          

                        @if($patient->fee)
                            <td class="green" title="{{$patient->fee->entry_date or ''}}"><span style="color:#333">{{$patient->fee->entry_date->format('jS M, Y h:i a')}}</span></td> 
                        @else                        
                            <td class="red">N</td>               
                        @endif 
                        </tr>
            @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>