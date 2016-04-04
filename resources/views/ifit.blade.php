<!DOCTYPE html>
<html lang="en">
<head>
  <title>iFitter</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>iFitter:{{$email}}</h2>

   <table class="table table-striped table-bordered">
                        
    <thead>
        <tr>
            <th>__type</th>
            <th>ReadingId</th>
            <th>RecordedForDate</th>
            <th>BodyFat</th>
            <th>BodyMassIndex</th>
            <th>BoneWeight</th>
            <th>Hydration</th>
            <th>MuscleMass</th>
            <th>Weight</th>
        </tr>
    </thead>
    <tbody>
        @foreach($response->Data as $record)
        <tr>
            <td>{{$record->__type}}</td>
            <td>{{$record->ReadingId}}</td>
            <td>{{date("F j, Y, g:i a",$record->RecordedForDate)}}</td>
            <td>{{$record->BodyFat}}</td>
            <td>{{$record->BodyMassIndex}}</td>
            <td>{{$record->BoneWeight}}</td>
            <td>{{$record->Hydration}}</td>
            <td>{{$record->MuscleMass}}</td>
            <td>{{$record->Weight}}</td>
        </tr>
        @endforeach
    </tbody>
    </table>                        

 </div>

</body>
</html>