<div class="container">
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

<style type="text/css">
	.outbox ul li {
		margin-left: -30px;
	}
	.new-message {
		display: inline;
		margin-left: 10px;
		background-color: #c00;
		padding: 5px 10px;
		color: #fff;
		border-radius: 2px
	}

	.table tr:hover {
		background-color: #f9f9f9;
		color: #333 !important;
		cursor: pointer;
	}
	table tr.unread {
		font-weight: 800;
		background-color: #ffffcc;
	}
	.table tr {
        border-bottom: solid 1px #dedede;
		background-color: #f4f4f4;
		font-weight: 300;
    }
    .view {
        border: solid #ddd 1px;
        border-radius: 4px;
        padding: 2px 15px 2px 10px;
        background-color: #dedede;
    }
    .view a {        
        color: #333;
    }
    .view a, a:visited {
        text-decoration: none;
    }
    .view .aTn {
        background: no-repeat url(/images/smartmail.png) -67px -100px;
        width: 7px;
        height: 7px;
        opacity: .55;
        margin-top: 2px;
    }
</style>

	