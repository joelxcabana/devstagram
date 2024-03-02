<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function __construct()
    {
        // proteger este controlador , tienen que estar autenticado
        // except protege todo excepto los metodos del array
        $this->middleware('auth')->except(['show','index']);
    }

    public function index(User $user)
    {
      // buscar en post por el campo user_id
      $posts = Post::where('user_id',$user->id)->latest()->paginate(8);

       return view('dashboard',[
        'user'=> $user,
        'posts' => $posts
       ]);
    }

    public function create()
    {
       return view('posts.create');
    }

    public function store(Request $request)
    {
       $this->validate($request, [
        'titulo' => 'required|max:255',
        'descripcion' => 'required',
        'imagen' => 'required',
       ]);

       Post::create([
         'titulo' => $request->titulo,
         'descripcion' => $request->descripcion,
         'imagen' => $request->imagen,
         'user_id' => auth()->user()->id
       ]);

       return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post) 
    {
      return view('posts.show', [
         'post' => $post,
         'user' => $user
      ]);
    }

    public function destroy(Post $post)
    {

      // verifica para eliminar, si el usuario es el duenio del post
      $this->authorize('delete', $post);
      $post->delete();

      //eliminar la imagen
      $imagen_path = public_path('uploads/' . $post->imagen);

      if(File::exists($imagen_path)){
         unlink($imagen_path);
      }

      return redirect()->route('posts.index', auth()->user()->username);
    }
}
