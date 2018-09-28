<div class="container">
    @if (Session::has('message2'))
	   <div class="alert alert-success"><h2>{{ Session::get('message2') }}</h2></div>
	@endif
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Add Herb Template</h4>
      </div>
      <div class="panel-body">
         <form id="form-template" method="POST" class="form">
            <div class="form-group">
               <select name="herb" id="herb">
                  <option value="">Select Herb</option>
                  @foreach($herbs AS $herb)
                  <option value="{{$herb->id}}">{{$herb->name}}</option>
                  @endforeach
               </select>
            </div>
            <div class="form-group">
               <input type="text" name="quantity" id="quantity" size="3" placeholder="Quantity">
               <select name="unit" id="unit">
                  <option value="">Select Unit</option>
                  @foreach($units AS $unit)
                  <option value="{{$unit->id}}">{{$unit->name}}</option>
                  @endforeach
               </select>
            </div>
            <div class="form-group">
               <textarea name="remark" cols="30"></textarea>
            </div>
            <div class="form-group">
               <select name="mealtimes[]" id="mealtimes" multiple size='7'>
                  @foreach($mealtimes AS $mealtime)
                  <option value="{{$mealtime->id}}">{{$mealtime->name}}</option>
                  @endforeach
               </select>
            </div>
            <div class="form-group">
               <button type='submit' class='btn btn-primary'>Submit</button>
               <button type='reset' class='btn btn-danger'>Cancel</button>
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
         </form>
      </div>
   </div>
    
   <div class="panel panel-default">
      <div class="panel-heading">
         <h4>Herb Templates</h4>
      </div>
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Herb</th>
                  <th>Quantity</th>
                  <th>Unit</th>
                  <th>Remark</th>
                  <th>Mealtimes</th>
                  <th></th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach($templates as $template)

               <tr>
                  <td>{{$i++}}</td>
                  <td>{{$template->herb->name or ""}}</td>
                  <td>{{$template->quantity or ""}}</td>
                  <td>{{$template->unit->short_name or ""}}</td>
                  <td>{{$template->remark or ""}}</td>
                  <td width="15%">
                     @foreach($template->mealtimes AS $mealtime)	
                     <i class="fa fa-check-square"></i> {{$mealtime->mealtime->name or ""}}<br>
                     @endforeach	
                  </td>
                  <td><i class="fa fa-info-circle"></i></td>
                  <td><button class="btn-success btn-xs" data-toggle="modal" data-target="#model-{{ $template->id }}">
                     <i class="fa fa-edit"></i>
                     </button>
                  </td>
               </tr>
               <!-- Modal -->
               <div class="modal" id="model-{{ $template->id }}" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                           <h4 class="modal-title" id="myModalLabel">Update the information</h4>
                        </div>
                        <div class="modal-body">
                           <form method="post" class="form" id="frmUpdate" enctype="multipart/form-data" action="{{url('/herb/template/update') }}">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <select name="herbEdit" id="herbEdit" class="form-control">
                                          <option value="" disabled="disabled" selected="selected">Select Herb</option>
											@foreach($herbs AS $herb)
											    <?php 
											    $dd = $template->herb->id;
											    $cc = $herb->id; ?>
											        @if($dd == $cc )
											        <option selected="selected" value="{{$herb->id}}">{{$herb->name}}</option>
											        @else
											        <option value="{{$herb->id}}">{{$herb->name}}</option>
											        @endif
										    @endforeach                                          
                                       </select>
                                       {{ $errors->first('herbEdit', '<span class=error>:message</span>') }}
                                    </div>
                                    <div class="form-group">
                                       <input type="text" name="quantityEdit" id="quantityEdit" size="3" placeholder="Quantity" class="form-control" value="{{$template->quantity}}">
                                       {{ $errors->first('quantityEdit', '<span class=error>:message</span>') }}
                                    </div>
                                    <div class="form-group">
                                       <select name="unitEdit" id="unitEdit" class="form-control">
                                          <option value="" disabled="disabled" selected="selected">Select Herb</option>
											@foreach($units AS $unit)
											    <?php 
											    $dd = $template->unit->id;
											    $cc = $unit->id; ?>
											        @if($dd == $cc )
											        <option selected="selected" value="{{$unit->id}}">{{$unit->name}}</option>
											        @else
											        <option value="{{$unit->id}}">{{$unit->name}}</option>
											        @endif
										    @endforeach
                                       </select>
                                       {{ $errors->first('unitEdit', '<span class=error>:message</span>') }}
                                    </div>
                                    <div class="form-group">
                                       <input type="submit" name="btnSubmit" class="btnContact" value="Update" />
                                       <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                       <input type="hidden" name="id" value="{{ $template->id }}">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <select name="mealtimesEdit[]" id="mealtimesEdit" multiple size='7' class="form-control">
						                  @foreach($mealtimes AS $mealtime)
						                  <option value="{{$mealtime->id}}">{{$mealtime->name}}</option>
						                  @endforeach
						               </select>
						               {{ $errors->first('mealtimesEdit', '<span class=error>:message</span>') }}
                                    </div>
                                    <div class="form-group">
                                       <textarea name="remarkEdit" class="form-control" placeholder="Your Remark *" style="width: 100%; height: 75px;">{{$template->remark or ""}}</textarea>
                                       {{ $errors->first('remarkEdit', '<span class=error>:message</span>') }}
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                     </div>
                     <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
               </div>
               <!-- /.modal -->
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>

<script type="text/javascript">
   $("#form-template").submit(function(event){
   	event.preventDefault();
   	var url = '/herb/template/add';
   	$.ajax(
          {
             type: "POST",
             url: url,
             data: $("#form-template").serialize(),
             success: function(data)
             {
                 $('#alert').show();
                 $('#alert').empty().append(data);
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
                          //location.reload();
                       });
                  }, 3000);
             }
          });
   });
</script>