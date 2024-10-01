<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{

    // Proteger rutas autenticadas


    public function index(User $user)
    {

        $posts = Post::where('user_id', $user->id)->latest()->get();

        // Metodo para paginar

        // $posts = Post::where('user_id', $user->id)->paginate(1);
        // $posts = Post::where('user_id', $user->id)->simplePaginate(1);



        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function create()
    {

        return view('posts.create');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' =>  'required',
            'imagen' => 'required',
        ]);

        // Post::create([
        //     'titulo' => $request->titulo,
        //     'descripcion' => $request->descripcion,
        //     'imagen' => $request->imagen,
        //     'user_id' => auth()->user()->id
        // ]);

        // Otra forma de crear registros

        // $post = new Post();

        // $post->titulo = $request->titulo;
        // $post->descripcion = $request->descripcion;
        // $post->imagen = $request->imagen;
        // $post->user_id = auth()->user()->id;
        // $post->save();

        // TERCERA FORMA DE ALMACENAR DATOS, METODO RELACION

        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id
        ]);


        return redirect()->route('posts.index', auth()->user()->username);
    }


    public function show(User $user, Post $post)
    {
        // Verificar que el $user en la url sea el dueño de $post a mostrar.
        if ($user->id != $post->user_id) {
            return redirect()->route('posts.index', auth()->user()->username);
        }

        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]);
    }

    public function destroy(Post $post)
    {
        // if ($post->user_id === auth()->user()->id) {
        //     dd('Si es la misma persona');
        // } else {
        //     dd('no es la misma persona');
        // }

        // HACEMOS LO MISMO DE ARRIBA CON POLICY 
        $this->authorize('delete', $post);
        $post->delete();


        // Eliminar la imagen 

        $imagen_path = public_path('uploads/' . $post->imagen);

        if (File::exists($imagen_path)) {
            unlink($imagen_path);
        }

        return redirect()->route('posts.index', auth()->user()->username);
    }
}
