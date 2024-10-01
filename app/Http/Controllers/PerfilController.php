<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index');
    }


    public function store(Request $request)
    {

        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username' => [
                'required',
                'unique:users,username,' . auth()->user()->id,
                'min:6',
                'max:20',
                'not_in:twitter,editar-perfil'
            ],
            'email' => ['required', 'email', 'max:60']
        ]);

        $usuario = User::find(auth()->user()->id);

        if ($request->imagen) {
            $imagen = $request->file('imagen');
            // $input = $request->all();

            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            $imagenServidor = Image::read($imagen);

            $imagenServidor->resize(1000, 1000);

            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;

            $imagenServidor->save($imagenPath);

            // Si ya existe una imagen anterior, borrarla
            if ($usuario->imagen && file_exists(public_path('perfiles/' . $usuario->imagen))) {
                unlink(public_path('perfiles/' . $usuario->imagen));
            }

            // Asignar la nueva imagen al usuario
            $usuario->imagen = $nombreImagen;
        }

        // GUARDAR CAMBIOS
        $usuario->username = $request->username;
        $usuario->email = $request->email;

        if ($usuario->isDirty(['username', 'email', 'imagen'])) {

            // Guardar solo si hay cambios
            $usuario->save();
            return redirect()->route('posts.index', $usuario->username);
        }

        // Si no hubo cambios, redirigir sin guardar
        return redirect()->route('posts.index', $usuario->username);
    }
}
