<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //

    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // dd($request);
        // dd($request->get('name'));

        // modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);

        // Validación con laravel
        $this->validate($request, [
            //reglas de validación
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:70',
            'password' => 'required|confirmed|min:6'
        ]);

        // dd('Creando usuario...');

        // llamar al modelo y usar el metodo create para insertar en la bd
        User::create([
            //definir los campos que se van a crear en el usuario
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        //autenticar al usuario
        // auth()->attempt([
        //     'email' => $request->email,
        //     'password' => $request->password,
        // ]);

        //otra forma de autenticar
        auth()->attempt($request->only('email', 'password'));


        // redireccionar al usuario
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
