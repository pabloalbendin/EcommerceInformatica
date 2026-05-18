<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Ordenadores', 'descripcion' => 'Equipos de sobremesa preparados para trabajo, gaming y uso diario.'],
            ['nombre' => 'Portátiles', 'descripcion' => 'Portátiles para estudiar, trabajar o disfrutar de un equipo potente en movilidad.'],
            ['nombre' => 'Monitores', 'descripcion' => 'Pantallas para oficina, diseño y juego con distintas resoluciones y tamaños.'],
            ['nombre' => 'Periféricos', 'descripcion' => 'Dispositivos de entrada y control para completar tu setup.'],
            ['nombre' => 'Teclados', 'descripcion' => 'Teclados mecánicos y de membrana para productividad y gaming.'],
            ['nombre' => 'Ratones', 'descripcion' => 'Ratones ergonómicos y gaming con distintos sensores y diseños.'],
            ['nombre' => 'Auriculares', 'descripcion' => 'Auriculares para juego, música y comunicación.'],
            ['nombre' => 'Componentes', 'descripcion' => 'Piezas de hardware para montar, mejorar o reparar tu ordenador.'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Complementos y pequeños dispositivos para sacar más partido a tus equipos.'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::updateOrCreate(
                ['nombre' => $categoria['nombre']],
                ['descripcion' => $categoria['descripcion']]
            );
        }
    }
}
