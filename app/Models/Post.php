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
        'user_id'
    ];

    public function user()
    {
        // belongsTo es cuando un post pertenece a un usuario
        // select es para seleccionar solo esos atributos
        return $this->belongsTo(User::class)->select(['name','username']);
    }

    public function comentarios()
    {
        // un post tiene muchos comentarios
        return $this->hasMany(Comentario::class);

    }

    public function likes()
    {
        // un post tiene muchos likes
       return $this->hasMany(Like::class);
    }

    // verificar si un usario le dio like al post
    public function checkLike(User $user)
    {
        return $this->likes->contains('user_id',$user->id);
    }
}
