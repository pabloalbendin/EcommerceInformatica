<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoPedidosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estado_pedidos')->insert([
            ['nombre' => 'Pendiente'],
            ['nombre' => 'Confirmado'],
            ['nombre' => 'Cancelado'],
        ]);
    }
}
