@extends('layouts.app')

@section('titulo')
    {{ isset($categoria) ? 'Editar categoría' : 'Crear categoría' }}
@endsection

@section('admin_title')
    {{ isset($categoria) ? 'Editar categoría' : 'Crear categoría' }}
@endsection

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')

    <div class="auth-card">
        <h1 class="h3 mb-4">{{ isset($categoria) ? 'Editar categoría' : 'Crear categoría' }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($categoria) ? route('admin.categorias.actualizar', $categoria->id) : route('admin.categorias.guardar') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $categoria->descripcion ?? '') }}</textarea>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-primary">
                    {{ isset($categoria) ? 'Actualizar categoría' : 'Crear categoría' }}
                </button>
                <a href="{{ route('admin.categorias') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
