<div class="col-md-8 col-md-offset-2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Add Brunch Articles</h2>
        </div>
        <div class="panel-body">
            <hr>
            <form method="POST" role="form" class="form-inline" id="form" data-message="Do you really want to save this Recipe?">
                <fieldset>
                    <ol> 
                         <li>
                            <label>Title*</label>
                            <input type="text" name="article_title" style="width:590px" required>
                        </li>

                        <li>
                            <label>Image_URL *</label>
                            <input type="text" name="article_img" step="0.01" style="width:590px" required>
                        </li>
                        <li>
                            <label>Description *</label>
                            <textarea name="article_desc" cols="100" rows="30"  style="width:790px" required></textarea>
                         </ol>
                </fieldset>             
                <div class="row">
                
                    <div class="col-md-4">
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        <input class="btn btn-danger" type="reset" value="Clear form">
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">          
            </form> 
        </div>  
    </div>
    
</div>


<?php $i=0;?>
    <div class="panel panel-default">
        <div class="panel-body">

            @if($brunch_articles)

                <table class="table table-bordered">
                    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>article_title</th>
                            <th>article_img</th>
                            <th>article_desc</th>
                        </tr>
                    </thead>
                        
                    <tbody>
            @if($brunch_articles <> NULL)
                @foreach($brunch_articles as $brunch_article)
                    <?php $i++;?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                {{ $brunch_article->article_title or " "}}
                            </td>
                             <td>
                                 <img src="{{$brunch_article->article_img}}" alt="Flowers in Chania">
                            </td>
                            <td>
                                {{ $brunch_article->article_desc or " "}}
                            </td>
                        </tr>                
                @endforeach
            @endif

                @if(!$brunch_articles)
                    <tr>
                        <td colspan="11">No results found</td>
                    </tr>
                @endif
                    </tbody>

                </table>
            @endif
        </div>
    </div>
</div>