<div class="container">
    <div class="panel panel-warning">
        <div class="panel-heading">
            <div class="col-md-4">
                @if(isset($tracking->estimated_delivery_timestamp))
                    <label>Estimated Delivery Time</label> :
                    <small>
                        {{ Carbon::parse($tracking->estimated_delivery_timestamp)->format('D, jS M, Y h:i A') }}
                    </small>

                @elseif(isset($tracking->actual_delivery_timestamp))
                    <label>Actual Delivery Time</label> :
                    <small>
                        {{ Carbon::parse($tracking->actual_delivery_timestamp)->format('D, jS M, Y h:i A') }}
                    </small>
                @endif
            </div>
            <div class="col-md-4" style="text-align: center;">
                <div class="statusbar_large {{ $tracking->status_class }}"></div>
                <div class="description {{ $tracking->status_class }}">
                    {{ $tracking->status_detail->Description or "" }}
                </div>
                <div>                    
                    {{ $tracking->status_detail->Location->City or "" }}
                </div>
            </div>
            <div class="col-md-4">
                
            </div>
            <h2></h2>
        </div>
        <div class="panel-body">
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-5">Service :</label>
                    <div class="col-md-7">
                        {{ $tracking->service->Description or "" }}
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-5">Tracking Number :</label>
                    <div class="col-md-7">
                        {{ $tracking->id }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-5">Special Handling :</label>
                    <div class="col-md-7">

                    @if(isset($tracking->special_handlings->Type))
                        {{ $tracking->special_handlings->Type }}
                    @else
                        @foreach($tracking->special_handlings as $special)
                            {{ $special->Description or "" }}<br>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-5">Package Weight :</label>
                    <div class="col-md-7">
                        {{ $tracking->package_weight->Value or "" }}
                        {{ $tracking->package_weight->Units or "" }}
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-5">Shipment Weight :</label>
                    <div class="col-md-7">
                        {{ $tracking->shipment_weight->Value or "" }}
                        {{ $tracking->shipment_weight->Units or "" }}
                    </div>
                </div> 
            </div>
        </div>
    @if(isset($tracking->service_commit_message))
        <div class="panel-footer" style="text-align: center;">            
            {{ $tracking->service_commit_message or "" }}
        </div>
    @endif
    </div>
    <div class="panel panel-warning">
        <div class="panel-body">
            <ul class="timeline">

            @if(isset($tracking->events->Timestamp))
                <?php $event = $tracking->events; ?>
                @include('shipping.partials.event')
            @else
                @foreach($tracking->events as $event)
                    @include('shipping.partials.event')
                    <?php  $i++ ?>
                @endforeach
            @endif

                
        </div>
    </div>
            
    </ul>
</div> 
<style>
.timeline {
  list-style: none;
  padding: 10px 0 10px;
  position: relative;
}
.timeline:before {
  top: 0;
  bottom: 0;
  position: absolute;
  content: " ";
  width: 3px;
  background-color: #eeeeee;
  left: 50%;
  margin-left: -1.5px;
}
.timeline > li {
  margin-bottom: 10px;
  position: relative;
}
.timeline > li:before,
.timeline > li:after {
  content: " ";
  display: table;
}
.timeline > li:after {
  clear: both;
}
.timeline > li:before,
.timeline > li:after {
  content: " ";
  display: table;
}
.timeline > li:after {
  clear: both;
}
.timeline > li > .timeline-panel {
  width: 46%;
  float: left;
  border: 1px solid #d4d4d4;
  border-radius: 2px;
  padding: 10px;
  position: relative;
  -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
  box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
}
.timeline > li > .timeline-panel:before {
  position: absolute;
  top: 26px;
  right: -15px;
  display: inline-block;
  border-top: 15px solid transparent;
  border-left: 15px solid #ccc;
  border-right: 0 solid #ccc;
  border-bottom: 15px solid transparent;
  content: " ";
}
.timeline > li > .timeline-panel:after {
  position: absolute;
  top: 27px;
  right: -14px;
  display: inline-block;
  border-top: 14px solid transparent;
  border-left: 14px solid #fff;
  border-right: 0 solid #fff;
  border-bottom: 14px solid transparent;
  content: " ";
}
.timeline > li > .timeline-badge {
  color: #fff;
  width: 50px;
  height: 50px;
  line-height: 50px;
  font-size: 1.4em;
  text-align: center;
  position: absolute;
  top: 10px;
  left: 50%;
  margin-left: -25px;
  background-color: #999999;
  z-index: 100;
  border-top-right-radius: 50%;
  border-top-left-radius: 50%;
  border-bottom-right-radius: 50%;
  border-bottom-left-radius: 50%;
}
.timeline > li.timeline-inverted > .timeline-panel {
  float: right;
}
.timeline > li.timeline-inverted > .timeline-panel:before {
  border-left-width: 0;
  border-right-width: 15px;
  left: -15px;
  right: auto;
}
.timeline > li.timeline-inverted > .timeline-panel:after {
  border-left-width: 0;
  border-right-width: 14px;
  left: -14px;
  right: auto;
}
.timeline-badge.primary {
  background-color: #2e6da4 !important;
}
.timeline-badge.success {
  background-color: #3f903f !important;
}
.timeline-badge.warning {
  background-color: #f0ad4e !important;
}
.timeline-badge.danger {
  background-color: #d9534f !important;
}
.timeline-badge.info {
  background-color: #5bc0de !important;
}
.timeline-title {
  margin-top: 0;
  color: inherit;
}
.timeline-body > p,
.timeline-body > ul {
  margin-bottom: 0;
}
.timeline-body > p + p {
  margin-top: 5px;
}
h2 {
    font-size: 16px;
    font-weight: 400;
    margin-top: 4px;
}
p {
    color: #777;
    font-size: 13px;
}
p.status_exception {
    font-style: italic;
    color: #ED1C24;
    font-size: 13px;
}
.vertical-date {
    font-weight: 500;
    text-align: right;
    font-size: 13px;
}
.vertical-date small {
    color: #62cb31;
    font-weight: 400;
}
.panel .panel-heading {
    min-height: 100px;
}
/** Statusbar **/
    .statusbar_large {
        overflow: hidden;
        background: url(images/statusbar_large.png) no-repeat;
        display: inline-block;
    }
    .statusbar_large.in_progress {
        width: 208px;
        height: 35px;
        background-position: -2px -39px;
    }
    .statusbar_large.picked_up {
        width: 208px;
        height: 35px;
        background-position: -2px -2px;
    }
    .statusbar_large.in_transit {
        width: 208px;
        height: 35px;
        background-position: -212px -2px;
    }
    .statusbar_large.exception {
        width: 208px;
        height: 35px;
        background-position: -212px -39px;
    }
    .statusbar_large.delivered {
        width: 208px;
        height: 35px;
        background-position: -212px -76px;
    }
</style>
