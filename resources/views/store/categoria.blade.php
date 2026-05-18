@extends('layouts.app')

@section('titulo', $categoria->nombre)

@section('content')
<div class="container py-4">
    <div class="row g-5 align-items-start">
        <div class="col-lg-3">
            <aside class="category-sidebar">
                <h2 class="h5 mb-3">Todas las categorias</h2>
                <nav class="category-sidebar-list">
                    @foreach ($todasLasCategorias as $categoriaLateral)
                        <a
                            href="{{ route('categoria', $categoriaLateral->id) }}"
                            class="category-sidebar-link {{ $categoriaLateral->id === $categoria->id ? 'active' : '' }}"
                        >
                            <span>{{ $categoriaLateral->nombre }}</span>
                            <span class="category-sidebar-count">{{ $categoriaLateral->productos_count }}</span>
                        </a>
                    @endforeach
                </nav>
            </aside>
        </div>

        <div class="col-lg-9">
            <h1 class="h2 text-center mb-4">{{ $categoria->nombre }}</h1>

            @php
                $hayFiltrosActivos = request()->filled('precio_min')
                    || request()->filled('precio_max')
                    || request()->filled('tipo')
                    || request('stock', 'todos') !== 'todos'
                    || request('orden', 'recientes') !== 'recientes';
            @endphp

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h4 mb-0">Productos de la categoria</h2>
                <button
                    class="btn btn-outline-primary"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#filtrosCategoria"
                    aria-expanded="{{ $hayFiltrosActivos ? 'true' : 'false' }}"
                    aria-controls="filtrosCategoria"
                >
                    {{ $hayFiltrosActivos ? 'Ocultar filtros' : 'Mostrar filtros' }}
                </button>
            </div>

            <div class="collapse {{ $hayFiltrosActivos ? 'show' : '' }}" id="filtrosCategoria">
                <div class="card card-body mb-4">
                    <form method="GET" action="{{ route('categoria', $categoria->id) }}">
                        <div class="row g-3">
                            <div class="col-md-6 col-xl-3">
                                <label for="precio_min" class="form-label">Precio mínimo</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="precio_min"
                                    name="precio_min"
                                    min="0"
                                    step="0.01"
                                    value="{{ request('precio_min') }}"
                                >
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <label for="precio_max" class="form-label">Precio máximo</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="precio_max"
                                    name="precio_max"
                                    min="0"
                                    step="0.01"
                                    value="{{ request('precio_max') }}"
                                >
                            </div>

                            @if(!empty($tiposComponente))
                                <div class="col-md-6 col-xl-3">
                                    <label for="tipo" class="form-label">Tipo</label>
                                    <select name="tipo" id="tipo" class="form-select">
                                        <option value="">Todos</option>
                                        @foreach ($tiposComponente as $valorTipo => $labelTipo)
                                            <option value="{{ $valorTipo }}" {{ request('tipo') === $valorTipo ? 'selected' : '' }}>
                                                {{ $labelTipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-6 col-xl-3">
                                <label for="stock" class="form-label">Stock</label>
                                <select name="stock" id="stock" class="form-select">
                                    <option value="todos" {{ request('stock', 'todos') === 'todos' ? 'selected' : '' }}>Todos</option>
                                    <option value="disponible" {{ request('stock') === 'disponible' ? 'selected' : '' }}>Solo disponibles</option>
                                    <option value="sin_stock" {{ request('stock') === 'sin_stock' ? 'selected' : '' }}>Sin stock</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-xl-3">
                                <label for="orden" class="form-label">Ordenar por</label>
                                <select name="orden" id="orden" class="form-select">
                                    <option value="recientes" {{ request('orden', 'recientes') === 'recientes' ? 'selected' : '' }}>Más recientes</option>
                                    <option value="precio_asc" {{ request('orden') === 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                                    <option value="precio_desc" {{ request('orden') === 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                                    <option value="nombre_asc" {{ request('orden') === 'nombre_asc' ? 'selected' : '' }}>Nombre A-Z</option>
                                    <option value="nombre_desc" {{ request('orden') === 'nombre_desc' ? 'selected' : '' }}>Nombre Z-A</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                            <a href="{{ route('categoria', $categoria->id) }}" class="btn btn-outline-secondary">Limpiar</a>
                        </div>
                    </form>
                </div>
            </div>

            @if($productos->isEmpty())
                <div class="alert alert-info empty-state">
                    {{ request()->query() ? 'No hay resultados para los filtros seleccionados.' : 'No hay productos disponibles en esta categoría por ahora.' }}
                </div>
            @else
                <div class="section-heading">
                    <p class="section-copy mb-0">
                        {{ $productos->count() }} resultado(s). Una selección organizada para encontrar antes lo que necesitas.
                    </p>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
                    @foreach ($productos as $producto)
                        @include('partials.producto', ['producto' => $producto])
                    @endforeach
                </div>

                <section class="category-description-block mt-4">
                    <h3 class="h5 mb-2">Sobre esta categoria</h3>
                    <p class="section-copy mb-0">
                        {{ $categoria->descripcion ?: 'Esta categoria todavia no tiene una descripcion detallada.' }}
                    </p>
                </section>
            @endif
        </div>
    </div>
</div>
@endsection
