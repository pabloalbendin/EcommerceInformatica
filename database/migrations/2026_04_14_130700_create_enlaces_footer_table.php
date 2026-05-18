<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enlaces_footer', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('tipo_destino');
            $table->foreignId('pagina_personalizada_id')->nullable()->constrained('paginas_personalizadas')->nullOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->string('url_personalizada')->nullable();
            $table->unsignedTinyInteger('columna')->default(1);
            $table->unsignedSmallInteger('orden')->default(1);
            $table->boolean('nueva_pestana')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enlaces_footer');
    }
};
