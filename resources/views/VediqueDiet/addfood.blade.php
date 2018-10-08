<div class="col-md-8 col-md-offset-2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Add Food</h2>
        </div>
        <div class="panel-body">
            <hr>
            <form method="POST" role="form" class="form-inline" id="form" data-message="Do you really want to save this Food?">
                <fieldset>
                    <ol> 
                         <li>
                            <label>Name*</label>
                            <input type="text" name="name" style="width:390px">
                        </li>

                        <li>
                            <label>Energy *</label>
                            <input type="number" name="energy" step="0.01" style="width:390px">
                        </li>
                        <li>
                            <label>Carb *</label>
                            <input type="number" name="carb" step="0.01" style="width:390px">
                        </li>
                        <li>
                            <label>Protein *</label>
                            <input type="number" name="protein" step="0.01" style="width:390px">
                        </li>
                        <li>
                            <label>Fat *</label>
                            <input type="number" name="fat" step="0.01"  style="width:390px">
                        </li>
                        <li>
                            <label>Calcium *</label>
                           <input type="number" name="calcium" step="0.01"  style="width:390px">
                        </li>
                        <li>
                            <label>Fiber</label>
                            <input type="number" name="fiber" step="0.01" style="width:390px">
                        </li>
                         <li>
                            <label>Vata *</label>
                            <input type="number" name="vata"  style="width:390px">
                        </li>
                        <li>
                            <label>Pitta *</label>
                            <input type="number" name="pitta" style="width:390px">
                        </li>
                        <li>
                            <label>Kapha *</label>
                           <input type="number" name="kapha" style="width:390px">
                        </li>
                         <li>
                            <label>Scale *</label>
                           <input type="number" name="scale" style="width:390px">
                        </li>
                        
                        <li>
                            <label>Recommendation*</label>
                            <textarea size="3" class="form-control" type="text" id="afterdinner" name="recommendation" style="width:390px"></textarea>
                        </li>
                         <li>
                            <label>Image *</label>
                            <textarea size="3" class="form-control" type="text" id="breakfast" name="image" style="width:390px" required></textarea>
                        </li>
                    </ol>
                </fieldset>             
                <div class="row">
                
                    <div class="col-md-4">
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        <input class="btn btn-danger" type="reset" value="Clear form">
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">          
            </form>
        </div>  
    </div>
    
</div>


<?php $i=0;?>
    <div class="panel panel-default">
        <div class="panel-body">

            @if($foods)

                <table class="table table-bordered">
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Energy</th>
                            <th>Protein</th>
                            <th>Carb</th>
                            <th>Fat</th>
                            <th>Calcium</th>
                            <th>Fiber</th>
                            <th>Vata</th>
                            <th>Pitta</th>
                            <th>Kapha</th>
                            <th>Scale</th>
                            <th>Recommendation</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                        
                    <tbody>
            @if($foods <> NULL)
                @foreach($foods as $food)
                    <?php $i++;?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                {{$food->name or " "}}
                            </td>

                            <td>
                               {{$food->energy or " "}}
                            </td>
                            
                            <td>{{ $food->protein or " "}}</td>
                            <td>
                                {{ $food->carb or " "}}
                            </td>
                            <td>
                                 {{ $food->fat or " "}}
                            </td>
                            <td>
                                {{ $food->calcium or " "}}
                            </td>
                            <td>
                                {{ $food->fiber or " "}}
                            </td>
                             <td>
                                 {{ $food->vata or " "}}
                            </td>
                            <td>
                                {{ $food->pitta or " "}}
                            </td>
                            <td>
                                {{ $food->kapha or " "}}
                            </td>
                            <td>
                                {{ $food->scale or " "}}
                            </td>
                            <td>
                                {{ $food->recommendation or " "}}
                            </td>
                             <td>
                                {{ $food->image or " "}}
                            </td>
                        </tr>                
                @endforeach
            @endif

                @if(!$foods)
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