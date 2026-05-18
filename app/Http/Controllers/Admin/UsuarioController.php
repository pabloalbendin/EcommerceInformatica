<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Muestra el listado de usuarios
    public function index()
    {
        $usuarios = Usuario::with(['rol', 'pedidos'])
            ->where('id_rol', '!=', 2)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    // Muestra el formulario para crear usuarios
    public function crearIndex()
    {
        $roles = Rol::orderBy('id')->get();

        return view('admin.usuarios.form', compact('roles'));
    }

    // Guarda un nuevo usuario
    public function crear(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'nombre' => 'required|string|max:255',
                'correo' => 'required|email|unique:usuarios,correo',
                'contrasena' => 'required|min:6',
                'id_rol' => 'required|exists:roles,id',
            ],
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'correo.required' => 'El correo es obligatorio.',
                'correo.email' => 'El correo no tiene un formato válido.',
                'correo.unique' => 'Ya existe un usuario con ese correo.',
                'contrasena.required' => 'La contraseña es obligatoria.',
                'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',
                'id_rol.required' => 'Debes seleccionar un rol.',
            ]
        );

        $datosValidados['contrasena'] = Hash::make($datosValidados['contrasena']);

        Usuario::create($datosValidados);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
    }

    // Elimina un usuario si no es el actual y no tiene pedidos
    public function delete(Request $request)
    {
        $usuario = Usuario::withCount('pedidos')->findOrFail($request->id);

        if ((int) $usuario->id_rol === 2) {
            return redirect()->route('admin.usuarios')->with('error', 'No puedes eliminar usuarios administradores desde esta sección.');
        }

        if ((int) $usuario->id === (int) Auth::id()) {
            return redirect()->route('admin.usuarios')->with('error', 'No puedes eliminar tu propio usuario administrador.');
        }

        if ($usuario->pedidos_count > 0) {
            return redirect()->route('admin.usuarios')->with('error', 'No puedes eliminar un usuario que tiene pedidos asociados.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
}
