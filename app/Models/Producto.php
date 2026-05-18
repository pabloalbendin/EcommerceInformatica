<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Producto extends Model
{

    use SoftDeletes;
    
    protected $table = 'productos';
   

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'stock',
        'tipo_componente',
        'specs',
    ];

    protected $casts = [
        'specs' => 'array',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function datospedidos()
    {
        return $this->hasMany(Datospedido::class, 'id_producto');
    }

    public function scopeVisibleEnTienda(Builder $query): Builder
    {
        return $query->whereHas('categoria', fn (Builder $categoriaQuery) => $categoriaQuery->visibleEnTienda());
    }

}
