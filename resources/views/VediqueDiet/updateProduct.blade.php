<div class="col-md-8 col-md-offset-2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">update Product</h2>
        </div>
        <div class="panel-body">
            <hr>
            <form method="POST" role="form" class="form-inline" id="form" data-message="Do you really want to save this Recipe?">
                <fieldset>
                    <ol> 
                         <li>
                            <label>Name*</label>
                            <input type="text" name="name" style="width:390px" required value="{{$product->name}}">
                        </li>
                          <li>
                            <label>Image *</label>
                            <input type="text" name="image"  style="width:390px" required value="{{$product->image}}">
                        </li>
                        <li>
                            <label>Quantity *</label>
                            <input type="text" name="quantity" step="0.01" style="width:390px" required value="{{$product->quantity}}">
                        </li>
                        <li>
                            <label>Price *</label>
                            <input type="number" name="price" step="0.01" style="width:390px" required value="{{$product->price}}">
                        </li>
                        <li>
                            <label>Ingredients *</label>
                            <textarea name="ingredients" cols="40" rows="4"  style="width:390px" required>{{$product->ingredients}}</textarea> 
                        </li>
                        <li>
                            <label>Description *</label>
                            <textarea name="description" cols="40" rows="4"  style="width:390px" required>{{$product->description}}</textarea>
                        </li>
                        <li>
                            <label>Benefit *</label>
                           <textarea type="text" name="benefit" cols="40" rows="4" style="width:390px" required>{{$product->benefit}}</textarea>
                        </li>
                        <li>
                            <label>Buy URL</label>
                            <input type="text" name="buy_url" step="0.01" style="width:390px" required value="{{$product->buy_url}}">
                        </li>
                       
                        <li>
                            <label>Is Active *</label>
                            <input type="number" name="is_active"  style="width:390px" required value="{{$product->is_active}}">
                        </li>
                        <li>
                            <label> Immunity *</label>
                            <input type="number" name=" immunity"  style="width:390px" required value="{{$product->Immunity}}">
                        </li>
                        <li>
                            <label>Weight Loss *</label>
                            <input type="number" name="weightloss"  style="width:390px" required value = "{{$product['Weight Loss']}}">
                        </li>
                        <li>
                            <label>Green Teas *</label>
                            <input type="number" name="greenteas"  style="width:390px" required value = "{{$product['Green Teas']}}">
                        </li>
                        <li>
                            <label>Cardiac Wellness *</label>
                            <input type="number" name="cardiacwellness"  style="width:390px" required value = "{{$product['Cardiac Wellness']}}">
                        </li>
                        <li>
                            <label>Diabetic Care *</label>
                            <input type="number" name="diabeticcare"  style="width:390px" required value = "{{$product['Diabetic Care']}}">
                        </li>
                        <li>
                            <label>Skin & Hair care *</label>
                            <input type="number" name="skinhaircare"  style="width:390px" required value = "{{$product['Skin & Hair care']}}">
                        </li>
                        <li>
                            <label>Liver Care *</label>
                            <input type="number" name="livercare"  style="width:390px" required value = "{{$product['Liver Care']}}">
                        </li>
                        <li>
                            <label>Kidney Care *</label>
                            <input type="number" name="kidneycare"  style="width:390px" required value = "{{$product['Kidney Care']}}">
                        </li>
                        <li>
                            <label>Joint Pain /Arthritis *</label>
                            <input type="number" name="jointpainarthritis"  style="width:390px" required value = "{{$product['Joint Pain /Arthritis']}}">
                        </li>
                        <li>
                            <label>Cold & Cough *</label>
                            <input type="number" name="coldcough"  style="width:390px" required value = "{{$product['Cold & Cough']}}">
                        </li>
                        <li>
                            <label>Stress *</label>
                            <input type="number" name="stress"  style="width:390px" required value="{{$product->Stress}}">
                        </li>
                        <li>
                            <label>Hair Care *</label>
                            <input type="number" name="haircare"  style="width:390px" required value = "{{$product['Hair Care']}}">
                        </li>
                        <li>
                            <label>Acidity *</label>
                            <input type="number" name="acidity"  style="width:390px" required value="{{$product->Acidity}}">
                        </li>
                        <li>
                            <label>Constipation *</label>
                            <input type="number" name="constipation"  style="width:390px" required value="{{$product->Constipation}}">
                        </li>
                        <li>
                            <label>Acne/Pimples *</label>
                            <input type="number" name="acnepimple"  style="width:390px" required value="{{$product['Acne/Pimples']}}">
                        </li>
                        <li>
                            <label>Cholesterol *</label>
                            <input type="number" name="cholesterol"  style="width:390px" required value="{{$product->Cholesterol}}">
                        </li>
                        <li>
                            <label>Women Health *</label>
                            <input type="number" name="womenhealth"  style="width:390px" required value = "{{$product['Women Health']}}">
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