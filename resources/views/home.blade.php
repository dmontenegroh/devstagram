@extends('layouts.app')

@section('titulo')
    Pagina Principal
@endsection



@section('contenido')
    <x-listar-post :posts="$posts" />

    {{-- <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($posts as $post)
            <div>
                <a href="{{ route('posts.show', ['post' => $post, 'user' => $post->user]) }}">
                    <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}">
                </a>
            </div>

        @empty
            <p>no hay posts</p>
        @endforelse
    </div> --}}
@endsection
