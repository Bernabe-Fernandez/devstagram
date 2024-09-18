<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImagenController extends Controller
{
    //
    public function store(Request $request)
    {

        $manager = new ImageManager(new Driver());

        $imagen = $request->file('file');


        //asignar un nombre unico a la imagen
        $nombreImagen = Str::uuid() . "." . $imagen->extension();

        //guardar la imagen al servidor
        $imagenServidor = $manager->read($imagen);

        $imagenServidor->cover(1000, 1000);

        //mover imagen al servidor
        $imagenPath = public_path('uploads'). '/' . $nombreImagen;
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' => $nombreImagen]);
    }
}
