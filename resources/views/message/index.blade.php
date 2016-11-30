<div class="container" id="messages">
	<!-- Menu -->
	<div class="container">
		<a href="/message/compose" class="btn {{$section=='compose' ? 'btn-primary' : 'btn-default'}}">Compose <i class="fa fa-envelope-o"></i></a>
		<a href="/message/inbox" class="btn {{$section=='inbox' ? 'btn-primary' : 'btn-default'}}">Inbox <i class="fa fa-inbox"></i></a>
		<a href="/message/outbox" class="btn {{$section=='outbox' ? 'btn-primary' : 'btn-default'}}">Outbox <i class="fa fa-th-list"></i></a>
	</div>
	<!-- Main -->
	<div>
		@yield('main')
	</div>
</div>	