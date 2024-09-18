<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostsController extends Controller
{
    //funcion para validar el usuario
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }


    //funcion para mostrar la vista predeterminada
    public function index(User $user)
    {

        //hacemos la consulta a la bd
        //guadamos los datos en un variable, dentro de la consulta colocamos que y como queremos ver los datos
        $posts = Post::where('user_id', $user->id)->latest()->paginate(12);

        // dd($posts);

        // dd($user->username);
        return view('dashboard', [
            //devemos regresae los datos a la vista, para que se puedan mostrar
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    //funcion para abrir el formulario para craer un registro
    public function create()
    {
        // dd('Creando Post..');
        return view('posts.create');
    }

    //funcion que almacena en la bd
    public function store(Request $request)
    {
        // dd('Creando post...');
        $this->validate($request, [
            //reglas de validación
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        //crear un registro en la base de datos
        // Post::create([
        //     'titulo' => $request->titulo,
        //     'descripcion' => $request->descripcion,
        //     'imagen' => $request->imagen,
        //     'user_id' => auth()->user()->id
        // ]);

        //otra forma de crear registros
        // $post = new Post;

        // $post->titulo = $request->titulo;
        // $post->descripcion = $request->descripcion;
        // $post->imagen = $request->imagen;
        // $post->user_id = auth()->user()->id;
        // $post->save();

        //guardar registros con relacion
        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('posts.index', auth()->user()->username);
    }

    //funcion para mostrar unicamente una publicación
    public function show(User $user,Post $post){
        return view('posts.show', [
            'post' => $post,
            'user' => $user,
        ]);
    }

    public function destroy(Post $post)
    {
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
