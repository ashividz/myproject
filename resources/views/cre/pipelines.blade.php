<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-left">
			@include('cre/partials/index')
		</div>
		<div>
			@include('cre/partials/dispositions_filter')
		</div>
	</div>
	<div class="panel-body">
@foreach($statuses as $status)
		<div class="col-md-2">
			<div class="stage">
				<div class="stagehead">
					<span class="stagename">{{$status->name}} <em>({{$status->leads->count()}})</em></span> 
				</div>
			</div>

		@foreach($status->leads as $lead)
			<div class="leads d{{$lead->disposition->disposition_id or ''}}">						
					<div class='icon'>
						<img src='/images/status{{$status->id}}.png'>
					</div>
				<div class='block'>
					<strong>
						<a href='/lead/{{$lead->id}}/viewDispositions' target='_blank'>{{ $lead->name }} </a>
						
						<em data-toggle="popover" data-html="true" data-placement="top" data-ajax="/api/lead/{{$lead->id}}/dispositions?name={{$name}}">({{ $lead->dispositions()->where('name', $name)->count() . "/" . $lead->dispositions()->count() }})</em>

					</strong>
					<small>
				@if(!$lead->sources->isEmpty())
						<span>
					
					@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
							[ {{ $lead->source->master->source or "" }} ]
					@else
							[{{ $lead->source->master->channel->name or "" }}]
					@endif
							{{ $lead->cre ? date('M j, Y', strtotime($lead->cre->created_at)) : ''}}
						</span>
				@endif

					</small>

				</div>

			</div>
		@endforeach

		</div>
		
@endforeach

	</div>
</div>
<style type="text/css">
.panel-body {
	background-color: #F0F0F0;
}
.col-md-2 {
	padding: 0px;
}
.stage {
	background: url('/images/editable-area-bg.png');
    height: 55px;
}
.stage .stagehead {
	background: url('/images/stage-cone-large.png') right center repeat-x;	
    height: 55px;
    vertical-align: middle;
    padding: 15px 30px 6px 12px;
}
.stage .stagename {
	font-family: Michroma,Cantarell;
	font-size: 14px;
    line-height: 21px;
    font-weight: 900;
    text-transform: uppercase;
}
.leads {
	background-color: #fff;
	min-height: 50px;
	margin: 1px;
}
.block {
    display: block;
    width: 100%;
    position: relative;
    min-height: 50px;
    margin: 0;
    border: none;
    padding-left: 20px;
}
.block strong {
    position: relative;
    font-weight: 700;
    display: block;
    cursor: pointer;
    font-family: Oswald;
    font-size: 12px;
    line-height: 18px;
    transition: color 0.1s ease-in-out;
    padding: 6px 25px 6px 12px;
}
.block em {
    font-size: 12px;
    line-height: 16px;
    padding: 2px 0;
    color: #888A8D;
}
.icon
{
	position: absolute;
	width: 50px;
	height: 50px;
}
.icon img {
    height: 32px;
    vertical-align: middle;
    padding: 5px;
}
.popover {
	min-width: 500px;
	z-index: 99999;
	font-size: 10px;
}
</style>
<script type="text/javascript">
	$('*[data-ajax]').click(function() {
		$('.popover').not(this).popover('hide'); //all but this
    	var e=$(this);
    	e.off('hover');
    	$.get(e.data('ajax'),function(d) {
        	e.popover({content: d}).popover('show');
    	});
	});
</script>