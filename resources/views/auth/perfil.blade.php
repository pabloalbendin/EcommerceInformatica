@extends('layouts.app')

@section('titulo', 'Mi perfil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center g-4">
        <div class="col-lg-7">
            <div class="auth-card">
                <span class="eyebrow mb-3">Cuenta</span>
                <h1 class="h3 mb-4">Mi perfil</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('perfil.actualizar') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}">
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo', $usuario->correo) }}">
                    </div>

                    <hr class="my-4">

                    <p class="text-muted mb-3">Si no quieres cambiar la contraseña, deja estos campos vacíos.</p>

                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Nueva contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena">
                    </div>

                    <div class="mb-4">
                        <label for="contrasena_confirmation" class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" class="form-control" id="contrasena_confirmation" name="contrasena_confirmation">
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Volver</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="table-shell p-3 p-md-4">
                <span class="eyebrow mb-3">Pedidos</span>
                <h2 class="h4 mb-3">Seguimiento de pedidos</h2>
                <p class="text-muted mb-4">Aquí ves tus pedidos y el estado real actual de cada uno.</p>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Pedido</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado del pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pedidos as $pedido)
                                <tr>
                                    <td class="fw-semibold">#{{ $pedido->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($pedido->total, 2) }} €</td>
                                    <td><span class="badge-soft">{{ $pedido->estado->nombre ?? 'Sin estado' }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Todavía no tienes pedidos para seguir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
