<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
       $this->validate($request,[
        'email' => 'required||email',
        'password' => 'required'
       ]);

       // valida si el usuario existe
       if(!auth()->attempt($request->only('email','password'), $request->remember)){

        // crea una variable con le texto para mostrarlo en la vista con session('message')
        // back vuelve a la pagina anterior
        return back()->with('message','Credenciales Incorrectas');
       }

       // redirecciona 
       return redirect()->route('posts.index', auth()->user()->username);
    }
}
