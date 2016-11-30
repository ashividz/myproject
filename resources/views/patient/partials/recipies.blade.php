@extends('patient.index')

@section('top')
<style type="text/css">
	.tags li {
		list-style: none;
		display: inline-block;
	}
	.tags li .tag {
		text-transform: uppercase;
		font-size: 14px;
		margin-left: 20px; 
		background-color: rgba(111, 128, 168, 1);
    	box-shadow: 0 1px 0 rgba(255, 255, 255, 0.15), inset 0 1px 2px rgba(0, 0, 0, 0.5);
    	padding: 2px 20px;	
	}
    .modal.fade.in {
    top: 10%;
    display: block !important;
}
.fade.in {
    opacity: 1;
}
    .modal.fade {
    top: -25%;
    transition: opacity 0.3s linear 0s, top 0.3s ease-out 0s;
}

.modal {
    background-clip: padding-box;
    background-color: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.3);
    border-radius: 6px;
    box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
    left: 50%;
    margin-left: -280px;
    outline: medium none;
    position: fixed;
    top: 10%;
    width: 560px;
    z-index: 1050;
}
.ingreinp
{

  width: 100px;
}
.fa-times-circle::before
{
font-size: 20px;
}
.remove-ingred, .remove-ingred:focus
{

  color: #669999;
}
.remove-ingred:hover
{

  color: #ae0000;
}
table.dataTable tr.disabled td, table.dataTable tr.disabled td.sorting_1
{
background: #b3b3b3;
opacity: 0.7;
}
.item_remv
{
text-align: center;

}
.preview_table tr td
{

  font-size: 14px;
}
p.sent_recip
{
  font-size: 12px;
}
</style>
<div class="panel panel-default">
	
	<div class="panel-body">
		<form id="form-upload" enctype="multipart/form-data" method="post" action="">
			<table class="table table-bordered" >
				<tbody>
					<tr>
						<td>
							<div class="form-group col-md-12">
                            <div class="input-group">
                                <label style='background: #cceeff;font-weight: bold;' class="input-group-addon">Select Recipe</label>
                                <div class="dropdown">
                                    <select id="recipe" name="recipe" class="form-control disposition" size=15 required>
                                        @foreach($recipies AS $recipe) 

                                              @if($recipe->recipe_code == $recipe_code)
                                                  <option value="{{$recipe->recipe_code}}" selected>{{$recipe->recipe_name}}</option>
                                              @else
                                                  <option value="{{$recipe->recipe_code}}">{{$recipe->recipe_name}}</option>
                                              @endif

                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                        </div>
                        </td>
                        <td style='vertical-align: middle;padding-top: 0px'>
                        @if(sizeof($sent_recipies))
                          <div style='min-height: 220px;overflow: auto'>
                          <h4 style='margin: 0px;background: #e6e6e6;padding: 10px;color: #555555;border: 1px solid #cccccc'> SENT RECIPE HISTORY</h4>
                          <div style='max-height: 180px;overflow: auto;padding: 10px'>
                            @foreach($sent_recipies AS $sent_recipe)
                            <p class='sent_recip'><a class='popup_gallery' href='/patient/{{$patient->id}}/sentRecipe/{{$sent_recipe->id}}'>{{$sent_recipe->recipe_name}}</a> &nbsp; {{date('jS M, Y', strtotime($sent_recipe->created_at))}}</p>
                            @endforeach 
                            </div>
                          </div>
                          @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button id="get_ingredients" type="submit" name="get_ingredients" class="btn btn-info"> Get Ingredients</button>
                    
                        </td>
                        </tr>
				</tbody>
			</table>
		</form>
	</div>
</div>

@endsection

@section('main')



	
	
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 style='text-align: center' class="panel-title">{{$recipe_name}} <span style='color: #cce6ff'>Ingredients</span></h3>
	</div>
	<div class="panel-body">
	<div style='display: inline-block !important;margin-left: 10%'>
		<form id="form-diet" action="/patient/{{$patient->id}}/recipe/send" method="post" class="form-inline" >
   <table><tr> <td style='width: 600px'>
    <input type='hidden' name='recipe_name' value='{{$recipe_name}}' />
     <input type='hidden' name='recipe_code' value=' {{$recipe_selected->recipe_code}}' />
     <input type='hidden' name='patient_id' value=' {{$patient->id}}' />
     <input type='hidden' name='lead_id' value=' {{$patient->lead_id}}' />
			<table class="table table-bordered blocked" id='table'>
				<thead>
					<tr>
											
						<th>Item</th>
						<th>Quantity</th><!--
						<td>Mid Morning</td>-->
						<th>Unit</th>
            <th>Detach</th>
						
					</tr>
				</thead>
				<tbody>
					
			@foreach($ingredients as $ingredient)
					<tr class='row-{{$ingredient->ingr_item_id}}'>
						
            <td class='ingre_name'>

            {{$ingredient->ingredient->name}}</td>
            
            <td><input type='text' name='quantity[]' class='inp-{{$ingredient->ingr_item_id}} ingreinp ingre_qnty' value='{{$ingredient->ingr_qty}}' /></td>
            <td><!--<input type='text' name='unit[]' class='inp-{{$ingredient->ingr_item_id}} ingreinp' value='{{$ingredient->ingr_unit}}' />-->
               <select  class='inp-{{$ingredient->ingr_item_id}} ingre_unit' name="unit[]" >
                                        
                                            @foreach($units AS $unit) 

                                                @if(trim(strtolower($unit->ingr_unit)) == trim(strtolower($ingredient->ingr_unit)))
                                                    <option value="{{$unit->ingr_unit}}" selected>{{$unit->ingr_unit}}</option>
                                                @else
                                                    <option value="{{$unit->ingr_unit}}">{{$unit->ingr_unit}}</option>
                                                @endif

                                          @endforeach 
              </select>
            </td>
  					<td  class='item_remv'>
            <input type='hidden' name='item_name[]' class='inp-{{$ingredient->ingr_item_id}} ingreinp' value='{{trim($ingredient->ingredient->name)}}' />
               <input type='hidden' name='item_id[]' class='inp-{{$ingredient->ingr_item_id}} ingreinp' value='{{$ingredient->ingr_item_id}}' />
               <a  href ='{{$ingredient->ingr_item_id}}' class='remove-ingred remove-{{$ingredient->ingr_item_id}}'><i class="fa fa-times-circle"></i></a>
            </td>
          </tr>



          
			@endforeach

				</tbody>
			</table>
    
<br>
    <table>
      <tr>
        <td>
            <div class="form-group col-md-12" style='padding-left: 0px'>
                <div class="input-group">
                    <label style='background: #cceeff;font-weight: bold;' class="input-group-addon"><button  id="add_to_recipe" type='submit' class='btn btn-info active' > Add to Recipe</button></label>
                    <div class="dropdown">
                        <select id="add_item" name="add_item" style='width: 200px' class="form-control disposition" size=15 >
                           @foreach($ingredient_items AS $ingredient_item) 
                              <option value="{{$ingredient_item->ID}}">{{$ingredient_item->name}}</option>
                           @endforeach 
                        </select>
                    </div>
                </div>
             </div>
      </td>
      <td>
          <select  id="add_unit" class=''  name="add_unit" >
            @foreach($units AS $unit)
              @if(!empty($unit->ingr_unit))
                <option value="{{$unit->ingr_unit}}">{{$unit->ingr_unit}}</option>
              @endif  
            @endforeach 
          </select><br><br>
          <input type='text' placeholder="Quantity" name='add_quantity' id='add_quantity' class='ingreinp' val='' />
      </td>
      
    </tr>
  </table>
  <p id='sbmt_cntnr' style='margin-top: 10px;'>

         <a id="preview_recipe" href='' name="preview_recipe" class="btn btn-warning"> Preview</a>
        <button id="send_recipe" type="submit" name="send_recipe" class="btn btn-danger"> Send Recipe</button>
      </p>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div id='preview_box'></div>
</td>         
<td style='vertical-align: top'>
  <table class="table table-bordered blocked" id='table'>
      @if(!empty($recipe->recipe_img_url) && !is_null($recipe_selected->recipe_img_url))
      <tr><td colspan='2'><img width='100%' src='{{$recipe_selected->recipe_img_url}}' /></td></tr>
      @endif
      <tr><td><label>Servings</label></td><td><input type='text' name='servings' value='{{$recipe_selected->recipe_servings}}' /></td></tr>
      <tr><td><label>Calories</label></td><td><input type='text' name='calorie' value='{{$recipe_selected->recipe_calories}}' /></td></tr>
      <tr><td><label>Cooking Duration</label></td><td><input type='text' name='cooking_duration' value='{{$recipe_selected->recipe_cooking_duration}}' /></td></tr>
      <tr><td><label>Preparation Duration</label></td><td><input type='text' name='preparation_duration' value='{{$recipe_selected->recipe_preparation_duration}}' /></td></tr>
      <tr><td><label>Remarks</label></td><td><input type='text' name='remarks' value='{{$recipe_selected->recipe_remarks}}' /></td></tr>
      <tr><td colspan='2'><label>Notes:</label><textarea name='notes' style='width: 100%;height: 200px'>{{$recipe_selected->recipe_notes}}</textarea></td></tr>
  </table>
</td></tr></table>

    </form>



		</div>
	</div>
</div>





<script type="text/javascript" src="/js/form-ajax.js"></script>
 <script src="/js/jquery.media.js'"></script> 
<script type="text/javascript">
$(document).ready(function() 
{

 $('#table').dataTable({
      "bPaginate": false,
      "bFilter": false, 
      "bInfo": false, 
    }); 
 


 $('#preview_recipe').click(function(e){
    e.preventDefault();

    var ingr_names = $('.ingre_name').map( function () {
    if(!$(this).parent().hasClass('disabled'))
      return $( this ).text();
    }).get();

    var ingre_qnty = $('.ingre_qnty').map( function () {
    if(!$(this).parent().parent().hasClass('disabled'))
    return $( this ).val();
    }).get();

    var ingre_unit = $('.ingre_unit').map( function () {
    if(!$(this).parent().parent().hasClass('disabled'))
    return $( this ).val();
    }).get();


    var recipe_name = $("input[name='recipe_name']").val();
    var servings = $("input[name='servings']").val();
    var calorie = $("input[name='calorie']").val();
    var cooking_duration = $("input[name='cooking_duration']").val();
    var preparation_duration = $("input[name='preparation_duration']").val();
    var remarks = $("input[name='remarks']").val();
    var notes = $("textarea[name='notes']").val().replace(/\r\n|\r|\n/g,"<br />");

     recipe_body = "<table class='preview_table' border='0' width='100%' style='margin: 0px;border: 1px solid #d9d9d9;font-family: arial' cellspacing='0' cellpadding='0' ><tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h3 style='margin: 0px;color: #444444'>"+recipe_name+"</h3></td></tr>";
     recipe_body +=   "<tr><td colspan='3' style='padding: 0px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'><table cellspacing='0' cellpadding='0' border='0'><tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Servings</td><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+servings+"</td></tr><tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Calorie</td><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+calorie+"</td></tr><tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Cooking Duration</td><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+cooking_duration+"</td></tr><tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Preparation Duration</td><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+preparation_duration+"</td></tr><tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Remarks</td><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+remarks+"</td></tr><tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Notes</td><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+notes+"</td></tr></table></td></tr>";


        


        recipe_body += "<tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h4 style='margin: 0px;color: #444444'>INGREDIENTS</h4></td></tr>";
        for(var i = 0;i < ingr_names.length; i++)
        {
          recipe_body +=   "<tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+ingr_names[i]+"</td><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+ingre_qnty[i]+"</td><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>"+ingre_unit[i]+"</td></tr>";
        }
        recipe_body += "</table>";
$('#preview_box').html(recipe_body);
  });

 $('#add_to_recipe').click(function(e){
    e.preventDefault();

   var item_id =  $('#add_item').val();
   var item_name = $.trim($("#add_item option:selected").text());

   var quantity =  $('#add_quantity').val();
   var quanty_class= 'inp-'+item_id+' ingreinp ingre_qnty';
   var remov_class = 'remove-ingred remove-'+item_id;

   var row_class = 'row-'+item_id;
   if($('#table tr:last').hasClass('even'))
   row_class += ' odd';
  else
    row_class += ' even';
   var $selct = $('#add_unit').clone();

   $selct.attr('name','unit[]');
   $selct.attr('class','ingre_unit inp-'+item_id);
   $selct.val($('#add_unit').val());
   
   
   var txt1 = $( "p" );
   txt1.add("<input>");
   /*$( "<a/>", {
    html: "This is a <strong>new</strong> link",
    "class": "new",
    href: "foo.html"
});

 var div = $('<div/>').text("Sup, y'all?").appendTo(document.body);*/
var new_item = $('<tr/>', {'class': row_class})
  .append($('<td/>', {'class': 'sorting_1 item_name ingre_name'}))
  .append($('<td/>', {'class': 'item_quanty'}))
  .append($('<td/>', {'class': 'item_unt'}))
  .append($('<td/>', {'class': 'item_remv'}));
$(new_item).find('.item_name').html(item_name);
$(new_item).find('.item_quanty').append($('<input/>', {'type': 'text', 'class': quanty_class, 'value': quantity,'name': 'quantity[]'}));
$(new_item).find('.item_unt').append($selct);
$(new_item).find('.item_remv').append($('<input/>', {'type': 'hidden', 'class': quanty_class, 'value': item_name,'name': 'item_name[]'}));
$(new_item).find('.item_remv').append($('<input/>', {'type': 'hidden', 'class': quanty_class, 'value': item_id,'name': 'item_id[]'}));
$(new_item).find('.item_remv').append($('<a/>', {'href': item_id, 'class': remov_class, 'html': "<i class='fa fa-times-circle'></i>"}));
$('#table').append(new_item);

});

$('body').on('click', 'a.remove-ingred', function(e) {
    e.preventDefault();
    var ingr_id = $(this).attr('href');
    if($('.row-'+ingr_id).hasClass('disabled'))
      {
        $('.row-'+ingr_id).removeClass('disabled');
        $('.inp-'+ingr_id).prop('disabled', false);
      }
    else
      {

        $('.row-'+ingr_id).addClass('disabled');
        $('.inp-'+ingr_id).prop('disabled', true);
      }
});


  $('#date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'D-M-YYYY' 
  }); 
 });
</script>

<style type="text/css">
	.progress td {
		padding: 8px 0 !important;
	}
	div .checked {
		background-color:#266c8e; 
		color:#fff!important; 
	}
</style>
<script type="text/javascript">
$(document).ready(function() 
{

    $('#bt_report').bind('change', function() {
            var fileExtension = ['pdf','mkv', 'mp4', 'flv'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("Only PDF Files Allowed");
                $('#form-upload .btn').prop('disabled', true);
                return false;
          }

        /*if(this.files[0].size > 1024 * 1024 * 2) {
                alert('Report size is: ' + (this.files[0].size/1024/1024).toFixed(2) + "MB, shouldn't be more that 2 MB.");
                $('#form-upload .btn').prop('disabled', true);
          }
        else
                $('#form-upload .btn').prop('disabled', false);*/
    });

    $('input[name="report_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD'
            },
        endDate: '2016-12-31'
    });

    $('input[name="report_date"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });
    
	$('.fa-copy').on('click', function(){
		var i = $(this).prev();
		var diet = this.title;		

		$('#'+this.title).val(i.text());
		$('.'+this.title).removeClass('checked');
		i.addClass('checked');
		//alert('Copied : ' +i.text());
	});

	//autocomplete
	$( "#form-diet .diet-area" ).autocomplete({
    	source: function( request, response ) {
    		//alert($(this.element).prop("id"));
        	var id = $(this.element).prop("id");
        	$.ajax({
				type: "POST",
				url: "/diet/autocomplete",
				data:{'term' : request.term, id : id, _token : '{{ csrf_token() }}'},
				dataType: "json",
				beforeSend: function(){
					//$("#"+id+"-list").css("background","#f2f2f2");
				},
				success: function(data){
					//console.log(data);
					$("#"+id+"-list").empty();
					$("#"+id+"-list").show();
					response( $.map( data, function(field) {
                    // your operation on data
                
						console.log(field.id);
						$("#"+id+"-list").append("<li class='diet-data' title='"+id+"'>"+ field.diet +"</li>");
					}));
				}
			});
		},
		minLength :4
	});

	$('body').on('click', '.diet-data', function(){
		$("#"+this.title).val($(this).text());
		$("#"+this.title+"-list").hide();
	});

	//Hide diet-list when not in focus
	$("body").click
	(
	  function(e)
	  {
	    if(e.target.className !== "diet-list")
	    {
	      $(".diet-list").hide();
	    }
	  }
	);
});
</script>
<style type="text/css">
	textarea {
		font-size: 12px;
	}
	.diet-list {		
		position: absolute;
		line-height: 20px;
		background-color: #fff4c5;
		border: 1px solid #e4c94b;
	}
	li.diet-data {
		list-style: none;
		padding: 5px;
		cursor: pointer;
		z-index: 9999;
		min-width: 500px;
		font-size: 12px;
		margin: 5px; 
		background-color: #fff4c5;
	}
	li.diet-data:hover {
		background-color: #F9DD68;
	}
	.ui-helper-hidden-accessible {
		display: none !important;
	}
</style>

<link href="/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="/js/jquery-ui.min.js"></script>

<link href="/css/popup.css" media="all" type="text/css" rel="stylesheet">
<script src="/js/jquery.popup.js"></script>
<script>


    /*---------------------
      SETTINGS
    */
    var gallerySettings = {
    	
    	height: 500,
        markup    : '' +
          '<div class="popup">' +
            '<div class="popup_wrap">' +
              '<div class="popup_content"/>' +
            '</div>' +
            '<a href="#next" class="ignore-hash">Next →</a>' +
            '<a href="#prev" class="ignore-hash">← Previous</a>' +
          '</div>',
        // This is a custom variable
        gallery : '.popup_gallery',
        replaced : function($popup, $back){
          var plugin = this,
              $wrap = $('.popup_wrap', $popup);
          // Animate the popup to new size
          $wrap.animate({
            width : $wrap.parent().outerWidth(true),
            height : $wrap.children().children().outerHeight(true)
          }, {
            duration : 500,
            easing : 'easeOutBack',
            step : function(){
              // Need to center the poup on each step
              $popup
                .css({
                  top : plugin.getCenter().top,
                  left : plugin.getCenter().left
                });
            },
            complete : function(){
              // Fade in!
              $wrap
                .children()
                .animate({opacity : 1}, plugin.o.speed, function(){
                  plugin.center();
                  plugin.o.afterOpen.call(plugin);
                });
            }
          });
        },
        show    : function($popup, $back){
          var plugin = this,
            $wrap = $('.popup_wrap', $popup);
          // Center the plugin
          plugin.center();
          // Default fade in
          $popup
            .animate({opacity : 1}, plugin.o.speed, function(){
              plugin.o.afterOpen.call(plugin);
            });
          // Set the inline styles as we animate later
           $popup.css({
            width   :'500px'
          });
          $wrap.css({
            width   :'100%',
            height   : $wrap.outerHeight(true)
          });
        },
        afterClose    : function(){
          this.currentIndex = undefined;
        }
      };
    $(function(){
      /*---------------------
        POPUP
      */
      $('.popup_gallery').popup(gallerySettings);
      /*---------------------
        NEXT & PREVIOUS LINKS
      */
      $(document).on('click', '[href="#next"], [href="#prev"]', function(e){
        e.preventDefault();
        var $current = $('.popup_active'),
            popup = $current.data('popup'),
            $items = $(popup.o.gallery);
        // If this is the first time
        // and we don't have a currentIndex set
        if( popup.currentIndex === undefined ){
          popup.currentIndex = $items.index($current);
        }
        // Fade the current item out
        $('.'+popup.o.contentClass)
          .animate({opacity : 0}, 'fast', function(){
            // Get the next index
            var newIndex = $(e.target).attr('href') === '#next'
              ? popup.currentIndex + 1
              : popup.currentIndex - 1;
            // Make sure the index is valid
            if( newIndex > $items.length -1 ){
              popup.currentIndex = 0;
            }else if( newIndex < 0 ){
              popup.currentIndex = $items.length - 1;
            }else{
              popup.currentIndex = newIndex;
            }
            // Get the new current link
            $current = $($items[popup.currentIndex]);
            // Load the content
            popup.open($current.attr('href'), undefined, $current[0]);
          });
      });
    });


</script>

@endsection
