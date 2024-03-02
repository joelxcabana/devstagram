<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // este invoc  este metodo se manda a llamar automaticamente
   public function __invoke()
   {
     //o btener a quiene seguimos
     $ids = auth()->user()->followings->pluck('id')->toArray();
     $posts = Post::whereIn('user_id', $ids)->latest()->paginate(20);

     return view('home',[
        'posts' => $posts
     ]);
   }
}
