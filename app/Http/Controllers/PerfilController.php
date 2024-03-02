<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        $request->request->add(['username' => Str::slug($request->username)]);
        
        $this->validate($request, [
            'username' => ['required','unique:users,username,'.auth()->user()->id,'min:3','max:30']
        ]);

        if($request->imagen){
            $imagen = $request->imagen;

            $nombreImagen = Str::uuid() . "." . $imagen->extension();
            
            $manager = new ImageManager(new Driver());
            $imagenServidor = $manager::imagick()->read($imagen);
            $imagenServidor->resizeDown(1000, 1000);
     
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            
            $imagenServidor->toPng()->save($imagenPath);
        }

        $usuario = User::find(auth()->user()->id);

        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';

        $usuario->save();

        return redirect()->route('posts.index', $usuario->username);
    }
}
