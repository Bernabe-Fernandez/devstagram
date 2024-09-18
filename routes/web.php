<?php

use App\Models\Comentario;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Posts\PostsController;
use App\Http\Controllers\Posts\ImagenController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PerfilController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomeController::class)->name('home');


// rutas para el perfil
Route::get('/editar-perfil', [PerfilController::class, 'index'])->name('perfil.index');
Route::post('/editar-perfil', [PerfilController::class, 'store'])->name('perfil.store');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);


//rutas para login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

// rutas para cerrar sesion
Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

//rutas para el muro de post
Route::get('/post/create', [PostsController::class, 'create'])->name('post.create');
Route::post('/posts', [PostsController::class, 'store'])->name('posts.store');
Route::get('/{user:username}/posts/{post}', [PostsController::class, 'show'])->name('posts.show');
Route::delete('/posts/{post}', [PostsController::class, 'destroy'])->name('post.destroy');

// rutas para subir una posts
Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store');

//ruta para los comentarios
Route::post('/{user:username}/posts/{post}', [ComentarioController::class, 'store'])->name('comentarios.store');


Route::get('/{user:username}', [PostsController::class, 'index'])->name('posts.index');


// siguiendo usuario
Route::post('/{user:username}/follow', [FollowerController::class, 'store'])->name('users.follow');
Route::delete('/{user:username}/unfollow', [FollowerController::class, 'destroy'])->name('users.unfollow');