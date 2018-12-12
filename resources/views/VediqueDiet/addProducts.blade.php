<div class="col-md-8 col-md-offset-2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Add Products</h2>
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
                            <label>Quantity *</label>
                            <input type="text" name="quantity" step="0.01" style="width:390px" required>
                        </li>
                        <li>
                            <label>Price *</label>
                            <input type="number" name="price" step="0.01" style="width:390px" required >
                        </li>
                        <li>
                            <label>Ingredients *</label>
                            <textarea name="ingredients" cols="40" rows="4"  style="width:390px" required></textarea>
                        </li>
                        <li>
                            <label>Description *</label>
                            <textarea name="description" cols="40" rows="4"  style="width:390px" required></textarea>
                        </li>
                        <li>
                            <label>Benefit *</label>
                           <textarea type="text" name="benefit" cols="40" rows="4" style="width:390px" required></textarea>
                        </li>
                        <li>
                            <label>Buy URL</label>
                            <input type="text" name="buy_url" step="0.01" style="width:390px" required>
                        </li>
                         <li>
                            <label>Image *</label>
                            <input type="text" name="image"  style="width:390px" required>
                        </li>
                        <li>
                            <label>Vata Dosage *</label>
                            <input type="text" name="vata_dosage"  style="width:390px" required>
                        </li>
                        <li>
                            <label>Pitta Dosage *</label>
                            <input type="text" name="pitta_dosage"  style="width:390px" required>
                        </li>
                        <li>
                            <label>Kapha Dosage *</label>
                            <input type="text" name="kapha_dosage"  style="width:390px" required>
                        </li>
                        <li>
                            <label>Is Active *</label>
                            <input type="number" name="is_active"  style="width:390px" required></li>
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

            @if($products)

                <table class="table table-bordered">
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Ingredients</th>
                            <th>Description</th>
                            <th>Benefit</th>
                            <th>Buy URL</th>
                            <th>Image</th>
                            <th>Is Active</th>
                        </tr>
                    </thead>
                        
                    <tbody>
            @if($products <> NULL)
                @foreach($products as $product)
                    <?php $i++;?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                {{$product->name or " "}}
                            </td>

                            <td>
                               {{$product->quantity or " "}}
                            </td>
                            
                            <td>{{ $product->price or " "}}</td>
                            <td>
                                {{ $product->ingredients or " "}}
                            </td>
                            <td>
                                 {{ $product->description or " "}}
                            </td>
                            <td>
                                {{ $product->benefit or " "}}
                            </td>
                            <td>
                                {{ $product->buy_url or " "}}
                            </td>
                             <td>
                                 <img src="{{$product->image}}" alt="Flowers in Chania">
                            </td>
                            <td>
                                {{ $product->is_active or " "}}
                            </td>
                        </tr>                
                @endforeach
            @endif

                @if(!$products)
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