<div class="container" id="post">
  <div id="loader" v-show="loading" style="text-align:center" >
        <img src="/images/loading.gif">
    </div>
    <div class="panel">
        @if(\Auth::user()->canPost())
        <div class="panel-body" >
        <div class='col-md-3'>
          <div class="panel-heading">

            <a class='add_new_post_link' @click="addNew($event)">Add New</a>
              <h3 class="col-md-offset-1" style=''>All Post</h3>
          </div>

          <ul>
          <li class='post_title_link' @click="editPost($index)" v-for="post in posts">@{{post.title}}</li>
          </ul>
        </div>
        <div class='col-md-9'>
            <div class="panel-heading">
            <h3 class="col-md-offset-1">@{{addedit}}</h3>
            </div>
          <input id='token' type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="col-md-10 col-md-offset-1 input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" v-model="postTitle" class="form-control" placeholder="Title|Subject">
                </div>
            </div>
            <div class="form-group">

                <div class="col-md-10  col-md-offset-1 input-group" id='txteditr'>
                    <vue-html-editor :model.sync="postContent"></vue-html-editor>
                    <div style="margin-top:40px">
                     <hr>
                     
                </div>

            </div>
            <div class="form-group">
                <div class="col-md-10 col-md-offset-1">
                    <label>Publish</label> &nbsp;
                    <label class="btn btn-default active publish" >
                        <i class="fa fa-times" style='color: #ff6666'></i> 
                        <input checked="checked" name='postPublish' type="radio" v-model="postPublish" value="0"> 
                    </label>
                    <label class="btn btn-default active publish" >
                        <i class="fa fa-check" style='color: #40bf80'></i> 
                        <input name='postPublish' type="radio" v-model="postPublish" value="1"> 
                    </label>

                       <!-- <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default active">
                                <i class="fa fa-male"></i> 
                                <input type="radio" v-model="gender" value="M"> Male
                            </label>
                            <label class="btn btn-default">
                                <i class="fa fa-female"></i> 
                                <input type="radio" v-model="gender" value="F"> Female
                            </label>
                            <label class="btn btn-default">
                                <i class="fa fa-question"></i> 
                                <input type="radio" v-model="gender" value="O"></i> Other
                            </label>
                        </div> -->
                </div>
            </div>
            <div class="form-group add-padding" style="text-align:center;margin-top: 10px">
                <button class="btn btn-primary" style="background-color:#3C8DBC;"  @click="store" >
                    <i class="fa fa-save"></i> &nbsp;Save
                </button>
            </div>

        </div>
        
        </div>
    </div>
    @else
    <h2 style='text-align: center'>No Permission For This Page</h2>
    @endif
</div>
</div>
@include('partials/modal')
<link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/summernote/0.8.1/summernote.css">
<script type="text/javascript" src="https://cdn.bootcss.com/summernote/0.8.1/summernote.js"></script>
<script>
Vue.component('vue-html-editor', {
  replace: true,
  inherit: false,
  template: "<textarea class='form-control' :name='name'></textarea>",
  props: {
    model: {
      required: true,
      twoWay: true
    },
    language: {
      type: String,
      required: false,
      default: "en-US"
    },
    height: {
      type: Number,
      required: false,
      default: 160
    },
    minHeight: {
      type: Number,
      required: false,
      default: 160
    },
    maxHeight: {
      type: Number,
      required: false,
      default: 800
    },
    name: {
      type: String,
      required: false,
      default: ""
    },
    toolbar: {
      type: Array,
      required: false,
      default: function() {
        return [
          ["font", ["bold", "italic", "underline", "clear"]],
          ["fontsize", ["fontsize"]],
          ["para", ["ul", "ol", "paragraph"]],
          ["color", ["color"]],
          ["insert", ["link", "hr", "table"]],
          ["Misc", ["undo", "redo", "codeview"]],
        ];
      }
    }
  },
  beforeCompile: function() {
    this.isChanging = false;
    this.control = null;
  },
  ready: function() {
    //  initialize the summernote
    if (this.minHeight > this.height) {
      this.minHeight = this.height;
    }
    if (this.maxHeight < this.height) {
      this.maxHeight = this.height;
    }
    var me = this;
    this.control = $(this.$el);
    this.control.summernote({
      lang: this.language,
      height: this.height,
      minHeight: this.minHeight,
      maxHeight: this.maxHeight,
      toolbar: this.toolbar,
      callbacks: {
        onInit: function() {
          me.control.summernote("code", me.model);
        }
      }
    }).on("summernote.change", function() {
      // Note that we do not use the "onChange" options of the summernote
      // constructor. Instead, we use a event handler of "summernote.change"
      // event because that I don't know how to trigger the "onChange" event
      // handler after changing the code of summernote via ".summernote('code')" function.
      if (! me.isChanging) {
        me.isChanging = true;
        var code = me.control.summernote("code");
        me.model = (code === null || code.length === 0 ? null : code);
        me.$nextTick(function () {
          me.isChanging = false;
        });
      }
    });
  },
  watch: {
    "model": function (val, oldVal) {
      if (! this.isChanging) {
        this.isChanging = true;
        var code = (val === null ? "" : val);
        this.control.summernote("code", code);
        this.isChanging = false;
      }
    }
  }
})

var vm = new Vue({
  mixins: [mixin],
  el: "#post",
  
  data: {
    postTitle: '',
    postContent: '',
    postPublish: '0',
    postId: '0',
    loading: false,
    posts: [],
    addedit: 'Add'
  },
  ready : function() {
         this.getPosts();
    },
   methods: {

            getPosts() {
                this.$http.get('/getPosts/user/{{ Auth::id() }}')
                .success(function(data) {
                    this.posts = data;
                  
                }.bind(this));
             },
             

            editPost(id) {
              this.addedit = 'Edit';
              this.postContent = this.posts[id].content;
              this.postTitle = this.posts[id].title;
              this.postPublish = this.posts[id].publish;
              this.postId = this.posts[id].id;
            },
            addNew(event) {
               event.preventDefault();
              this.addedit = 'Add';
              this.postContent = '';
              this.postTitle = '';
              this.postPublish = '0';
              this.postId = '0';
            },

            store() {
              this.loading = true;
             this.$http.post("/storePost", {
                title: this.postTitle,
                content: this.postContent,
                publish: this.postPublish,
                id: this.postId,
                created_by: {{ Auth::id() }}
                })
             .success(function(post){
                    toastr.success('Post saved', 'Success!');
                    
                      this.getPosts();
                      window.location.href = "/showPosts"
                      //router.go('/showPosts');
                    
                })
                .error(function(errors) {
                    this.loading = false;
                    this.toastErrors(errors);
                    
                })
                .bind(this);
              
            }
        }
});


</script>




<script type="text/javascript">
$(document).ready(function() 
{
    $( "#downloadCSV" ).bind( "click", function() 
    {
        var csv_value = $('.tab-pane.active').find('small').remove();
        
        $( "td" ).each(function() {
            t = $(this).text();
            t = t.replace(/[\r\n]+/g, '');
            $(this).text(t);
            //console.log(t);
        });      
        

        $('.tab-pane.active').find('small').remove();        
        var csv_value = $('.tab-pane.active').find('table').table2CSV({
                delivery: 'value'
            });
        console.log(csv_value);
        downloadFile('creconversion.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
        $("#csv_text").val(csv_value);
        location.reload();
    });

    function downloadFile(fileName, urlData){
        var aLink = document.createElement('a');
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent("click");
        aLink.download = fileName;
        aLink.href = urlData ;
        aLink.dispatchEvent(evt);
    }

    $('#daterange').daterangepicker(
    { 
        ranges: 
        {
            'Today': [new Date(), new Date()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), new Date()],
            'Last 30 Days': [moment().subtract(29, 'days'), new Date()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }, 
        format: 'YYYY-MM-DD' 
        }
    );   
    $('#daterange').on('apply.daterangepicker', function(ev, picker) 
    {   
        $('#daterange').trigger('change'); 
    });

     $('#conversionDate').daterangepicker(
    {   
        singleDatePicker: true,
        showDropdowns: true,
        ranges: 
        {
            'Today': [new Date(), new Date()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), new Date()],
            'Last 30 Days': [moment().subtract(29, 'days'), new Date()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }, 
        format: 'YYYY-MM-DD' 
        }
    );   
    $('#conversionDate').on('apply.daterangepicker', function(ev, picker) 
    {   
        $('#conversionDate').trigger('change'); 
    });
});
</script>                   
</div>

<style>
.post_title_link
{
color: #ff944d;
font-weight: bold;
padding: 3px 0px;
cursor: pointer;
}

.add_new_post_link
{
  display: inline-block;
  margin-left: 20px;
  font-size: 18px;
  padding: 2px 9px;
  -webkit-box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.55);
  -moz-box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.55);
  box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.55);

  -webkit-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.45);
  -moz-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.45);
  box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.45);
  border-top: 1px solid #ddd;
  margin-top: 2px;
  cursor: pointer;
  color: #555;
}

.note-editor .note-toolbar
{

    background: #ddd !important;
}
.btn.publish
{
    padding: 1px 12px;
}

.btn.publish .fa
{
    font-size: 18px;
}
</style>
