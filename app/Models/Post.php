<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id',
    ];

    public function user()
    {

        return $this->belongsTo(User::class)->select('name', 'username');
    }


    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // EVITAR LIKES REPETIDOS
    public function checkLike(User $user){

        // posicionarse en la tabla de likes utilizando contains busca que la tabla contenga al usuario de este post
        return $this->likes->contains('user_id', $user->id);

    }
}
