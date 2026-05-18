<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('tipo_componente', 40)->nullable()->after('id_categoria');
            $table->index('tipo_componente');
        });

        $categoriaComponentesId = DB::table('categorias')
            ->whereRaw('LOWER(nombre) = ?', ['componentes'])
            ->value('id');

        if (!$categoriaComponentesId) {
            return;
        }

        $productos = DB::table('productos')
            ->where('id_categoria', $categoriaComponentesId)
            ->select('id', 'nombre', 'descripcion')
            ->get();

        foreach ($productos as $producto) {
            $texto = Str::lower(($producto->nombre ?? '') . ' ' . ($producto->descripcion ?? ''));
            $tipo = null;

            if (Str::contains($texto, ['cpu', 'procesador', 'ryzen', 'intel core'])) {
                $tipo = 'cpu';
            } elseif (Str::contains($texto, ['placa', 'motherboard', 'chipset'])) {
                $tipo = 'placa_base';
            } elseif (Str::contains($texto, ['ram', 'ddr4', 'ddr5'])) {
                $tipo = 'ram';
            } elseif (Str::contains($texto, ['grafica', 'gráfica', 'gpu', 'rtx', 'radeon'])) {
                $tipo = 'gpu';
            } elseif (Str::contains($texto, ['ssd', 'hdd', 'nvme', 'm.2'])) {
                $tipo = 'almacenamiento';
            } elseif (Str::contains($texto, ['fuente', 'psu', '80 plus', 'watt'])) {
                $tipo = 'fuente';
            } elseif (Str::contains($texto, ['caja', 'chasis', 'tower', 'gabinete'])) {
                $tipo = 'caja';
            } elseif (Str::contains($texto, ['refrigeracion', 'refrigeración', 'cooler', 'disipador'])) {
                $tipo = 'refrigeracion';
            }

            if ($tipo) {
                DB::table('productos')
                    ->where('id', $producto->id)
                    ->update(['tipo_componente' => $tipo]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropIndex(['tipo_componente']);
            $table->dropColumn('tipo_componente');
        });
    }
};
