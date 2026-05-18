<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('datos_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pedido')
                ->constrained('pedidos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('id_producto')
                ->constrained('productos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('cantidad');
            $table->decimal('precio', 8, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_pedidos');
    }
};
