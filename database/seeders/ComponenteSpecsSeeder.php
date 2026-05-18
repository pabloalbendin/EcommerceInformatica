<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ComponenteSpecsSeeder extends Seeder
{
    public function run(): void
    {
        $specsPorNombre = [
            'Procesador AMD Ryzen 5 7600' => [
                'socket' => 'am5',
                'tdp_w' => 65,
            ],
            'Placa Base B650 ATX' => [
                'socket' => 'am5',
                'ram_tipo' => 'ddr5',
                'form_factor' => 'atx',
            ],
            'Memoria RAM 32GB DDR5' => [
                'ram_tipo' => 'ddr5',
                'modules' => 2,
                'capacity_gb' => 32,
                'speed_mhz' => 6000,
            ],
            'Tarjeta Gráfica RTX 4060 8GB' => [
                'tdp_w' => 115,
                'length_mm' => 245,
            ],
            'SSD 1TB NVMe' => [
                'interface' => 'nvme',
                'capacity_gb' => 1000,
            ],
            'Fuente 750W 80 Plus Gold' => [
                'power_w' => 750,
            ],
            'Caja ATX Airflow' => [
                'supported_form_factors' => ['atx', 'matx', 'itx'],
                'max_gpu_length_mm' => 330,
                'max_cooler_height_mm' => 165,
            ],
            'Disipador Torre 120mm' => [
                'supported_sockets' => ['am5', 'lga1700'],
                'height_mm' => 155,
            ],
        ];

        foreach ($specsPorNombre as $nombre => $specs) {
            $producto = Producto::where('nombre', $nombre)->first();
            if (!$producto) {
                continue;
            }

            $producto->specs = $specs;
            $producto->save();
        }
    }
}
