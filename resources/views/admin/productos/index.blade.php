@extends('layouts.app')

<!-- Título de la página -->
@section('titulo', 'Panel de administración - Productos')
@section('admin_title', 'Gestión de productos')

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')
    
    <!-- Encabezado con título y botón para crear un producto -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Gestión de productos</h1>
        <a href="/admin/crear" class="btn btn-success">Crear nuevo producto</a>
    </div>

    <form method="GET" action="{{ route('admin.productos') }}" class="card card-body mb-3">
        <div class="row g-3">
            <div class="col-md-6 col-xl-3">
                <label for="q" class="form-label">Buscar</label>
                <input
                    type="text"
                    id="q"
                    name="q"
                    class="form-control"
                    placeholder="Nombre, categoría o ID"
                    value="{{ request('q') }}"
                >
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="categoria" class="form-label">Categoría</label>
                <select name="categoria" id="categoria" class="form-select">
                    <option value="">Todas</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ (string) request('categoria') === (string) $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="stock" class="form-label">Stock</label>
                <select name="stock" id="stock" class="form-select">
                    <option value="todos" {{ request('stock', 'todos') === 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="disponible" {{ request('stock') === 'disponible' ? 'selected' : '' }}>Con stock</option>
                    <option value="sin_stock" {{ request('stock') === 'sin_stock' ? 'selected' : '' }}>Sin stock</option>
                    <option value="bajo_stock" {{ request('stock') === 'bajo_stock' ? 'selected' : '' }}>Bajo stock (1-5)</option>
                </select>
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="precio_min" class="form-label">Precio mín.</label>
                <input type="number" id="precio_min" name="precio_min" class="form-control" min="0" step="0.01" value="{{ request('precio_min') }}">
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="precio_max" class="form-label">Precio máx.</label>
                <input type="number" id="precio_max" name="precio_max" class="form-control" min="0" step="0.01" value="{{ request('precio_max') }}">
            </div>
            <div class="col-md-6 col-xl-3">
                <label for="orden" class="form-label">Ordenar</label>
                <select name="orden" id="orden" class="form-select">
                    <option value="recientes" {{ request('orden', 'recientes') === 'recientes' ? 'selected' : '' }}>Más recientes</option>
                    <option value="nombre_asc" {{ request('orden') === 'nombre_asc' ? 'selected' : '' }}>Nombre A-Z</option>
                    <option value="nombre_desc" {{ request('orden') === 'nombre_desc' ? 'selected' : '' }}>Nombre Z-A</option>
                    <option value="precio_asc" {{ request('orden') === 'precio_asc' ? 'selected' : '' }}>Precio ascendente</option>
                    <option value="precio_desc" {{ request('orden') === 'precio_desc' ? 'selected' : '' }}>Precio descendente</option>
                    <option value="stock_asc" {{ request('orden') === 'stock_asc' ? 'selected' : '' }}>Stock ascendente</option>
                    <option value="stock_desc" {{ request('orden') === 'stock_desc' ? 'selected' : '' }}>Stock descendente</option>
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
            <a href="{{ route('admin.productos') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <div class="table-shell">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td class="fw-semibold">{{ $producto->nombre }}</td>
                            <td>{{ $producto->categoria->nombre }}</td>
                            <td>{{ number_format($producto->precio, 2) }} €</td>
                            <td>{{ $producto->stock }}</td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a href="/admin/editar/{{ $producto->id }}" class="btn btn-sm btn-primary">Editar</a>
                                <form action="/admin/delete" method="POST" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $producto->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No hay productos que coincidan con la búsqueda o filtros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($productos->hasPages())
        <div class="mt-3">
            {{ $productos->links() }}
        </div>
    @endif
</div>
@endsection
