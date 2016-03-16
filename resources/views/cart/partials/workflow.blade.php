<div class="row">
    <div id="workflow">
    
        @foreach($statuses AS $status)
            <div class="col-md-{{!$statuses->isEmpty()? floor(12/$statuses->count()): 3}}">
                <div>{{$status->name}}</div>
                
        <?php  
            $content = "";

            $step = Step::getCartStepByStatus($cart->id, $status->id);
            if ($step) {
                $content = " <span class='".$step->state->css_class."'>".$step->state->name."</span></b> <small>by</small> ".$step->user->employee->name." <small>on <em>".$step->created_at->format('jS M, Y, h:i:A')."</em></small>";
                $content .= $step->remark <> '' ? " <small>(".$step->remark.")</small>" : "";
            } 
        ?>

                <div class="base">
                    <a data-html="true" data-toggle="popover" title="" data-content="{!! $content or "" !!}" data-placement="top"><strong class="{{$step->state->css_class or ''}}"></strong></a>
                </div>
            </div>

        @endforeach

    </div>
</div>
<style type="text/css">
#workflow .col-md-{{!$statuses->isEmpty()? 12/$statuses->count(): 3}} {
    padding: 0px 0px 40px 0px;
    text-align: center;
}
.base {
    height: 2px;
    background-color: #a3a3a3;
    width: 100%;
    display: block;
    margin-top: 20px;
    text-align: center;
}
strong {
    background-color: #fff;
    border: 2px solid #a3a3a3;
    border-radius: 50%;
    box-shadow: 0 0 2px #2abd2a inset;
    color: #019856;
    display: inline-block;
    padding: 15px;
    position: relative;
    text-align: center;
    top: -15px;
    font-size: 16px;
}
strong.primary {
    background-color: #2E6DA4;
}
strong.danger {
    background-color: #D43F3A;
}
strong.success {
    background-color: #4CAE4C;
}

.primary {
    color: #2E6DA4;
}
.danger {
    color: #D43F3A;
}
.success {
    color: #4CAE4C;
}
.popover {
    max-width: 1200px;
}
</style>
<script type="text/javascript">
    
    
    $('[data-toggle="popover"]').popover(); 
    
    $('body').on('click', function (e) {
        //did not click a popover toggle, or icon in popover toggle, or popover
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('[data-toggle="popover"]').length === 0
            && $(e.target).parents('.popover.in').length === 0) { 
            $('[data-toggle="popover"]').popover('hide');
        }
    })
</script>