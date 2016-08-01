<div class="container" id="post">
  <div id="loader" v-show="loading" style="text-align:center" >
        <img src="/images/loading.gif">
    </div>
    <div class="panel">
         
        <div class="panel-body" >
        <div class='col-md-3' style='display: none'>
        <div  v-show="myposts.length">
            <div class="panel-heading">
              <h3 class="page_head">My Posts</h3>
          </div>

          <ul style='padding-left: 10px'>
          <li class='post_title_link' @click="editPost($index)" v-for="post in myposts">@{{post.title}}</li>
          </ul>

          </div>
        </div>
        <div class='col-md-12'>
            <div class="panel-heading">
            <div class='row'>
            <div class='col-md-6'>
            <h3 class="page_head">Posts</h3>
            <input type="text" id="postDate" class='pull-right' v-model="postDate" size="15" readonly/>
            </div>

            @if(\Auth::user()->canPost())
               <div class='col-md-6'>
                   <a class='add_new_post_link' @click="addNew($event)">Add New</a>
                </div>
            @endif
            </div>
            </div>
            
            <div class='container postedit' v-if="addPost">
             <input id='token' type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="col-md-10  input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                    <input type="text" v-model="postTitle" class="form-control" placeholder="Title|Subject">
                </div>
            </div>
            <div class="form-group">

                <div class="col-md-10   input-group" id='txteditr'>
                    <vue-html-editor :model.sync="postContent"></vue-html-editor>
                    

            </div>
            <div class="form-group">
                <div class="col-md-4">
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
                <div class="col-md-5" >
                <button class="btn btn-primary" style="background-color:#3C8DBC;"  @click="store" >
                    <i class="fa fa-save"></i> &nbsp;Save
                </button>
                </div>
            </div>
           

        </div>     
        </div>

            <div class="form-group">
               <div v-for="post in posts"> 
                  <post-content :post.sync="post"  />
               </div>
            </div>
            
        
        </div>
    </div>
</div>

<template id="post-container">
    <div class='postbox @{{ mypost + "mypost" }}' class="col-md-12" style=''>
        <div>

           <div class='container postedit' v-show="editView && mypost">
                <input id='token' type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="col-md-10  input-group">
                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                        <input type="text" v-model="postTitle" class="form-control" placeholder="Title|Subject">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-10   input-group" id='txteditr'>
                        <vue-html-editor :model.sync="postContent"></vue-html-editor>
                    </div>
                    <div class="form-group">
                   
                        <div class="col-md-5 col-md-offset-1" >
                        <button class="btn btn-primary" style="background-color:#3C8DBC;"  @click="store" >
                            <i class="fa fa-save"></i> &nbsp;Save
                        </button>
                        </div>
                    </div>
               </div>     
           </div>


          <div v-else>
                <div class='post_head'>
                <h3 class='post_title' @click="toggleEdit" style=''> @{{ post.title }} </h3>
                <p><span class='post_meta authname'>
                   @{{post.creator.employee.name}}   
                   </span>
                   <span class='post_meta'>
                    @{{ post.created_at | format_date }}  
                   </span>
                </p>
                </div>
                <div class='post_text'>
                    @{{{ post.content }}} 
                </div>
            </div>

            


            <div class='row comment_inputbox' v-show="commentEnabled">
                <div class="col-xs-1 col-md-1 likepad">
               
                <span class='post_meta '>
                <a href='' @click="updateLike('1', $event)">Like @{{post.likeCount}}</a> 
                </span> 
                </div>
                <div class="col-xs-9 col-md-9">
                    <textarea placeholder='Your Comment' class='comment_txtbox' v-model="commenttxt" ></textarea>
                    
                </div>
                <div class="col-xs-2 col-md-2">
                    
                    <div class='submit_box'><a class='comment_submit' href='' @click="commentSubmit($event)">Submit</a></div>
                    <div class='comment_loading' v-show="loading"  style="" >
                        <img src="/images/loading1.gif">
                    </div>
                </div>
            </div>
            <div class='comment_container' v-show="commentEnabled">
            <h5>Comments</h5>
                
                <div class='comment' v-for="comment in comments"> 
                    <comment v-bind:comment.sync="comment" />
                </div>
               
            </div>
        </div>
    </div>
</template>

<template id="comment">
    <div class="comment_template" style=''>
       <div >
         <div class='comment_data'>
          <p class='comment_text'>@{{ comment.content }} </p>
          <div><span class='comment_by'>@{{comment.creator.employee.name}}</span> &nbsp;<span class='comment_time'>@{{comment.time}}</span>
          <a class='likelink' href='' @click="updateLike2('1', $event)"><i>&nbsp;</i> @{{comment.likeCount}}</a>

          <span class='replylnk' href='' @click="reply=!reply"> Reply</span>

          </div>

          <div v-if="comment.level > 1" class='tree'>&nbsp;</div>
          </div>
          <div v-if="reply">
          <div class='row reply_textbox'>
                <div class="col-xs-10 col-md-10">
                    <textarea placeholder='Your Comment' class='comment_txtbox' v-model="commenttxt" ></textarea>
                    
                </div>
                <div class="col-xs-2 col-md-2">
                    
                    <div class='submit_box'><a class='comment_submit' href='' @click="commentSubmit($event)">Submit</a></div>

                    <div class='comment_loading' v-show="loading"  style="" >
                        <img src="/images/loading1.gif">
                    </div>
                </div>

                 
                </div>
          </div>
          <div class='comment childcomment' v-for="comment in comments"> 
                    <comment v-bind:comment.sync="comment" />
                  
          </div>
       </div>
        
    </div>
</template>


@include('partials/modal')
<link rel="stylesheet" type="text/css" href="/css/summernote.css">
<script type="text/javascript" src="/js/summernote.js"></script>
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


Vue.component('post-content', {
    mixins: [mixin],
    template: '#post-container',
    props: ['post'],
    data: function() {
         return {
     comments: [],
     commenttxt: '',
     loading: false,
     editView: false,
     postTitle: '',
     postContent: '',
     postPublish: '0',
     postId: '0',
     mypost: false
    }
        
    },
   created: function () {
    if(this.post.created_by== {{ Auth::id() }})
      this.mypost = true;
    this.getComments(this.post.id);
    },
     methods: {

        toggleEdit() {
            this.editView = true;
            this.postContent = this.post.content;
            this.postTitle = this.post.title;
            this.postPublish = this.post.publish;
            this.postId = this.post.id;
        },

        updateLike(state, event) {
           event.preventDefault();
            this.$http.patch("/updateLike/" + this.post.id, {
                state: state,
                model: 'Post'
            })
            .success(function(data){
               this.getPost(this.post.id);
            })
            .error(function(errors) {
                this.$parent.toastErrors(errors);
            })
            .bind(this);
            
        },

        getPost(id) {
           this.$http.get('/getPost/' + id)
            .success(function(data) {
                this.post = data;
            }.bind(this));
        },

        commentSubmit(event) {
            event.preventDefault();
            if(this.loading == true)
                return;
            this.loading = true;
            this.$http.post("/commentSubmit/" + this.post.id, {
                content: this.commenttxt,
                level: 1,
                replyto: 0
            })
            .success(function(data){
               this.commenttxt = ''; 
               this.getComments(this.post.id);
               
               this.loading = false;
            })
            .error(function(errors) {
                this.loading = false;
                this.toastErrors(errors);
            })
            .bind(this);
         

        },

        getComments(id) {
            
            this.$http.get("/getComments/" + this.post.id)
            .success(function(data){
               this.comments = data;
            })
            .error(function(errors) {
                this.$parent.toastErrors(errors);
            })
            .bind(this);
            this.edit = false;

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
                      this.post.title = this.postTitle;
                      this.post.content = this.postContent;
                      
                      
                      this.loading = false;
                      
                      this.editView = false;
                      //window.location.href = "/showPosts"
                      //router.go('/showPosts');
                    
                })
                .error(function(errors) {
                    this.loading = false;
                    this.toastErrors(errors);
                    
                })
                .bind(this);
              
            }

    },

    computed: {

        post_date() {
            var range = this.daterange.split(" - ");
            return moment(range[0]).format('YYYY-MM-DD') + ' 0:0:0';
        }
    }

});

Vue.component('comment', {
    mixins: [mixin],
    template: '#comment',
    props: ['comment'],
    data: function() {
          return {
     reply: false,
     commenttxt: '',
     comments: [],
     loading: false,
    }
    },
    created: function () {
        if(this.comment.hasChild)
        {
         this.getChildComments();
        }
    },
    watch:{
    'reply': {
        handler:function() {
            console.log(this.reply)
        },
        deep:true
    }
},
     methods: {
        updateLike2(state, event) {
            
           event.preventDefault();
            this.$http.patch("/updateLike/" + this.comment.id, {
                state: state,
                model: 'Comment'
            })
            .success(function(data){
               this.getComment(this.comment.id);
               
            })
            .error(function(errors) {
                this.$parent.toastErrors(errors);
            })
            .bind(this);
            this.edit = false;
        },

        getComment(id) {
           this.$http.get('/getComment/' + id)
            .success(function(data) {
                this.comment = data;
            }.bind(this));
        },

         commentReply(id) {
           this.$http.get('/postReply/' + id)
            .success(function(data) {
                toastr.success('Reply Submitted', 'Success!')
                this.comment = data;
            }.bind(this));
        },

        commentSubmit(event) {
            event.preventDefault();
            if(this.loading == true)
                return;
            this.loading = true;
            this.$http.post("/commentSubmit/" + this.comment.post_id, {
                content: this.commenttxt,
                level: parseInt(this.comment.level)+1,
                replyto: this.comment.id
            })
            .success(function(data){
               this.commenttxt = '';
               this.getChildComments();
               this.reply = false;
               this.loading = false;
               

               
            })
            .error(function(errors) {
                this.loading = false;
                this.toastErrors(errors);
            })
            .bind(this);
         

        },


        getChildComments()
        {
            
            this.$http.get('/getChildComments/' + this.comment.id)
            .success(function(data) {
                
                this.comments = data;
            }.bind(this));

        }

    }

});

var vm = new Vue({
 mixins: [mixin],
  el: "#post",
  data: {
    posts: [],
    myposts: [],
    loading: false,

    postTitle: '',
    postContent: '',
    postPublish: '0',
    postId: '0',
    addedit: 'Add',
    addPost: false,
    commentEnabled: false,
    postDate: '{{ Carbon::now()->format('Y-m-d') }}'
  },
   ready : function() {
        this.getPosts();
    },
   methods: {
          getPosts() {
               this.loading = true;
               this.$http.get('/getPosts', {
                start_date: this.start_date, 
                end_date: this.end_date,
                })
                .success(function(data) {
                    this.posts = data;
                    this.loading = false;
                    this.setmyPosts();
                }.bind(this));
            },

             setmyPosts() {

              this.myposts = [];
              for(i=0; i <  this.posts.length; i++)
              {

                if(this.posts[i].created_by=={{ Auth::id() }})
                {
                    //alert(this.posts[i].created_by);
                    this.myposts.push(this.posts[i]);
                    //this.$set('myposts', this.posts[i]);
                    //alert(this.myposts[0].title);
                }
              }
            },

            editPost(id) {
              this.addPost = true;
              this.addedit = 'Edit';
              this.postContent = this.myposts[id].content;
              this.postTitle = this.myposts[id].title;
              this.postPublish = this.myposts[id].publish;
              this.postId = this.myposts[id].id;
            },

            addNew(event) {
              event.preventDefault();
              
              this.addPost = true;
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
                      this.loading = false;
                      this.addPost = false;
                      //window.location.href = "/showPosts"
                      //router.go('/showPosts');
                    
                })
                .error(function(errors) {
                    this.loading = false;
                    this.toastErrors(errors);
                    
                })
                .bind(this);
              
            }
        },

        computed: {
            start_date() {
                var range = this.postDate;
                return moment(range).format('YYYY-MM-DD') + ' 0:0:0';
            },

            end_date() {
                var range = this.postDate;
                return moment(range).format('YYYY-MM-DD') + ' 23:59:59';
            }
        }
});

vm.$watch('postDate', function (newval, oldval) {
        this.getPosts();
    })

  Vue.filter('format_date', function (value) {
        if (value == null) {
            return null;
        }
      return moment(value).format('D MMM YYYY, hh:mm A');
    })
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

     $('#postDate').daterangepicker(
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
    $('#postDate').on('apply.daterangepicker', function(ev, picker) 
    {   
        $('#postDate').trigger('change'); 
    });
});
</script>                   
</div>
<style>
.comment_container .comment
{

  
    padding: 5px 0px;
}
.likelink i{
    background-image: url("/images/N5ic9UxCaHj.png");
    background-position: 1px -503px;
    background-repeat: no-repeat;
    background-size: auto auto;
    display: inline-block;
    width: 16px;
}
.comment_time
{
   color: #555; 
   font-size: 10px;
}
.comment_by
{
 font-style: italic;
 color: #777; 
 font-size: 10px;   
}
.comment_text
{
    margin-bottom: 2px;
    font-size: 12px;
}
.comment
{

    margin-bottom: 5px;
}
.comment_inputbox
{
    background: #eee;
    border: 1px solid #e6e6e6;
    margin-top: 20px;
}
.comment_inputbox .pad
{
    padding-top: 10px;
}
.comment_txtbox
{
width: 100%;
height: 40px;
}
.comment_submit
{
    display: inline-block;
    background: #bbb;
    background: #b3e0ff;
   -webkit-box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.55);
   -moz-box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.55);
    box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.55);
    padding: 2px 10px;
    color: #333;
    margin-top: 8;
}
.postbox
{
    box-shadow:0 1px 3px 0 #d4d4d5, 0 0 0 1px #d4d4d5;
    padding: 15px;
    padding-bottom: 25px;
    margin-bottom: 30px;
    box-shadow: 0 1px 3px 1px #d4d4d5, 0 1px 1px 1px #d4d4d5;
    background: #f9f9f9;
    border: 1px solid #76abd6;
   
}
.panel-body
{
    background: #f4f4f4;

}
.post_title
{
color: #00264d;
margin-top: 5px;
margin-bottom: 5px;

}
.childcomment
{
    margin-left: 20px;
}
.replylnk
{   
    padding-left: 10px;
    cursor: pointer;
    color: #ff944d;
    color: #33adff;
    font-size: 11px;
}
.reply_textbox
{

    padding: 4px 0px;
    background: #eee;
    border: 1px solid #e6e6e6;
}
.reply_textbox .col-md-2
{

    padding-left: 0px;
}
.comment_data, .comment_template
{
    position: relative;

}

.comment_container .childcomment
{
   margin-bottom: 0px !important;
   padding-bottom: 0px !important;
}

.comment_data .tree
{
    position: absolute;
    left: -20px;
    bottom: -3px;
    width: 10px;
    height: 20px;
    border-left: 1px solid #aaaaaa;
    border-bottom: 1px solid #aaaaaa;
}
.childcomment  .tree
{
    position: absolute;
    left: -10px;
    bottom: -3px;
    width: 10px;
    height: 100%;
    border-left: 1px solid #bbbbbb;
    border-bottom: 1px solid #bbbbbb;
}

.post_meta
{
    display: inline-block;
    margin-right: 10px;
    color: #777;
}
.comment_loading, .submit_box
{

    display: inline-block;
}

.comment_loading img
{

    width: 20px;
}
.post_text, .post_text p
{
    font-size: 14px;
}
.post_text
{
   overflow: auto;
    max-height: 500px;
}
.post_head
{
margin-bottom: 10px;

border-bottom: 2px solid #e7e7e7;
}
.post_head p
{
margin-top: 5px;
margin-bottom: 5px;

}
.post_meta.authname
{
    font-style: italic;
    color: #555;
}
.likepad span
{
    font-size: 14px;
    margin-right: 0px;
     margin-top: 10px;
}
.likepad
{
    padding-right: 0px;
}
.page_head
{
    display: inline-block;
    margin-top: 0px;
    margin-bottom: 0px;
    color: #fff;
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
background: #ffffff;
  -webkit-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.45);
  -moz-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.45);
  box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.45);
  border-top: 1px solid #ddd;
  margin-top: 2px;
  cursor: pointer;
  color: #555;

}
.add_new_post_link:hover
{

    color: #333;
}
.panel-heading
{
    background: #e1e1e1;
    background: #ffa366;
    background: #5697cc;
    margin-bottom: 5px;
}
.panel-body
{
    padding-top: 0px;
}

.post_title_link
{
color: #aaa;
font-weight: bold;
padding: 3px 0px;
cursor: pointer;
font-size: 14px;
}
.post_title_link:hover
{
color: #888;
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
.postedit
{
    margin: 20px 0px;
    background: #eee;
    padding-top: 10px;
    padding-bottom: 20px;
    border: 1px solid #e1e1e1;
} 
.truemypost
{
  border: 1px solid #76abd6;
}
#postDate
{
  font-size: 14px;
}
.note-editing-area, .note-editable
{
  overflow: auto;
   max-width: 1000px;
}
#txteditr
{
  width: 100%;
  overflow: hidden;
}
</style>