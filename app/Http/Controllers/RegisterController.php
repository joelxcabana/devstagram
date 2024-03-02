<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index() {
        return view('auth.register');
    }

    public function store(Request $request){


        // parsear el username a slug
        $request->request->add(['username' => Str::slug($request->username)]);

        //validacion
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:30',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        // agregar a la base de datos
        User::create([
            'name' => $request->name,
            'username' => Str::slug($request->username),
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
        ]);

        // autenticar usuario
        auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        // redireccionr
        return redirect()->route('posts.index');
    }
}
