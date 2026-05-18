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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('id_estado')
                ->constrained('estado_pedidos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
