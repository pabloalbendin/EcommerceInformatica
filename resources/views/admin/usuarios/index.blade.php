@extends('layouts.app')

@section('titulo', 'Panel de administración - Usuarios')
@section('admin_title', 'Gestión de usuarios')

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Usuarios de la web</h1>
        <a href="{{ route('admin.usuarios.crear') }}" class="btn btn-success">Crear nuevo usuario</a>
    </div>

    <div class="table-shell">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Pedidos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td class="fw-semibold">{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->correo }}</td>
                            <td>{{ $usuario->rol->rol ?? 'Sin rol' }}</td>
                            <td>{{ $usuario->pedidos->count() }}</td>
                            <td>
                                <form action="{{ route('admin.usuarios.delete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $usuario->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
