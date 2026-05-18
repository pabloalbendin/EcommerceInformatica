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
        Schema::table('categorias', function (Blueprint $table) {
            $table->boolean('mostrar_en_menu')->default(false)->after('descripcion');
            $table->unsignedTinyInteger('orden_menu')->nullable()->after('mostrar_en_menu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn(['mostrar_en_menu', 'orden_menu']);
        });
    }
};
