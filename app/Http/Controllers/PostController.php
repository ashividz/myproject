<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Auth;
use DB;

class PostController extends Controller
{
   
    protected $menu = "post";

    public function __construct(Request $request)
    {
    
        $this->nutritionist = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date('Y-m-d', strtotime('-5 days'));
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d', strtotime('+5 days'));
        $this->allUsers = $request->user=='' ? true : false;
        
    } 
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function addPost(Request $request)
    {
        $section = "add_post";
        $data = array(
            'section'           => $section,
            'menu'              => $this->menu
        );
        return view('home')->with($data);
    }


    public function editPost(Request $request)
    {   

      
        $section = "edit_post";
        $data = array(
            'section'           => $section,
            'menu'              => $this->menu        
        );
        return view('home')->with($data);
      
    }

     public function getLikes(Request $request)
    {
        $pst = Post::find($request->id);
        $lik = $pst->likes->count();
        return $lik;
    }


       public function showPosts(Request $request)
    {
        $section = "view_posts";
        $data = array(
            'section'           => $section,
            'menu'              => $this->menu
        );
        return view('home')->with($data);
    }

       public function getPost(Request $request)
    {
       $post = Post::with('creator.employee')->find($request->id);
       $post->likeCount = $post->likes->count();
       $post->dislikeCount = $post->dislikes->count();
       
       return $post;
    }

      public function getPosts(Request $request)
    {
       $posts = Post::with('creator.employee')
                     ->whereBetween('created_at', Array($request->start_date, $request->end_date))
                     ->orderBy('id', 'DESC')->get();
       foreach($posts as $post)
       {
           $post->likeCount = $post->likes->count();
           $post->dislikeCount = $post->dislikes->count();
       }
       
       return $posts;
    }

       public function getUserPosts(Request $request)
    {
       $posts = Post::with('creator.employee')->where('created_by', $request->id)->orderBy('id', 'DESC')->get();
       foreach($posts as $post)
       {
           $post->likeCount = $post->likes->count();
           $post->dislikeCount = $post->dislikes->count();
       }
       
       return $posts;
    }

      public function commentSubmit(Request $request)
    {
       $this->validate($request, [
            'content'        => 'required',
            
         ]);

        $comment = new Comment();
        $comment->content   = $request->content;
        $comment->post_id = $request->id;
        $comment->level = $request->level;
        $comment->user_id = Auth::id();
        $comment->replied_to  = $request->replyto;
        $comment->save();

       return json_encode(['id' => $comment->id]);
    }

   public function getComments(Request $request)
    {
      $comments = Comment::with('creator.employee')->where('post_id', $request->id)->where('level', 1)->orderBy('id', 'DESC')->get();
        foreach($comments as $comment)
       {
           $comment->likeCount = $comment->likes->count();
           $comment->dislikeCount = $comment->dislikes->count();
           $comment->time = $comment->created_at->diffForHumans();
           $childComment = Comment::where('replied_to', $comment->id)->get()->first();

           //dd($childComment);
           if($childComment)
            $comment->hasChild = 1;
       }
      return  $comments;
    }


       public function getChildComments(Request $request)
    {
      $comments = Comment::with('creator.employee')->where('replied_to', $request->id)->orderBy('id', 'DESC')->get();
        foreach($comments as $comment)
       {
           $comment->likeCount = $comment->likes->count();
           $comment->dislikeCount = $comment->dislikes->count();
           $comment->time = $comment->created_at->diffForHumans();
           $childComment = Comment::where('replied_to', $comment->id)->get()->first();
         
           if($childComment)
            $comment->hasChild = 1;
       }
      return  $comments;
    }

       public function getComment(Request $request)
    {
       $comment = Comment::with('creator.employee')->with('creator.employee')->find($request->id);
       $comment->likeCount = $comment->likes->count();
       $comment->dislikeCount = $comment->dislikes->count();
       $comment->time = $comment->created_at->diffForHumans();
       return $comment;
    }
    
     public function updateLike(Request $request)
    {
       $uid = Auth::id();
      // $like = Like::firstOrCreate(['user_id' => $uid, 'content_id' => $request->id, 'content_type' => $request->model]);

       $like = Like::where('user_id', $uid)->where('content_id', $request->id)->where('content_type', $request->model)->get()->first();
       if($like)
          $like->delete();
       else
       {
        $like = new Like();
        $like->user_id = $uid;
        $like->content_id = $request->id;
        $like->content_type = $request->model;
        $like->state = $request->state;
        $like->save();
       }
       
       return json_encode(['status' => 'Updated']);
    }

    public function storePost(Request $request)
    {
      if (Auth::user()->canPost()) {
        $this->validate($request, [
            'title'          => 'required',
            'content'        => 'required',
            'publish'        => 'required',
         ]);

        $post = new Post();
        if(isset($request->id) && !empty($request->id))
          $post = Post::find($request->id);
        $post->title   = $request->title;
        $post->content = $request->content;
        $post->publish = $request->publish;
        $post->created_by = Auth::id();
        $post->save();

       return json_encode(['id' => $post->id]);
    }
  }


}
