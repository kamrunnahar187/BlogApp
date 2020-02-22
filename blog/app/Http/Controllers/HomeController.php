<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Profile;
use App\User;
use App\Post;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $user_id = Auth::user()->id;
       $profile = DB::table('users')
                                    ->join('profiles','users.id','=',
                                        'profiles.user_id')
                                     ->select('users.*','profiles.*')
                                     ->where(['profiles.user_id' => $user_id])  
                                     ->first();  
       // return $profile->profile_pic;

      $posts = Post::paginate(2);
      return view('home',['profile' => $profile, 'posts' => $posts]);
    }

}