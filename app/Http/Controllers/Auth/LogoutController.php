<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    //
    public function store()
    {
        // dd('Cerrando Sesión');

        // // helper para cerrar sesion
        auth()->logout();
        

        // //redireccionar a la ventana de login
        return redirect()->route('login');


    }
}
