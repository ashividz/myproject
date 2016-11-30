<div class="row">
    <div id="workflow">
        <?php
            $pStep = null;
            $created_at = null;
            $updated_at = null;
        ?>
    
        @foreach($statuses AS $status)
            <div class="col-md-3">
                <div>{{$status->name}}</div>
                
        <?php  
            $content = "";
            $step = Step::getCartStepByStatus($cart->id, $status->id, $updated_at);
            //dd($step);
            if ($step) {
                
                $content = " <span class='".$step->state->css_class."'>".$step->state->name."</span></b>";

                $content .= " <small>by</small> ".$step->creator->employee->name." <small>on <em>".$step->updated_at->format('jS M, Y, h:i:A')."</em></small>";
                
                $content .= $step->remark <> '' ? " <small>(".$step->remark.")</small>" : "";
                $created_at = $step->created_at;
                $updated_at = $step->updated_at;
            } else {
                $created_at = Carbon::now();
            }
        ?>
                <div class="base">
                    <span style="font-size:.8em; padding-right: 30px;font-style: italic;"> {{ $pStep ? $pStep->diffForHumans($created_at) : '' }}</span>
                    <a data-html="true" data-toggle="popover" title="" data-content="{!! $content or "" !!}" data-placement="top"><strong class="{{$step->state->css_class or ''}}"></strong></a>
                </div>
            </div>
        <?php
            $pStep = $step ? $step->created_at : null;
        ?>

        @endforeach

    </div>
</div>