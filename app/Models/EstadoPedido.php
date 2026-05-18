<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    protected $table = 'estado_pedidos';

    protected $fillable = [
        'nombre',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_estado');
    }

}
