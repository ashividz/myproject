  <!-- Panels 1 -->
  <div class="panel panel-default">
    <div class="panel-heading"> 
      <div>
        @include('partials/daterange')
      </div>
    </div>
    <div class="panel-body no-padding">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#country" aria-controls="country" role="tab" data-toggle="tab">Country</a></li>
        <li role="presentation"><a href="#region" aria-controls="region" role="tab" data-toggle="tab">Region</a></li>
        <li role="presentation"><a href="#city" aria-controls="city" role="tab" data-toggle="tab">City</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="country">       
          <div class="container">

            <a name="download" id="downloadCountry" class="btn btn-primary pull-right">Download Country CSV</a>       
      		  <table id="table-country" class="table table-striped table-bordered table-hover">
      		  <thead>
      			<tr>
      				<th>Country</th>
      				<th>Patients</th>
      			</tr>
      		  </thead>
      		  <tbody>

        @foreach($countries AS $country)
              <tr>
                <td>{{$country->country_name}}</td>
                <td>{{$country->count}}</td>
              </tr>
        @endforeach
  		      </tbody>
  	       </table>
          </div>
        </div> 
        <!-- Regions Report -->
        <div role="tabpanel" class="tab-pane fade" id="region">        
          <div class="container">
            <a name="download" id="downloadState" class="btn btn-primary pull-right" style="margin:10px" download="summary.csv">Download Region</a>
            <table id="table-region" class="table table-striped table-bordered table-hover">
              <thead>
              <tr>
                <th>Region</th>
                <th>Patients</th>
              </tr>
              </thead>
              <tbody>

          @foreach($regions AS $region)
                <tr>
                  <td>{{$region->region_name}}</td>
                  <td>{{$region->count}}</td>
                </tr>
          @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!-- Cities Report -->
        <div role="tabpanel" class="tab-pane fade" id="city">        
          <div class="container">
            <a name="download" id="downloadCity" class="btn btn-primary pull-right" style="margin:10px" download="summary.csv">Download City</a>
            <table id="table-city" class="table table-striped table-bordered table-hover">
              <thead>
              <tr>
                <th>City</th>
                <th>Patients</th>
              </tr>
              </thead>
              <tbody>

          @foreach($cities AS $city)
                <tr>
                  <td>{{$city->city}}</td>
                  <td>{{$city->count}}</td>
                </tr>
          @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
$(document).ready(function() 
{
  $('#date').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    format: 'MMM D, YYYY' 
  }); 

  $('#date').on('apply.daterangepicker', function(ev, picker) 
  {    
      $('#formx').submit();
  });

  $( "#downloadCountry" ).bind( "click", function() 
  {
    var csv_value = $('#table-country').table2CSV({
                delivery: 'value'
            });
    downloadFile('countries.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    $("#csv_text").val(csv_value);  
  });

  $( "#downloadRegion" ).bind( "click", function() 
  {
    var csv_value = $('#table-region').table2CSV({
                delivery: 'value'
            });
    downloadFile('regions.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    $("#csv_text").val(csv_value);  
  });

  $( "#downloadCity" ).bind( "click", function() 
  {
    var csv_value = $('#table-city').table2CSV({
                delivery: 'value'
            });
    downloadFile('cities.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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
});
</script>