@extends('layouts.app')

@section('titulo', 'Panel de administración - Categorías')
@section('admin_title', 'Gestión de categorías')

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Gestión de categorías</h1>
        <a href="{{ route('admin.categorias.crear') }}" class="btn btn-success">Crear nueva categoría</a>
    </div>

    <form method="GET" action="{{ route('admin.categorias') }}" class="card card-body mb-3">
        <div class="row g-3">
            <div class="col-md-6 col-xl-4">
                <label for="q" class="form-label">Buscar</label>
                <input
                    type="text"
                    id="q"
                    name="q"
                    class="form-control"
                    placeholder="Nombre, descripción o ID"
                    value="{{ request('q') }}"
                >
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="menu" class="form-label">Menú principal</label>
                <select name="menu" id="menu" class="form-select">
                    <option value="todos" {{ request('menu', 'todos') === 'todos' ? 'selected' : '' }}>Todas</option>
                    <option value="en_menu" {{ request('menu') === 'en_menu' ? 'selected' : '' }}>En menú</option>
                    <option value="fuera_menu" {{ request('menu') === 'fuera_menu' ? 'selected' : '' }}>Fuera de menú</option>
                </select>
            </div>
            <div class="col-md-6 col-xl-3">
                <label for="productos" class="form-label">Productos asociados</label>
                <select name="productos" id="productos" class="form-select">
                    <option value="todos" {{ request('productos', 'todos') === 'todos' ? 'selected' : '' }}>Todas</option>
                    <option value="con_productos" {{ request('productos') === 'con_productos' ? 'selected' : '' }}>Con productos</option>
                    <option value="sin_productos" {{ request('productos') === 'sin_productos' ? 'selected' : '' }}>Sin productos</option>
                </select>
            </div>
            <div class="col-md-6 col-xl-3">
                <label for="orden" class="form-label">Ordenar</label>
                <select name="orden" id="orden" class="form-select">
                    <option value="default" {{ request('orden', 'default') === 'default' ? 'selected' : '' }}>Orden recomendado</option>
                    <option value="nombre_asc" {{ request('orden') === 'nombre_asc' ? 'selected' : '' }}>Nombre A-Z</option>
                    <option value="nombre_desc" {{ request('orden') === 'nombre_desc' ? 'selected' : '' }}>Nombre Z-A</option>
                    <option value="productos_desc" {{ request('orden') === 'productos_desc' ? 'selected' : '' }}>Más productos</option>
                    <option value="productos_asc" {{ request('orden') === 'productos_asc' ? 'selected' : '' }}>Menos productos</option>
                </select>
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="per_page" class="form-label">Mostrar</label>
                <select name="per_page" id="per_page" class="form-select">
                    <option value="10" {{ request('per_page', '10') === '10' ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('per_page') === '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') === '100' ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Buscar y filtrar</button>
            <a href="{{ route('admin.categorias') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <div class="table-shell">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Productos asociados</th>
                        <th>Menu principal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td class="fw-semibold">{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->descripcion ?: 'Sin descripción' }}</td>
                            <td>{{ $categoria->productos_count }}</td>
                            <td>
                                @if($categoria->mostrar_en_menu)
                                    <span class="badge-soft">Si, posicion {{ $categoria->orden_menu }}</span>
                                @else
                                    <span class="text-muted">No visible</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.categorias.editar', $categoria->id) }}" class="btn btn-sm btn-primary">Editar</a>
                                <form action="{{ route('admin.categorias.delete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $categoria->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Todavía no hay categorías creadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($categorias->hasPages())
        <div class="mt-3">
            {{ $categorias->links() }}
        </div>
    @endif
</div>
@endsection
