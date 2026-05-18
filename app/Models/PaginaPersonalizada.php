<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaginaPersonalizada extends Model
{
    protected $table = 'paginas_personalizadas';

    protected $fillable = [
        'titulo',
        'slug',
        'contenido_html',
    ];
}
