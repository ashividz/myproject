<div class="col-md-8 col-md-offset-2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">update Recipes</h2>
        </div>
        <div class="panel-body">
            <hr>
            <form method="POST" role="form" class="form-inline" id="form" data-message="Do you really want to save this Recipe?">
                <fieldset>
                    <ol> 
                         <li>
                            <label>Name*</label>
                            <input type="text" name="name" style="width:390px" required value = "{{$recipe->name}}">
                        </li>

                        <li>
                            <label>cooking_time *</label>
                            <input type="text" name="cooking_time" step="0.01" style="width:390px" required value = "{{$recipe->cooking_time}}">
                        </li>
                        <li>
                            <label>serving *</label>
                            <input type="number" name="serving" step="0.01" style="width:390px" required value = "{{$recipe->serving}}">
                        </li>
                        <li>
                            <label>calories *</label>
                            <input type="number" name="calories" step="0.01" style="width:390px" required value = "{{$recipe->calories}}">
                        </li>
                        <li>
                            <label>steps *</label>
                            <textarea name="steps" cols="40" rows="4"  style="width:390px" required >{{$recipe->steps}}</textarea>
                        </li>
                        <li>
                            <label>tips *</label>
                           <input type="text" name="tips" step="0.01"  style="width:390px" required value = "{{$recipe->tips}}">
                        </li>
                        <li>
                            <label>tag</label>
                            <input type="text" name="tag" step="0.01" style="width:390px" required value = "{{$recipe->tag}}">
                        </li>
                         <li>
                            <label>image *</label>
                            <input type="text" name="image"  style="width:390px" required value = "{{$recipe->image}}">
                        </li>
                        <li>
                            <label>ingredients *</label>
                            <textarea  name="ingredients" cols="40" rows="4"  style="width:390px" required > {{$recipe->ingredients}}</textarea>
                        </li>
                        <li>
                            <label>prakriti *</label>
                           <input type="text" name="prakriti" style="width:390px" required value = "{{$recipe->prakriti}}">
                        </li>
                         <li>
                            <label>Veg *</label>
                           <input type="text" name="veg" style="width:390px" required value = "{{$recipe->Veg}}">
                        </li>
                         <li>
                            <label>Non Veg *</label>
                           <input type="text" name="nonveg" style="width:390px" required value = "{{$recipe['Non Veg']}}">
                        </li>
                         <li>
                            <label>Egg *</label>
                           <input type="text" name="egg" style="width:390px" required value = "{{$recipe->Egg}}">
                        </li>
                         <li>
                            <label>BreakFast *</label>
                           <input type="text" name="breakfast" style="width:390px" required value = "{{$recipe->Breakfast}}">
                        </li>
                         <li>
                            <label>MidMorning *</label>
                           <input type="text" name="midmorning" style="width:390px" required value = "{{$recipe['Mid Morning']}}">
                        </li>
                         <li>
                            <label>Lunch *</label>
                           <input type="text" name="lunch" style="width:390px" required value = "{{$recipe->Lunch}}">
                        </li>
                         <li>
                            <label>Snack *</label>
                           <input type="text" name="snack" style="width:390px" required value = "{{$recipe->Snack}}">
                        </li>
                         <li>
                            <label>Dinner *</label>
                           <input type="text" name="dinner" style="width:390px" required value = "{{$recipe->Dinner}}">
                        </li>
                    </ol>
                </fieldset>             
                <div class="row">
                
                    <div class="col-md-4">
                        <button type="submit" name="submit" class="btn btn-success">Update</button>
                        <input class="btn btn-danger" type="reset" value="Clear form">
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">          
            </form>
        </div>  
    </div>
    
</div>
</div>