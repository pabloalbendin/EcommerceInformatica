<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    // Muestra el perfil del usuario autenticado
    public function index()
    {
        $usuario = Auth::user();
        $pedidos = Pedido::with(['estado', 'datospedido'])
            ->where('id_usuario', $usuario->id)
            ->orderByDesc('fecha')
            ->get()
            ->map(function ($pedido) {
                $pedido->total = $pedido->datospedido->sum(fn ($linea) => $linea->precio * $linea->cantidad);

                return $pedido;
            });

        return view('auth.perfil', compact('usuario', 'pedidos'));
    }

    // Actualiza los datos del perfil propio
    public function actualizar(Request $request)
    {
        $usuario = Auth::user();

        $datosValidados = $request->validate(
            [
                'nombre' => 'required|string|max:255',
                'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
                'contrasena' => 'nullable|min:6|confirmed',
            ],
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'correo.required' => 'El correo es obligatorio.',
                'correo.email' => 'El correo no tiene un formato válido.',
                'correo.unique' => 'Ese correo ya está siendo utilizado por otro usuario.',
                'contrasena.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
                'contrasena.confirmed' => 'La confirmación de la contraseña no coincide.',
            ]
        );

        $usuario->nombre = $datosValidados['nombre'];
        $usuario->correo = $datosValidados['correo'];

        if (!empty($datosValidados['contrasena'])) {
            $usuario->contrasena = Hash::make($datosValidados['contrasena']);
        }

        $usuario->save();

        return redirect()->route('perfil')->with('success', 'Perfil actualizado correctamente.');
    }
}
