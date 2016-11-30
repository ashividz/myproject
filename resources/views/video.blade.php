<div id="boxes">
  <div id="dialog" class="window">
  <div id="x" class="pull-right" title="Click To Close">x</div>
  <div id="countdown">Video will begin in <span id="counter"></span> seconds</div>
  	<video id="vid" width="1000" height="500" controls>
  		<source src="/logo.mp4">
  	</video>
  </div>
  <div id="mask"></div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{  
	var id = '#dialog';
		
	//Get the screen height and width
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
		
	//Set heigth and width to mask to fill up the whole screen
	$('#mask').css({'width':maskWidth,'height':maskHeight});
	//$('#id').css({'width':maskWidth,'height':maskHeight});

	//transition effect
	$('#mask').fadeIn(500);	
	$('#mask').fadeTo("slow",0.9);	
		
	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();
	              
	//Set the popup window to center
	$(id).css('top', winH/2-$(id).height()/2);
	$(id).css('left', winW/2-$(id).width()/2);

	$('#countdown').css('top', winW/2-$(id).width()/1.5);
	$('#countdown').css('left', winW/2-$(id).width()/4);
		
	//transition effect
	$(id).fadeIn(2000); 	
		
	//if close button is clicked
	$('.window .close').click(function (e) {
	//Cancel the link behavior
	e.preventDefault();

	$('#mask').hide();
	$('.window').hide();
	});

	//if mask is clicked
	$('#mask').click(function () {
	//$(this).hide();
	//$('.window').hide();
	});

	//if x is clicked
	$('#x').click(function () {
		$('#mask').hide();
		$('.window').hide();
		$('#vid').get(0).pause();
	});


	//Display Counter
	var counter = 11;
	var interval = setInterval(function() {
	    counter--;
	    $('#counter').empty();
	    $('#counter').append(counter);
	    if (counter == 0) {
	        $('#countdown').hide();
	        clearInterval(interval);
	    }
	}, 1000)

	//Play Vidoe
	setTimeout(function()
    {
        $('#vid').get(0).play();
    }, 11000);

	//Show Close button
	setTimeout(function()
    {
        $('#x').show();
    }, 30000);
	
});
</script>
<style type="text/css">
	#countdown {
		position: fixed;
		text-align: center;
		margin-top: 50px;
		font-size: 24px;
		color: #fff;
	}

	#mask {
	  	position: absolute;
	  	left: 0;
	  	top: 0;
	  	z-index: 9000;
	  	background-color: #000;
	  	display: none;
	}
	#boxes {
		text-align: center;
	}

#boxes .window {
  	position: absolute;
  	left: 0;
  	top: 0;
  	display: none;
  	z-index: 9999;
  	border-radius: 15px;
  	text-align: center;
}

#boxes #dialog {
  font-family: 'Segoe UI Light', sans-serif;
  font-size: 15pt;
}

#popupfoot {
  font-size: 16pt;
  position: absolute;
  bottom: 0px;
  width: 250px;
  left: 250px;
}
#x {
	display: none;
	color: red;
	font-weight: 800;
	cursor: pointer;
}
</style>