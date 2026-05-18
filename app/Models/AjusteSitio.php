<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AjusteSitio extends Model
{
    protected $table = 'ajustes_sitio';

    protected $fillable = [
        'clave',
        'valor',
    ];

    public static function obtener(string $clave, ?string $valorPorDefecto = null): ?string
    {
        return static::where('clave', $clave)->value('valor') ?? $valorPorDefecto;
    }

    public static function guardar(string $clave, ?string $valor): void
    {
        static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor]
        );
    }
}
