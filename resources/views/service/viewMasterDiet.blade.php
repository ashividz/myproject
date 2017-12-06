<div class="col-sm-10 col-sm-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">Search</div>
        <div class="panel-body">
            <form class="form" method="POST"> 
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                        <ol>						
                            <li>
                                <label>Program *</label>
                                <select name="program_id" id="program_id" required>
                                <option></option>
                                @foreach($programs as $pr)
                                <option value="{{$pr->id}}" >{{$pr->name}}</option>
                                @endforeach
                                </select>
                            </li>
                            <li>
                                <label>BloodGroup *</label>
                                <select name="blood_group_name" id="blood_group_name" required>
                                <option></option>
                                @foreach($blood_groups as $bg)
                                <option value="{{$bg->name}}" >{{$bg->name}}</option>
                                @endforeach
                                </select>
                            </li>
                            <li>
                                <label>RhFactor *</label>
                                <select name="rhfactor_name" id="rhfactor_name" required>
                                <option></option>
                                @foreach($rh_factors as $rh)
                                <option value="{{$rh->code}}" >{{$rh->code}}</option>
                                @endforeach
                                </select>
                            </li>
                            <li>
                                <label>Prakriti *</label>
                                <select name="prakriti_name" id="prakriti_name" required>
                                <option></option>
                                @foreach($prakriti as $pk)
                                <option value="{{$pk->name}}" >{{$pk->name}}</option>
                                @endforeach
                                </select>
                            </li>
                          
                            
                        </ol>
                </fieldset>	
                <div class="row">
                    
                    <div class="col-md-4">
                        <button type="submit" name="submit" class="btn btn-success" id="showbtn">Submit</button>
                        <input class="btn btn-danger" type="reset" value="Clear form">
                    </div>
                </div>
            </form>                
        </div>
    </div>

</div>
@if($diets)
        <div class="panel-body" id="mydiets">
        <form id="form-diet" action="#" method="post" class="form-inline">
            <table id="example" class="table table-bordered" >
                <caption>{{$headings}}</caption>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>#</th>
                            <th>Tag</th>
                            <th>BreakFast</th>
                            <th>MidMorning</th>
                            <th>Lunch</th>
                            <th>Evening</th>
                            <th>Dinner</th>
                            <th>Add New Diet</th>
                        </tr>
                    </thead>
                    <tbody>
            @if($diets <> NULL)
                @foreach($diets as $diet)
                    
                        <tr>
                            <td>{{ $diet->Day_Count }}</td>
                            <td><?php $colorClass="redbox"; if($diet->isveg==1) $colorClass="greenbox";elseif($diet->isveg==-1) $colorClass="yellowbox";?> 
                            <div id="circle" class="circle <?php echo $colorClass; ?>"></div></td>
                            <td>					
                                <i class="fa fa-edit diet" id="{{$diet->id}}"></i>  
						    </td>
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
                                <i class="fa fa-edit tagdiet" id="{{$diet->id}}"></i>  
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

@endif

<style type="text/css">
caption {
  font-weight: bold;
  text-align: center;
  font-size: large;
}
.circle {
      width: 10px;
      height: 10px;
      -webkit-border-radius: 25px;
      -moz-border-radius: 25px;
      border-radius: 25px;
    }
.redbox{
    background: red;
}   
.greenbox{
    background: green;
} 
.yellowbox{
    background: yellow;
}
</style>
<script type="text/javascript" src="/js/modals/tagmasterdiet.js"></script>  
<script type="text/javascript" src="/js/modals/masterdiet.js"></script> 