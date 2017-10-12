<h2 style="margin-left: 31%">Send Notification on YuWoW App</h2>
<div class="container">
	<form method="post" action="/yuwow/yuwowNotification" style="margin-left: 20%">

		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<!-- <input type="radio" name="notification" value="NCR" checked>Delhi NCR
  		<input type="radio" name="notification" value="PAN"> Pan India
  		<input type="radio" name="notification" value="INT"> International<br> -->
		<h4>Title</h4> <input type="text" name="title" placeholder="Enter title" required>
		<br>
		
		<h4>Notification</h4><textarea rows="6" cols="90" name="message" placeholder="Enter message" required></textarea>
		<br>
		<button type="submit" class="btn btn-primary">Send </button>
	</form>
</div>