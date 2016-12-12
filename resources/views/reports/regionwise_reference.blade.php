
<div class="container">  
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-left">
        @include('partials/daterange')
      </div>
      <h4>Report- Referee </h4>
    </div>
    <div class="panel-body">
    <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Delhi NCR</a></li>
        <li role="presentation"><a href="#summary" aria-controls="summary" role="tab" data-toggle="tab">Pan India</a></li>
        <li role="presentation"><a href="#referrer" aria-controls="referrer" role="tab" data-toggle="tab">International</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
          <br>
          <table id="example" class="table table-bordered">
            <thead>
                <tr>
                  <th>Name</th>
                  <th>Country</th>
                  <th>References</th>
                  <th>Conversions</th>
                  <th>Percentage</th>
                </tr>
              </thead>
              <tbody>

            @foreach($delhincr AS $referrer)  
                <tr>
                  <td><a href="/lead/{{$referrer->id}}/viewReferences" target="_blank">{{$referrer->name}}</a></td>
                  <td>{{$referrer->country or " "}}</td>
                  <td>{{$referrer->leads}}</td>
                  <td>{{$referrer->conversions}}</td>

                @if($referrer->conversions <> 0)                    
                  <td>{{round($referrer->conversions/$referrer->leads*100, 2)}} %</td>
                @else
                  <td></td>
                @endif
                  
                </tr>

            @endforeach

              </tbody>
          </table>
        </div>

        <!-- Nutritionist Summary Report -->
        <div role="tabpanel" class="tab-pane fade" id="summary"> 
              
          <div class="container">
            <p><br>
            <table id="y" class="table table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Country</th>
                  <th>City</th>
                  <th>References</th>
                  <th>Conversions</th>
                  <th>Percentage</th>
                </tr>
              </thead>
              <tbody>

            @foreach($panindia AS $referrer)  
                <tr>
                  <td><a href="/lead/{{$referrer->id}}/viewReferences" target="_blank">{{$referrer->name}}</a></td>
                  <td>{{$referrer->country or " "}}</td>
                  <td>{{$referrer->city or " "}}</td>
                  <td>{{$referrer->leads}}</td>
                  <td>{{$referrer->conversions}}</td>

                @if($referrer->conversions <> 0)                    
                  <td>{{round($referrer->conversions/$referrer->leads*100, 2)}} %</td>
                @else
                  <td></td>
                @endif
                  
                </tr>

            @endforeach

              </tbody>
            </table>       
          </div>
        </div>

        <!-- Referrer Summary Report -->
        <div role="tabpanel" class="tab-pane fade" id="referrer">     
          <div class="container">
            <p><br>
              <table id="x" class="table table-bordered" width="100%">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Country</th>
                  <th>References</th>
                  <th>Conversions</th>
                  <th>Percentage</th>
                </tr>
              </thead>
              <tbody>

            @foreach($international AS $referrer)  
                <tr>
                  <td><a href="/lead/{{$referrer->id}}/viewReferences" target="_blank">{{$referrer->name}}</a></td>
                  <td>{{$referrer->country or " "}}</td>
                  <td>{{$referrer->leads}}</td>
                  <td>{{$referrer->conversions}}</td>

                @if($referrer->conversions <> 0)                    
                  <td>{{round($referrer->conversions/$referrer->leads*100, 2)}} %</td>
                @else
                  <td></td>
                @endif
                  
                </tr>

            @endforeach

              </tbody>
            </table>
          </div>
        </div>

<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable({

      "iDisplayLength": 100
    });
} );
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#x').DataTable({

      "iDisplayLength": 100
    });
} );
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#y').DataTable({

      "iDisplayLength": 100
    });
} );
</script>
<style type="text/css">
  h4 {
    margin-left: 250px; 
  }
</style>