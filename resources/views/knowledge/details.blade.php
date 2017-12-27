<!DOCTYPE html>
<html>
<title></title>
<meta charset="UTF-8">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>
body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey">

<div class="w3-content" style="max-width:1400px">

<!-- Header -->
<header class="w3-container w3-center w3-padding-32"> 
  <h1><b>Ayurvedic Information</b></h1>
  <p>Welcome to the blog of NutriHealth</p>
</header>

<!-- Grid -->
<div class="w3-row">

<!-- Blog entries -->
<div class="w3-col l8 s12">
    @foreach($details as $detail)
  <div class="w3-card-4 w3-margin w3-white">
    <div class="w3-container">
      <h3 align="center"><b>{{$detail->Title}}</b></h3>
      <h5><b>Tag: </b>{{$detail->tag}}</h5>
    </div>

    <div class="w3-container">
      <p>{{$detail->Description}}</p>
      <!--<div class="w3-row">
        <div class="w3-col m8 s12">
          <p><button class="w3-button w3-padding-large w3-white w3-border"><b>Edit</b></button></p>
        </div>
      </div>-->
    </div>
  </div>
  @endforeach
  <hr>

<!-- END BLOG ENTRIES -->
</div>

<!-- Introduction menu -->
<div class="w3-col l4">
  <hr>
  
  <!-- Posts -->
  <div class="w3-card w3-margin">
    <div class="w3-container w3-padding">
      <h3><b>Add New</b></h3>
    </div>
    <form class="w3-container" role="form" method="post" action="/service/kd/save" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <p>
                <label>Title</label>
                <input class="w3-input" type="text" name="Title">
            </p>
            <p>
                <label>Description</label>
                <textarea rows="7" cols="20" name="Description"></textarea>
            </p>
                <label>Tags :</label>
                <select name="tagging">
                  <option></option>
                @foreach($tags as $tag)
                  <!--<option  value="{{$tag->id}}">{{$tag->tag_name}}</option>-->
                  <option  value="{{$tag->tag_name}}">{{$tag->tag_name}}</option>
                @endforeach
                </select>
            <button class="w3-button w3-padding-large w3-white w3-border"><b>Save</b></button></p>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
  </div>
  <hr> 

</div>


</div><br>

</div>

</body>
</html>

<style>
textarea {
    width: 100%;
    height: 150px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    resize: none;
}
</style>

</style>>
