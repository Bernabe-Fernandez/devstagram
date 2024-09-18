<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        return view('perfil/index');
    }

    public function store(Request $request)
    {
        // modificar el username del request
        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username' => ['required', 'unique:users,username,'.auth()->user()->id , 'min:3', 'max:20', 'not_in:twitter,editar-perfil']
        ]);

        if($request->imagen){
            $manager = new ImageManager(new Driver());
            $imagen = $request->file('imagen');

            //asignar un nombre unico a la imagen
            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            //guardar la imagen al servidor
            $imagenServidor = $manager->read($imagen);

            $imagenServidor->cover(1000, 1000);

            //mover imagen al servidor
            $imagenPath = public_path('perfiles'). '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }


        //guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
        $usuario->save();

        //redireccionar al usuario
        return redirect()->route('posts.index', $usuario->username);
    }
}
