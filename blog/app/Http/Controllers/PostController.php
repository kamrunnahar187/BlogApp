<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;



use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Post;
use App\User;
use App\Category;
use App\Like;
use App\Dislike;
use App\Comment;
use App\Profile;

class PostController extends Controller
{
  
    //
    public function post()
    {
    	$categories =Category::all();

      return view('posts.post',['categories' => $categories]);
    }


public function addPost(Request $request)
{

	//return $request->input('post_title');
  
    $this->validate($request, [
           'post_title' => 'required',
           'post_body' => 'required',
           'category_id' => 'required',
           'post_image' => 'required'
       ]);
      
       $posts =  new Post;
       $posts->post_title = $request->input('post_title');
       $posts ->user_id = Auth::user()->id;
       $posts->post_body = $request->input('post_body');
       $posts->category_id = $request->input('category_id');
       //print(Auth::user());
       //print_r($request->input());
       if(Input::hasFile('post_image')){
            $file = Input::file('post_image');
            $file->move(public_path().'/posts',$file->getClientOriginalName());
            $url = URL::to("/") . '/posts/'.$file->getClientOriginalName();
        }
       $posts->post_image = $url;
       $posts->save();
       return redirect('/home')->with('response', 'Post added successfully!');

}

 public function viewPost($post_id)
    {

        //return $post_id;
     $posts = DB::select('select * from posts where id = ?', [$post_id])  ; 
     //$posts =Post::where('id','=',$post_id)->get()  ;                     
     $categories =Category::all();
     
     $likePost = Post::find($post_id);
     $likeCtr =Like::where('post_id','=',$likePost->id)->count() ;  
     $dislikeCtr =Dislike::where('post_id','=',$likePost->id)->count() ;       
    
     //$comments = Comment::find($post_id);  
     $comments = DB::table('users')
                                    ->join('comments','users.id','=',
                                        'comments.user_id')
                                     ->join('posts','posts.id','=',
                                        'comments.post_id')
                                     ->select('users.name','comments.*')
                                     ->where(['posts.id' => $post_id])  
                                     ->get();  
     
  
     return view('posts.view',['posts' => $posts,'categories' => $categories,'likeCtr' => $likeCtr,'dislikeCtr' => $dislikeCtr,'comments' => $comments]);
    }

    public function viewCategory($category_id)
    {

        //return $post_id;
     //$posts = DB::select('select * from posts where id = ?', [$post_id])  ; 
     $posts =Post::where('category_id','=',$category_id)->get()  ;                     
     $categories =Category::all();
     //$posts = Post::paginate(2);
     
     return view('posts.view',['posts' => $posts,'categories' => $categories]);
    }     

    public function edit($post_id)
    {
    
      $posts = Post::find($post_id);  ; 
      $categories =Category::all();
      $category = Category::find($posts->category_id );
      //return $category;
      return view('posts.edit',['posts' => $posts,'category' =>$category, 'categories' => $categories]);
    }

public function editPost(Request $request,$post_id)
{

  //return $request->input('post_title');
  
    $this->validate($request, [
           'post_title' => 'required',
           'post_body' => 'required',
           'category_id' => 'required',
           'post_image' => 'required'
       ]);
      
       $posts =  new Post;
       $posts->post_title = $request->input('post_title');
       $posts ->user_id = Auth::user()->id;
       $posts->post_body = $request->input('post_body');
       $posts->category_id = $request->input('category_id');
       //print(Auth::user());
       //print_r($request->input());
       if(Input::hasFile('post_image')){
            $file = Input::file('post_image');
            $file->move(public_path().'/posts',$file->getClientOriginalName());
            $url = URL::to("/") . '/posts/'.$file->getClientOriginalName();
        }
       $posts->post_image = $url;

       $data = array(
           'post_title' => $posts->post_title,
           'post_body' => $posts->post_body,
           'user_id' => $posts->user_id,
           'category_id' => $posts->category_id,
           'post_image' => $posts->post_image
       );
       Post::where('id',$post_id)
       ->update($data);
       $posts->update();

       return redirect('/home')->with('response', 'Post updated successfully!');

}

public function deletePost($post_id)
  {
   $posts = Post::where('id',$post_id)
   ->delete(); 
      
      //return $category;
       return redirect('/home')->with('response', 'Post deleted successfully!');
  }
  
   public function like($id)
    {
      
      $loggedin_user = Auth::user()->id;
      $like_user = Like::where(['user_id' =>  $loggedin_user, 'post_id' => $id])->first();
      if(empty($like_user ->user_id)){
          $user_id = Auth::user()->id;
          $email = Auth::user()->email;
          $post_id = $id;
          $like = new Like;
          $like->user_id =  $user_id ;
          $like->email =  $email ;
          $like->post_id =  $post_id ;
          $like->save();
          return redirect("/view/{$id}");
      }
      else 
          return redirect("/view/{$id}");
     
      //return view('posts.edit',['posts' => $posts,'category' =>$category, 'categories' => $categories]);
    }  

    public function dislike($id)
    {
      
      $loggedin_user = Auth::user()->id;
      $dislike_user = Dislike::where(['user_id' =>  $loggedin_user, 'post_id' => $id])->first();
      if(empty($dislike_user ->user_id)){
          $user_id = Auth::user()->id;
          $email = Auth::user()->email;
          $post_id = $id;
          $dislike = new Dislike;
          $dislike->user_id =  $user_id ;
          $dislike->email =  $email ;
          $dislike->post_id =  $post_id ;
          $dislike->save();
          return redirect("/view/{$id}");
      }
      else 
          return redirect("/view/{$id}");
          //return $id;
     
      //return view('posts.edit',['posts' => $posts,'category' =>$category, 'categories' => $categories]);
    }  
public function comment(Request $request, $post_id)
  {
       
       $this->validate($request, [
           'comment' => 'required'
       ]);
       $comment = new Comment;
       $comment->comment = $request->input('comment');
       $comment->user_id= Auth::user()->id;
       $comment->post_id = $post_id;
       $comment->save();

       return redirect("/view/{$post_id}")->with('response', 'Comment added successfully!');
     }

     public function search(Request $request)
  {
       
       $this->validate($request, [
           'search' => 'required'
       ]);
       $user_id= Auth::user()->id;
       
      $profile = Profile::find($user_id);  ; 
      $keyword = $request->input('search');
      $posts =Post::where('post_title','LIKE','%'.$keyword.'%')->get()  ;      
      return view('posts.searchposts',['posts' => $posts,'profile' => $profile]);
 
       //return redirect("/view/{$post_id}")->with('response', 'Comment added successfully!');
     }

}
