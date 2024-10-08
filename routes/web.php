<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\PreventBackHistory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ! SINTAXIS DE CLOSURE

// Route::get('/', function () {
//     return view('principal');
// });

// Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::get('/register', function () {
//     return view('auth.register');
// });

// ! 


//* SINTAXIS DE CONOTROLADOR  

// Route::get('/register', [RegisterController::class, 'index'])->name('register');
// Route::post('/register', [RegisterController::class, 'store'])->name('register');


// Route::get('/login', [LoginController::class, 'index'])->name('login');
// Route::post('/login', [LoginController::class, 'store'])->name('login');

// Route::post('/logout', [LogoutController::class, 'store'])->name('logout');


// Route::get('/muro', [PostController::class, 'index'])->name('posts.index');


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    Route::get('/', HomeController::class)->name('home');
    // Route::get('/muro', [PostController::class, 'index'])->name('posts.index');
    // Rutas para el perfil 
    Route::get('/editar-perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::post('/editar-perfil', [PerfilController::class, 'store'])->name('perfil.store');

    // Usamos el nombre del Model con Route Model Binding (Ruta asociada a un Modelo)
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store');

    Route::post('/{user:username}/posts/{post}', [ComentarioController::class, 'store'])->name('comentarios.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->name('posts.likes.store');
    Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->name('posts.likes.destroy');
});

// * EXCLUIMOS A posts.index y a posts.show para que gente externa pueda ver perfiles y ver publicaciones pero sin tener acceso a editar o eliminar

Route::get('/{user:username}/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/{user:username}', [PostController::class, 'index'])->name('posts.index');


Route::post('/{user:username}/follow', [FollowerController::class, 'store'])->name('users.follow');
Route::delete('/{user:username}/unfollow', [FollowerController::class, 'destroy'])->name('users.unfollow');

// Route::group(['middleware' => 'prevent-back-history'], function () {
//     Route::get('/muro', [PostController::class, 'index'])->name('posts.index');
// });
