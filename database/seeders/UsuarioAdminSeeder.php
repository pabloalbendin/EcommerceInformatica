<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::updateOrCreate(
            ['correo' => 'admin@admin.com'],
            [
                'nombre' => 'Administrador',
                'contrasena' => Hash::make('admin123'),
                'id_rol' => 2,
            ],
        );
    }
}
