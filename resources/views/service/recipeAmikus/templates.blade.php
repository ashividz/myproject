<div class="container">
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Add Recipe</h4>
      </div>
      <div class="panel-body">
      <div id=msg></div>
         <form id="form-template" method="POST" class="form" action="{{url('/service/storeRecipe')}}" enctype="multipart/form-data" accept-charset="UTF-8">
         	<div class="row">
         		<!-- @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif -->
            @if(session()->has('activatMessage'))
                <div class="alert alert-success">
                    {{ session()->get('activatMessage') }}
                </div>
            @endif
            @if(Session::has('info'))
              <div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('info') }}</div>
            @endif
         	</div>
            <div class="row">
               <div class="col-sm-6">
                  <div class="form-group {{ $errors->has('txtName') ? 'has-error' : '' }}">
                     <label for="Recipe Name" class="cols-sm-2 control-label">Recipe Name</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                           <input type="text" class="form-control" name="fckTxtName" id="fckTxtName"  placeholder="Enter your Name"/>
                           
                        </div>
                        
                     </div>
                  </div>
                  <div class="form-group {{ $errors->has('txtCookingTime') ? 'has-error' : '' }}">
                     <label for="Cooking Time" class="cols-sm-2 control-label">Cooking Time</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-clock-o fa" aria-hidden="true"></i></span>
                           <input type="text" class="form-control" name="txtCookingTime" id="txtCookingTime"  placeholder="Enter your Cooking Time"/>
                           
                        </div>
                        <span class="text-danger">{{ $errors->first('txtCookingTime') }}</span>
                     </div>
                  </div>
                  <div class$('#myModal').modal('show');="form-group {{ $errors->has('txtServing') ? 'has-error' : '' }}">
                     <label for="Serving" class="cols-sm-2 control-label">Serving</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-black-tie fa" aria-hidden="true"></i></span>
                           <input type="number" class="form-control" name="txtServing" id="txtServing"  placeholder="Enter your Serving"/>
                           
                        </div>
                        <span class="text-danger">{{ $errors->first('txtServing') }}</span>
                     </div>
                  </div>
                  <div class="form-group {{ $errors->has('txtCalories') ? 'has-error' : '' }}">
                     <label for="Calories" class="cols-sm-2 control-label">Calories</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-bolt fa-lg" aria-hidden="true"></i></span>
                           <input type="number" class="form-control" name="txtCalories" id="txtCalories"  placeholder="Enter your Calories"/>
                           
                        </div>
                        <span class="text-danger">{{ $errors->first('txtCalories') }}</span>
                     </div>
                  </div>
                  <div class="form-group {{ $errors->has('txtSteps') ? 'has-error' : '' }}">
                     <label for="Steps" class="cols-sm-2 control-label">Steps</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-step-forward fa" aria-hidden="true"></i></span>
                           <textarea class="form-control" name="txtSteps" id="txtSteps" style="height: 90px"></textarea>
                           
                        </div>
                        <span class="text-danger">{{ $errors->first('txtSteps') }}</span>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  
                  <div class="form-group {{ $errors->has('txtTips') ? 'has-error' : '' }}">
                     <label for="Tips" class="cols-sm-2 control-label">Tips</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-info fa-lg" aria-hidden="true"></i></span>
                           <input type="text" class="form-control" name="txtTips" id="txtTips"  placeholder="Enter your Tips"/>
                          
                        </div>
                         <span class="text-danger">{{ $errors->first('txtTips') }}</span>
                     </div>
                  </div>
                  <div class="form-group {{ $errors->has('txtTag') ? 'has-error' : '' }}">
                     <label for="Tag" class="cols-sm-2 control-label">Tag</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-tags fa-lg" aria-hidden="true"></i></span>
                           <input type="text" class="form-control" name="txtTag" id="txtTag"  placeholder="Enter your Tag"/>
                           
                        </div>
                        <span class="text-danger">{{ $errors->first('txtTag') }}</span>
                     </div>
                  </div>
                  <div class="form-group {{ $errors->has('txtImg') ? 'has-error' : '' }}">
                     <label for="Image" class="cols-sm-2 control-label">Image</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-picture-o fa-lg" aria-hidden="true"></i></span>
                           <input type="text" class="form-control" name="txtImg" id="txtImg"  />
                           
                        </div>
                        <span class="text-danger">{{ $errors->first('txtImg') }}</span>
                     </div>
                  </div>
                  <div class="form-group {{ $errors->has('txtIngredients') ? 'has-error' : '' }}">
                     <label for="Ingredients" class="cols-sm-2 control-label">Ingredients</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></span>
                           <textarea class="form-control" name="txtIngredients" id="txtIngredients"></textarea>
                           
                        </div>
                        <span class="text-danger">{{ $errors->first('txtIngredients') }}</span>
                     </div>
                  </div>
                  <div class="form-group {{ $errors->has('ddlPrakriti') ? 'has-error' : '' }}">
                     <label for="Prakriti" class="cols-sm-2 control-label">Prakriti</label>
                     <div class="cols-sm-10">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-pagelines fa-lg" aria-hidden="true"></i></span>
                           <select class="form-control" name="ddlPrakriti[]" id="ddlPrakriti[]" multiple="multiple">
                           	<option value="-" selected="selected" disabled="disabled">Select</option>
                           	<option value="Vata">Vata</option>
                           	<option value="Kapha">Kapha</option>
                           	<option value="Pitta">Pitta</option>
                           </select>
                        </div>
                        <span class="text-danger">{{ $errors->first('ddlPrakriti') }}</span>
                     </div>
                  </div>
               </div>
            </div>
            <div class="form-group">
               <!-- <button type='submit' class='btn btn-primary'>Submit</button> -->
               <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".parent" onclick="myFunction()">Submit</button>
               <button type='reset' class='btn btn-danger'>Cancel</button>
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
         </form>
      </div>
   </div>
   <!-- script for front form modal -->
   <script>
    function myFunction() {
      $('.parent').modal('show');
      var fckTxtName = document.getElementById('fckTxtName').value;
      document.getElementById("editTxtName").value = fckTxtName;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var txtCookingTime = document.getElementById('txtCookingTime').value;
      document.getElementById("editTxtCookingTime").value = txtCookingTime;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var txtServing = document.getElementById('txtServing').value;
      document.getElementById("editTxtServing").value = txtServing;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var txtCalories = document.getElementById('txtCalories').value;
      document.getElementById("editTxtCalories").value = txtCalories;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var txtSteps = document.getElementById('txtSteps').value;
      document.getElementById("editTxtSteps").value = txtSteps;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var txtTips = document.getElementById('txtTips').value;
      document.getElementById("editTxtTips").value = txtTips;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var txtTag = document.getElementById('txtTag').value;
      document.getElementById("editTxtTag").value = txtTag;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var txtImg = document.getElementById('txtImg').value;
      document.getElementById("editTxtImg").value = txtImg;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var txtIngredients = document.getElementById('txtIngredients').value;
      document.getElementById("editTxtIngredients").value = txtIngredients;
      // ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
      var ddlPrakriti = document.getElementById('ddlPrakriti[]').value;
      //alert(ddlPrakriti);
      document.getElementById("editDdlPrakriti[]").value = ddlPrakriti;
    }
   </script>
   
   <!-- Modal Starts -->
  
   <!-- Modal Ends -->

   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Recipe Data</h4>
      </div>
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Recipe Name</th>
                  <th>Cooking Time</th>
                  <th>Serving</th>
                  <th>Calories</th>
                  <th>Steps</th>
                  <th>Tips</th>
                  <th>Tags</th>
                  <th>Img Url</th>
                  <th>Ingredients</th>
                  <th>Prakriti</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
              @foreach($RecipeAmikus as $ra)
              <tr>
                <td>{{ $ra->id }}</td>
                <td>{{ $ra->name }}</td>
                <td>{{ $ra->cooking_time }}</td>
                <td>{{ $ra->serving }}</td>
                <td>{{ $ra->calories }}</td>
                <td>{{ $ra->steps }}</td>
                <td>{{ $ra->tips }}</td>
                <td>{{ $ra->tag }}</td>
                <td><img src="{{ $ra->image }}" class="img-responsive"></td>

                <td>{{ $ra->ingredients }}</td> 
                <td>{{ $ra->prakriti }}</td>
                <td style="display:none">{{ $ra->image }}</td>
                <td class="clickable" data-toggle="modal" data-target="#myModal">
                  <div class="container">
                    <div class="row">
                      <!-- <a href="{{ url('/service/editRecipe/'.$ra->id) }}" class="btn btn-primary a-btn-slide-text" data-toggle="tooltip" title="Click to edit this record" data-placement="top">
                          <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                      
                      </a> -->
                      <button type="button" class="btn btn-warning" data-toggle="tooltip" title="Click to edit this record" data-placement="top" onclick="showData();">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                      </button>&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="{{ url('/service/act/'.$ra->id) }}" class="btn btn-success" data-toggle="tooltip" title="Click to approve this record" data-placement="top">
                          <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                      </a>
                      <!-- <a href="#" class="btn btn-primary a-btn-slide-text">
                         <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                          <span><strong>Delete</strong></span>            
                      </a> -->
                    </div>
                  </div>
                </td>
              </tr> 
               
              @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
<!-- MODAL STARTS HERE -->
               <div class="modal fade parent" id="myModal" role="dialog">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                      <div class="container">
                        <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4>Update Recipe</h4>
                        </div>
                        <div class="panel-body">
                           <form id="editForm-template" class="form" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                              <div class="row">
                                 @if(session()->has('message'))
                                 <div class="alert alert-success" id="alert">
                                    {{ session()->get('message') }}
                                 </div>
                                 @endif
                              </div>
                              <div class="row">
                                 <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('editTxtName') ? 'has-error' : '' }}">
                                       <label for="Recipe Name" class="cols-sm-2 control-label">Recipe Name</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                             <input type="text" class="form-control" name="editTxtName" id="editTxtName"  placeholder="Enter your Name"/>
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtName') }}</span>
                                       </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('editTxtCookingTime') ? 'has-error' : '' }}">
                                       <label for="Cooking Time" class="cols-sm-2 control-label">Cooking Time</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-clock-o fa" aria-hidden="true"></i></span>
                                             <input type="text" class="form-control" name="editTxtCookingTime" id="editTxtCookingTime"  placeholder="Enter your Cooking Time" value="{{ $ra->cooking_time }}"/>
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtCookingTime') }}</span>
                                       </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('editTxtServing') ? 'has-error' : '' }}">
                                       <label for="Serving" class="cols-sm-2 control-label">Serving</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-black-tie fa" aria-hidden="true"></i></span>
                                             <input type="text" class="form-control" name="editTxtServing" id="editTxtServing"  placeholder="Enter your Serving" value="{{ $ra->serving }}" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtServing') }}</span>
                                       </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('editTxtCalories') ? 'has-error' : '' }}">
                                       <label for="Calories" class="cols-sm-2 control-label">Calories</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-bolt fa-lg" aria-hidden="true"></i></span>
                                             <input type="text" class="form-control" name="editTxtCalories" id="editTxtCalories"  placeholder="Enter your Calories" value="{{ $ra->calories }}" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtCalories') }}</span>
                                       </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('editTxtSteps') ? 'has-error' : '' }}">
                                       <label for="Steps" class="cols-sm-2 control-label">Steps</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-step-forward fa" aria-hidden="true"></i></span>
                                             <textarea class="form-control" name="editTxtSteps" id="editTxtSteps" style="height: 250px; resize: none;">{{ $ra->steps }}</textarea>
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtSteps') }}</span>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group {{ $errors->has('editTxtTips') ? 'has-error' : '' }}">
                                       <label for="Tips" class="cols-sm-2 control-label">Tips</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-info fa-lg" aria-hidden="true"></i></span>
                                             <input type="text" class="form-control" name="editTxtTips" id="editTxtTips"  placeholder="Enter your Tips" value="{{ $ra->tips }}" />
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtTips') }}</span>
                                       </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('editTxtTag') ? 'has-error' : '' }}">
                                       <label for="Tag" class="cols-sm-2 control-label">Tag</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-tags fa-lg" aria-hidden="true"></i></span>
                                             <input type="text" class="form-control" name="editTxtTag" id="editTxtTag"  placeholder="Enter your Tag" value="{{ $ra->tag }}"/>
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtTag') }}</span>
                                       </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('editTxtImg') ? 'has-error' : '' }}">
                                       <label for="Image" class="cols-sm-2 control-label">Image</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-picture-o fa-lg" aria-hidden="true"></i></span>
                                             <input type="text" class="form-control" name="editTxtImg" id="editTxtImg" value="{{ $ra->image }}" />
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtImg') }}</span>
                                       </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('editTxtIngredients') ? 'has-error' : '' }}">
                                       <label for="Ingredients" class="cols-sm-2 control-label">Ingredients</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></span>
                                             <textarea class="form-control" name="editTxtIngredients" id="editTxtIngredients" style="height: 206px; resize: none">{{ $ra->ingredients }}</textarea>
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editTxtIngredients') }}</span>
                                       </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('editDdlPrakriti') ? 'has-error' : '' }}">
                                       <label for="Prakriti" class="cols-sm-2 control-label">Prakriti</label>
                                       <div class="cols-sm-10">
                                          <div class="input-group">
                                             <span class="input-group-addon"><i class="fa fa-pagelines fa-lg" aria-hidden="true"></i></span>
                                             <select class="form-control" name="editDdlPrakriti[]" id="editDdlPrakriti[]" multiple="multiple">
                                                <option value="-" selected="selected" disabled="disabled">Select</option>
                                                <option value="Vata">Vata</option>
                                                <option value="Kapha">Kapha</option>
                                                <option value="Pitta">Pitta</option>
                                             </select>
                                          </div>
                                          <span class="text-danger">{{ $errors->first('editDdlPrakriti') }}</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <button type='submit' class='btn btn-primary' onclick="showMessage();">Submit</button>
                                 <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Submit</button> -->
                                 <button type='reset' class='btn btn-danger'>Cancel</button>
                                 <input type="hidden" name="hiddenId" id="hiddenId">
                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              </div>
                           </form>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
               </div>
               <!-- MODAL ENDS HERE -->
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>	
<script type="text/javascript">
  function showMessage(){
        
        $("#editForm-template").submit(function (e) {
                    
          event.preventDefault();
      var url = '/service/storeRecipe';
      $.ajax(
        {
           type: "POST",
           url: url,
           data: $("#editForm-template").serialize(),
           success: function(data)
           {
                var value= 'Data submitted successfully';
               $('#alert').show();
               $('#alert').empty();
                  
                  $('#alert').append("<li>"+value+"</li>");
              
                  setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        location.reload();
                     });
                }, 3000);
           },
           error : function(data) {
               var errors = data.responseJSON;

            console.log(errors);

               $('#alert').show();
                  $('#alert').empty();
                  $.each(errors, function(index, value) {
                  $('#alert').append("<li>"+value+"</li>");
              });

                  setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        location.reload();
                     });
                }, 3000);
           }
        });
      
        });
  }
</script>	
<script type="text/javascript">

$(function() {
    $('.clickable').click(function(e) {
        var id = $(this).parent().find('td:first').text();
        
        var RecipeName = $(this).parent().find("td:eq(1)").text();
        
        var CookingTime = $(this).parent().find("td:eq(2)").text();
        
        var Serving = $(this).parent().find("td:eq(3)").text();
        
        var Calories = $(this).parent().find("td:eq(4)").text();
        
        var Steps = $(this).parent().find("td:eq(5)").text();
        
        var Tips = $(this).parent().find("td:eq(6)").text();
        
        var Tags = $(this).parent().find("td:eq(7)").text();
        
        var ImgUrl = $(this).parent().find("td:eq(11)").text();
        
        var Ingredients = $(this).parent().find("td:eq(9)").text();
        
        var Prakriti = $(this).parent().find("td:eq(10)").text();
        
        document.getElementById("hiddenId").value = id;
        document.getElementById("editTxtName").value = RecipeName;
        document.getElementById("editTxtCookingTime").value = CookingTime;
        document.getElementById("editTxtServing").value = Serving;
        document.getElementById("editTxtCalories").value = Calories;
        document.getElementById("editTxtSteps").value = Steps;
        document.getElementById("editTxtTips").value = Tips;
        document.getElementById("editTxtTag").value = Tags;
        document.getElementById("editTxtIngredients").value = Ingredients;
        document.getElementById("editDdlPrakriti[]").value = Prakriti;
        document.getElementById("editTxtImg").value = ImgUrl;
    });
});
</script>
			