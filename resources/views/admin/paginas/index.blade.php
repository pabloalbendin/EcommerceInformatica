@extends('layouts.app')

@section('titulo', 'Panel de administracion - Paginas')
@section('admin_title', 'Paginas y estilos')

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')

    <div class="admin-menu-editor mb-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <p class="eyebrow mb-3">CSS personalizado</p>
                <h1 class="h3 mb-3">Estilos globales del sitio</h1>
                <p class="section-copy mb-0">
                    Este CSS se inyecta en todas las paginas publicas para hacer ajustes visuales rapidos desde administracion.
                </p>
            </div>
            <div class="col-lg-8">
                <form action="{{ route('admin.paginas.css') }}" method="POST">
                    @csrf
                    <label for="css_personalizado" class="form-label">CSS personalizado</label>
                    <textarea name="css_personalizado" id="css_personalizado" rows="10" class="form-control font-monospace">{{ old('css_personalizado', $cssPersonalizado) }}</textarea>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Guardar CSS personalizado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-menu-editor mb-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <p class="eyebrow mb-3">Paginas personalizadas</p>
                <h2 class="h4 mb-3">{{ $paginaEditando ? 'Editar pagina' : 'Nueva pagina' }}</h2>
                <p class="section-copy mb-0">
                    Introduce el nombre de la pagina y su contenido en HTML. La URL se generara automaticamente a partir del titulo.
                </p>
            </div>
            <div class="col-lg-8">
                <form action="{{ $paginaEditando ? route('admin.paginas.actualizar', $paginaEditando->id) : route('admin.paginas.guardar') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label for="titulo" class="form-label">Titulo de la pagina</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $paginaEditando->titulo ?? '') }}" required>
                    </div>
                    <div class="col-12">
                        <label for="contenido_html" class="form-label">Contenido HTML</label>
                        <textarea name="contenido_html" id="contenido_html" rows="16" class="form-control font-monospace">{{ old('contenido_html', $paginaEditando->contenido_html ?? '') }}</textarea>
                    </div>
                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary">{{ $paginaEditando ? 'Actualizar pagina' : 'Crear pagina' }}</button>
                        @if($paginaEditando)
                            <a href="{{ route('admin.paginas') }}" class="btn btn-outline-secondary">Cancelar edicion</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-shell">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Titulo</th>
                        <th>URL</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginas as $pagina)
                        <tr>
                            <td class="fw-semibold">{{ $pagina->titulo }}</td>
                            <td>/{{ $pagina->slug }}</td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a href="{{ url('/' . $pagina->slug) }}" class="btn btn-sm btn-outline-dark" target="_blank" rel="noopener noreferrer">Ver</a>
                                <a href="{{ route('admin.paginas', ['editar_pagina' => $pagina->id]) }}" class="btn btn-sm btn-primary">Editar</a>
                                <form action="{{ route('admin.paginas.eliminar', $pagina->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Todavia no hay paginas personalizadas creadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
