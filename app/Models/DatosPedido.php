<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosPedido extends Model
{
    protected $table = 'datos_pedido';

    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad',
        'precio',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

}
