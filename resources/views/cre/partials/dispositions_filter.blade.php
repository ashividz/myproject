<div class="dropdown"> 
    <a href="#">
      <span>Filter Dispositions</span>     
    </a>

    <div class="multiSelect">
    @foreach($calls as $call)
        <div class="col-md-{{$calls->count()?12/$calls->count():12}}">
            <h5>{{$call->disposition}}
                <input type="checkbox" value="c{{$call->id}}" checked /></h5>
            <ul>
            @foreach($call->dispositions as $disposition)
                <li>
                    <input type="checkbox" value="d{{$disposition->id}}" class="c{{$call->id}}" checked /> {{$disposition->disposition}}</li>
                <li>
            @endforeach  
            </ul>
        </div>
    @endforeach
    </div>
</div>
<style type="text/css">
    .dropdown {
        position: absolute;
        margin-left: 500px; 
        padding: 3px 20px;
        z-index: 9;
        background-color: #374954;
        color: #f2f2f2;
    }
    .dropdown a, .dropdown a:visited {
        color: #fff;
        text-decoration: none;
        outline: none;
        font-size: 16px;
    }
    .dropdown a span, .multiSel span {
        cursor: pointer;
        display: inline-block;
    }
    .dropdown .multiSelect{
        display: none;
        width: 500px;
    }
    .dropdown ul {
        margin-left: -10px;
    }
</style>
<script type="text/javascript">
    $(".dropdown a").on('click', function() {
        $(".dropdown .multiSelect").slideToggle('fast');
    });

    $(".multiSelect ul li input[type='checkbox'").on('change', function(){
        $('.'+this.value).toggleClass('hidden');
    })

    $(".multiSelect h5 input[type='checkbox'").on('click', function(){
        $("."+this.value).each(function () { 
            this.checked = !this.checked; 
            $('.'+this.value).toggleClass('hidden');
        });
    })
</script>