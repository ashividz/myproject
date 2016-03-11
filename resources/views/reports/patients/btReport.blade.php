<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
        @include('partials/daterange')
      <div class="pull-right" style="">
        <a name="download" id="downloadCSV" class="btn btn-primary" download="filename.csv">Download Csv</a>   
        <input type="hidden" name="csv_text" id="csv_text"> 
        <button type="button" id="edit" class="btn btn-success">Edit</button>        
      </div>
            <h4>Patient BT Report</h4>           
        </div>
        <div class="panel-body">
            <div class="container1">
                <form id="form" method="post">
                    <table id="table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Entry Date</th>
                                <th>1st Dom</th>
                                <th>%</th>
                                <th>2nd Dom</th>
                                <th>%</th>
                                <th>3rd Dom</th>
                                <th>%</th>
                                <th>Blood Group</th>
                                <th>RH</th>
                                <th>Duration</th>
                                <th>Initial</th>
                                <th>Final</th>
                                <th>Herbs Prescribed</th>
                            </tr>
                        </thead>
                        <tbody>




                    

                        @foreach ($patients as $patient)
                            <tr>
                                <td><a href="/lead/{{$patient->lead_id}}/viewDetails" target="_blank">{{ $patient->lead->name or "No Name" }}</a>
                                    <div class="pull-right">
                                        
                                       {{$patient->name}}
                                    </div>
                               </td>
                               <td>
                                {{ $patient->lead->gender or " " }} 
                               </td>
                               <td>
                                    <?php
                                    if(isset($patient->lead->dob))
                                    echo date_diff(date_create($patient->lead->dob), date_create('today'))->y;
                                    
                                    ?>
                               </td>
                              <td>
                              {{date('jS M, Y', strtotime($patient->entry_date))}}
                              
                              </td>
                               <?php
                              
                                
                                  $patient_prakriti = \App\Models\PatientPrakriti::prakriti($patient->id);
                                
                               
                               ?><td>
                                   {{$patient_prakriti->first_dominant_name}}
                                   </td>
                                   <td>
                                   {{round($patient_prakriti->first_dominant_percentage, 2)}}%
                               </td>
                               <td>
                                   {{$patient_prakriti->second_dominant_name}}
                                   </td>
                                   <td>
                                   {{round($patient_prakriti->second_dominant_percentage, 2)}}%
                               </td>
                               <td>
                                   {{$patient_prakriti->recessive_name}}
                                   </td>
                                   <td>
                                   {{round($patient_prakriti->recessive_percentage, 2)}}%
                               </td>
                                    
                                   
                              
                                
                                <td>{{($patient->blood_type)?$patient->blood_type->name:''}}</td>
                                <td>{{($patient->rh_factor)?$patient->rh_factor->code:''}}</td>
                                <?php   
                                    if(isset($patient->fee)) {
                                        $totalDays = floor((strtotime($patient->fee->end_date) - strtotime($patient->fee->start_date))/(60*60*24));
                                       ?>
                                      <td>
                                   {{$totalDays}} 
                                </td> 
                                   <?php   } ?>
                                        
                                <td>
                                 @if($patient->weight)
                                 {{round($patient->weights->first()->weight,2)}}
                                  @endif
                                 </td>
                                 <td>
                                   @if($patient->weight)
                                   {{round($patient->weights->last()->weight,2)}}
                                   @endif
                                   </td>
                                
                                <td>
                                    @foreach ($patient->herbs as $herb)
                                        {{$herb->herb->name}}, 
                                    @endforeach
                                </td>

                              
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#table').dataTable({
      "iDisplayLength": 100,
      "aaSorting": [[ 1, "desc" ]]
    }); 

  //Initialize Editable Plugin on Paginate

  $( "#edit" ).on( "click", function() {
 
    editableInit();
   
  });

  $( "#downloadCSV" ).bind( "click", function() 
  {
    var csv_value = $('#table').table2CSV({
                delivery: 'value'
            });
    downloadFile('patient_nutritionists.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    $("#csv_text").val(csv_value);  
  });

  function downloadFile(fileName, urlData){
    var aLink = document.createElement('a');
    var evt = document.createEvent("HTMLEvents");
    evt.initEvent("click");
    aLink.download = fileName;
    aLink.href = urlData ;
    aLink.dispatchEvent(evt);
  }

  function editableInit() {
    $(".editable_primary").editable("/service/saveNutritionist", { 
      loadurl   : "/api/getNutritionists",
      type      : "select",
      submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      placeholder: '<span class="placeholder">(Edit)</span>',
    });

    $(".editable_secondary").editable("/service/saveNutritionist?secondary=1", { 
      loadurl   : "/api/getNutritionists",
      type      : "select",
      submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      placeholder: '<span class="placeholder">(Edit)</span>',
    });

    $(".editable_doctor").editable("/service/saveDoctor", { 
      loadurl   : "/api/getUsers?role=doctor",
      type      : "select",
      submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      placeholder: '<span class="placeholder">(Edit)</span>',
    });
  }

  

});
</script>

<link href="/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="/js/jquery-ui.min.js"></script>

<link href="/css/popup.css" media="all" type="text/css" rel="stylesheet">
<script src="/js/jquery.popup.js"></script>
<script>


    /*---------------------
      SETTINGS
    */
    var gallerySettings = {
        
        height: 320,
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