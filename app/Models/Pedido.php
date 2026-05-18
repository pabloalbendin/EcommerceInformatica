<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'fecha',
        'id_usuario',
        'id_estado',
    ];

    public function estado()
    {
        return $this->belongsTo(EstadoPedido::class, 'id_estado');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function datospedido()
    {
        return $this->hasMany(DatosPedido::class, 'id_pedido');
    }
    
}
