<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Categoria extends Model
{
    public const CATEGORIA_OCULTA_TIENDA = 'servicios';

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'mostrar_en_menu',
        'orden_menu',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria');
    }

    public function scopeVisibleEnTienda(Builder $query): Builder
    {
        return $query->whereRaw('LOWER(nombre) <> ?', [self::CATEGORIA_OCULTA_TIENDA]);
    }

    public function esOcultaEnTienda(): bool
    {
        return mb_strtolower(trim((string) $this->nombre)) === self::CATEGORIA_OCULTA_TIENDA;
    }
}
