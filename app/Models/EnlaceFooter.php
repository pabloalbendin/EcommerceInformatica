<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnlaceFooter extends Model
{
    protected $table = 'enlaces_footer';

    protected $fillable = [
        'titulo',
        'tipo_destino',
        'pagina_personalizada_id',
        'categoria_id',
        'url_personalizada',
        'columna',
        'orden',
        'nueva_pestana',
    ];

    protected $casts = [
        'nueva_pestana' => 'boolean',
    ];

    public function paginaPersonalizada()
    {
        return $this->belongsTo(PaginaPersonalizada::class, 'pagina_personalizada_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
