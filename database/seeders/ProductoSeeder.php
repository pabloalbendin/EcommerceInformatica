<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $categoriasPorNombre = Categoria::pluck('id', 'nombre');

        $productos = [
            [
                'categoria' => 'Ordenadores',
                'nombre' => 'PC Sobremesa Gaming',
                'descripcion' => 'Ordenador potente para gaming y trabajo',
                'precio' => 999.99,
                'imagen' => null,
                'stock' => 5,
            ],
            [
                'categoria' => 'Portátiles',
                'nombre' => 'Portátil Lenovo ThinkPad',
                'descripcion' => 'Portátil profesional para oficina',
                'precio' => 749.99,
                'imagen' => null,
                'stock' => 10,
            ],
            [
                'categoria' => 'Monitores',
                'nombre' => 'Monitor LG 24"',
                'descripcion' => 'Monitor Full HD 24 pulgadas',
                'precio' => 179.99,
                'imagen' => null,
                'stock' => 8,
            ],
            [
                'categoria' => 'Teclados',
                'nombre' => 'Teclado Mecánico TKL RGB',
                'descripcion' => 'Teclado mecánico para gaming',
                'precio' => 89.99,
                'imagen' => null,
                'stock' => 15,
            ],
            [
                'categoria' => 'Ratones',
                'nombre' => 'Ratón Gaming 16000 DPI',
                'descripcion' => 'Ratón de precisión para juego y productividad',
                'precio' => 39.99,
                'imagen' => null,
                'stock' => 20,
            ],
            [
                'categoria' => 'Auriculares',
                'nombre' => 'Auriculares Gaming 7.1',
                'descripcion' => 'Auriculares con micrófono y sonido envolvente',
                'precio' => 69.99,
                'imagen' => null,
                'stock' => 14,
            ],
            [
                'categoria' => 'Componentes',
                'nombre' => 'Procesador AMD Ryzen 5 7600',
                'descripcion' => 'CPU de 6 núcleos para gaming y tareas exigentes',
                'precio' => 229.99,
                'imagen' => null,
                'stock' => 9,
                'tipo_componente' => 'cpu',
            ],
            [
                'categoria' => 'Componentes',
                'nombre' => 'Placa Base B650 ATX',
                'descripcion' => 'Placa base AM5 con conectividad moderna',
                'precio' => 179.99,
                'imagen' => null,
                'stock' => 10,
                'tipo_componente' => 'placa_base',
            ],
            [
                'categoria' => 'Componentes',
                'nombre' => 'Memoria RAM 32GB DDR5',
                'descripcion' => 'Kit de RAM DDR5 de alto rendimiento',
                'precio' => 129.99,
                'imagen' => null,
                'stock' => 13,
                'tipo_componente' => 'ram',
            ],
            [
                'categoria' => 'Componentes',
                'nombre' => 'Tarjeta Gráfica RTX 4060 8GB',
                'descripcion' => 'GPU para gaming en 1080p y 1440p',
                'precio' => 349.99,
                'imagen' => null,
                'stock' => 7,
                'tipo_componente' => 'gpu',
            ],
            [
                'categoria' => 'Componentes',
                'nombre' => 'SSD 1TB NVMe',
                'descripcion' => 'Disco SSD NVMe de alta velocidad',
                'precio' => 119.99,
                'imagen' => null,
                'stock' => 20,
                'tipo_componente' => 'almacenamiento',
            ],
            [
                'categoria' => 'Componentes',
                'nombre' => 'Fuente 750W 80 Plus Gold',
                'descripcion' => 'Fuente eficiente para equipos de alto rendimiento',
                'precio' => 99.99,
                'imagen' => null,
                'stock' => 11,
                'tipo_componente' => 'fuente',
            ],
            [
                'categoria' => 'Componentes',
                'nombre' => 'Caja ATX Airflow',
                'descripcion' => 'Chasis ATX con buen flujo de aire',
                'precio' => 89.99,
                'imagen' => null,
                'stock' => 10,
                'tipo_componente' => 'caja',
            ],
            [
                'categoria' => 'Componentes',
                'nombre' => 'Disipador Torre 120mm',
                'descripcion' => 'Sistema de refrigeración por aire silencioso',
                'precio' => 49.99,
                'imagen' => null,
                'stock' => 12,
                'tipo_componente' => 'refrigeracion',
            ],
            [
                'categoria' => 'Accesorios',
                'nombre' => 'Hub USB-C 6 en 1',
                'descripcion' => 'Adaptador multipuerto para ampliar conectividad',
                'precio' => 34.99,
                'imagen' => null,
                'stock' => 18,
            ],
        ];

        foreach ($productos as $productoData) {
            $idCategoria = $categoriasPorNombre[$productoData['categoria']] ?? null;

            if (!$idCategoria) {
                continue;
            }

            Producto::updateOrCreate(
                ['nombre' => $productoData['nombre']],
                [
                    'id_categoria' => $idCategoria,
                    'descripcion' => $productoData['descripcion'],
                    'precio' => $productoData['precio'],
                    'imagen' => $productoData['imagen'],
                    'stock' => $productoData['stock'],
                    'tipo_componente' => $productoData['tipo_componente'] ?? null,
                ]
            );
        }
    }
}
