@extends('layouts.app')

@section('titulo', 'Login')

@section('content')
<div class="container auth-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-card">
                <span class="eyebrow mb-3">Acceso</span>
                <h1 class="card-title mb-3">Iniciar sesión</h1>
                <p class="section-copy mb-4">Accede a tu cuenta para gestionar pedidos, perfil y compras pendientes.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-1">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}" required>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('contrasena') is-invalid @enderror" id="contrasena" name="contrasena" required>
                        @error('contrasena')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>

                <p class="mt-4 mb-0 text-center">
                    ¿No tienes cuenta? <a href="{{ route('registro') }}">Crea una aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
