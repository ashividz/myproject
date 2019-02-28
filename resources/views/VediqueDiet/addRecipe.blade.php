<div class="col-md-8 col-md-offset-2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Add Recipes</h2>
        </div>
        <div class="panel-body">
            <hr>
            <form method="POST" role="form" class="form-inline" id="form" data-message="Do you really want to save this Recipe?">
                <fieldset>
                    <ol> 
                         <li>
                            <label>Name*</label>
                            <input type="text" name="name" style="width:390px" required>
                        </li>

                        <li>
                            <label>cooking_time *</label>
                            <input type="text" name="cooking_time" step="0.01" style="width:390px" required>
                        </li>
                        <li>
                            <label>serving *</label>
                            <input type="number" name="serving" step="0.01" style="width:390px" required >
                        </li>
                        <li>
                            <label>calories *</label>
                            <input type="number" name="calories" step="0.01" style="width:390px" required>
                        </li>
                        <li>
                            <label>steps *</label>
                            <textarea name="steps" cols="40" rows="4"  style="width:390px" required></textarea>
                        </li>
                        <li>
                            <label>tips *</label>
                           <input type="text" name="tips" step="0.01"  style="width:390px" required>
                        </li>
                        <li>
                            <label>tag</label>
                            <input type="text" name="tag" step="0.01" style="width:390px" required>
                        </li>
                         <li>
                            <label>image *</label>
                            <input type="text" name="image"  style="width:390px" required>
                        </li>
                        <li>
                            <label>ingredients *</label>
                            <textarea  name="ingredients" cols="40" rows="4"  style="width:390px" required> </textarea>
                        </li>
                        <li>
                            <label>prakriti *</label>
                           <input type="text" name="prakriti" style="width:390px" required>
                        </li>
                         <li>
                            <label>Veg *</label>
                           <input type="text" name="veg" style="width:390px" required >
                        </li>
                         <li>
                            <label>Non Veg *</label>
                           <input type="text" name="nonveg" style="width:390px" required >
                        </li>
                         <li>
                            <label>Egg *</label>
                           <input type="text" name="egg" style="width:390px" required >
                        </li>
                         <li>
                            <label>BreakFast *</label>
                           <input type="text" name="breakfast" style="width:390px" required >
                        </li>
                         <li>
                            <label>MidMorning *</label>
                           <input type="text" name="midmorning" style="width:390px" required >
                        </li>
                         <li>
                            <label>Lunch *</label>
                           <input type="text" name="lunch" style="width:390px" required >
                        </li>
                         <li>
                            <label>Snack *</label>
                           <input type="text" name="snack" style="width:390px" required >
                        </li>
                         <li>
                            <label>Dinner *</label>
                           <input type="text" name="dinner" style="width:390px" required >
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

            @if($recipes)

                <table class="table table-bordered">
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>cooking_time</th>
                            <th>serving</th>
                            <th>calories</th>
                            <th>steps</th>
                            <th>tips</th>
                            <th>tag</th>
                            <th>image</th>
                            <th>ingredients</th>
                            <th>prakriti</th>
                            <th>Button</th>
                        </tr>
                    </thead>
                        
                    <tbody>
            @if($recipes <> NULL)
                @foreach($recipes as $recipe)
                    <?php $i++;?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                {{$recipe->name or " "}}
                            </td>

                            <td>
                               {{$recipe->cooking_time or " "}}
                            </td>
                            
                            <td>{{ $recipe->serving or " "}}</td>
                            <td>
                                {{ $recipe->calories or " "}}
                            </td>
                            <td>
                                 {{ $recipe->steps or " "}}
                            </td>
                            <td>
                                {{ $recipe->tips or " "}}
                            </td>
                            <td>
                                {{ $recipe->tag or " "}}
                            </td>
                             <td>
                                 <img src="{{$recipe->image}}" alt="Flowers in Chania">
                            </td>
                            <td>
                                {{ $recipe->ingredients or " "}}
                            </td>
                            <td>
                                {{ $recipe->prakriti or " "}}
                            </td>
                            <td>
                                 <a href="/marketing/{{$recipe->id}}/updateRecipe" target="_blank">update</a>
                            </td>
                        </tr>                
                @endforeach
            @endif

                @if(!$recipes)
                    <tr>
                        <td colspan="11">No results found</td>
                    </tr>
                @endif
                    </tbody>

                </table>
            @endif
        </div>
        {!! $recipes->render() !!}
    </div>
</div>
