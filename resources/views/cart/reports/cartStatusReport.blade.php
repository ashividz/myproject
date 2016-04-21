<div class="container1">
    <div class="panel panel-default">       
        <div class="panel-heading">         
            <div class="pull-left">
            <h4>Cart Status Report</h4>
            </div>
            <div class="pull-right">
                @include('partials/daterange')
            </div>
        </div>
        <div class="panel-body">
            <form id="form" method="post" class="form-inline" action="/service/diets/send">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Cart id</th>
                            <th>Lead id</th>
                            <th>Patient id</th>
                            <th>Name</th>
                            <th>Created By</th>
                            <th>Created At</th>                            
                            <th style="width:50%;">status</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($carts as $cart)
                        <tr>
                            <td><a href="/cart/{{$cart->id}}" target="_blank"> {{$cart->id}}</a></td>
                            <td><a href="/lead/{{$cart->lead->id}}/viewDetails" target="_blank">{{$cart->lead->id}}</a></td>
                            <td><a href="/patient/{{ $cart->lead->patient->id or ""}}/viewDetails" target="_blank">{{ $cart->lead->patient->id or ""}}</a></td>
                            <td>{{$cart->lead->name}}</td>
                            <td>{{$cart->user}}</td>
                            <td>{{$cart->created_at->format('jS M Y, h:i A')}}</td>
                            <td><div>@include('cart.partials.workflow')</div></td>                             
                            

                        </tr>
                        
                @endforeach     
                    </tbody>                    
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/workflow.js"></script>