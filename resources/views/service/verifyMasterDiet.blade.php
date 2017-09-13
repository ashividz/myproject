<div class="panel-body" id="mydiets">
        <form id="form-diet" action="#" method="post" class="form-inline">
            <table id="example" class="table table-bordered" >
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>BloodGroup</th>
                            <th>Prakriti</th>
                            <th>DayCount</th>
                            <th>BreakFast</th>
                            <th>MidMorning</th>
                            <th>Lunch</th>
                            <th>Evening</th>
                            <th>Dinner</th>
                            <th></th>
                        </tr>
                    </thead>
                        
                    <tbody>
            @if($diets <> NULL)
                @foreach($diets as $diet)
                    
                        <tr>
                            <td>{{ $diet->name}}</td>
                            <td>{{ $diet->Blood_Group}}{{$diet->Rh_Factor}}</td>
                            <td>{{ $diet->Body_Prakriti}}</td>
                            <td>{{ $diet->Day_Count }}</td> 
                            <td>
                                <div class="breakfast">{{$diet->Breakfast}}</div>
							    <i class="fa" title="Breakfast"></i>
                            </td>    
                            <td>
                                <div class="midmorning">{{ $diet->MidMorning }}</div>
                                <i class="fa" title="MidMorning"></i>
                            </td>
                            <td>
                                <div class="lunch">{{ $diet->Lunch }} </div>
                                <i class="fa" title="Lunch"></i>
                            </td>
                            <td>
                                <div class="Evening">{{ $diet->Evening }}</div>
                                <i class="fa" title="Evening">
                            </td>
                            <td>
                                <div class="Dinner">{{ $diet->Dinner }}</div>
                                <i class="fa" title="Dinner">
                                
                            </td>
                            <td> 					
                                <i class="fa fa-edit diet" id="{{$diet->id}}"></i>  
						    </td>
                        </tr>        
                @endforeach
            @endif

                @if(!$diets)
                    <tr>
                        <td colspan="8">No results found</td>
                    </tr>
                @endif
                    </tbody>

                </table>
            </form>
        </div>

<style type="text/css">
caption {
  font-weight: bold;
  text-align: center;
  font-size: large;
}
</style>


<script type="text/javascript" src="/js/modals/masterdiet.js"></script>