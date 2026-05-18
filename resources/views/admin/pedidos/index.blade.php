@extends('layouts.app')

<!-- Título de la página -->
@section('titulo', 'Gestión de pedidos')
@section('admin_title', 'Gestión de pedidos')

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')
    
    <!-- Encabezado principal -->
    <h1 class="h3 mb-4">Gestión de pedidos</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 admin-stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Total pedidos</p>
                    <h2 class="display-6 mb-0">{{ $totalPedidos }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 admin-stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Pendientes</p>
                    <h2 class="display-6 mb-0">{{ $pedidosPendientes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 admin-stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Completados</p>
                    <h2 class="display-6 mb-0">{{ $pedidosCompletados }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 admin-stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Facturación total</p>
                    <h2 class="display-6 mb-0">{{ number_format($facturacionTotal, 2) }} €</h2>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('verAdmin') }}" class="card card-body mb-3">
        <div class="row g-3">
            <div class="col-md-6 col-xl-4">
                <label for="q" class="form-label">Buscar</label>
                <input
                    type="text"
                    id="q"
                    name="q"
                    class="form-control"
                    placeholder="ID pedido, cliente o correo"
                    value="{{ request('q') }}"
                >
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->id }}" {{ (string) request('estado') === (string) $estado->id ? 'selected' : '' }}>
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="fecha_desde" class="form-label">Desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="fecha_hasta" class="form-label">Hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-6 col-xl-2">
                <label for="orden" class="form-label">Ordenar</label>
                <select name="orden" id="orden" class="form-select">
                    <option value="recientes" {{ request('orden', 'recientes') === 'recientes' ? 'selected' : '' }}>Más recientes</option>
                    <option value="antiguos" {{ request('orden') === 'antiguos' ? 'selected' : '' }}>Más antiguos</option>
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
            <a href="{{ route('verAdmin') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <!-- Tabla responsive con los pedidos -->
    <div class="table-shell">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $pedido->usuario->nombre }}</div>
                                <div class="small text-muted">{{ $pedido->usuario->correo }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($pedido->fecha)->format('d/m/Y') }}</td>
                            <td>
                                <ul class="mb-0 ps-3">
                                    @foreach($pedido->datosPedido as $dato)
                                        <li>
                                            {{ $dato->producto->nombre ?? 'Producto eliminado' }} x{{ $dato->cantidad }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <span class="badge-soft">{{ $pedido->estado->nombre }}</span>
                            </td>
                            <td class="fw-semibold">{{ number_format($pedido->datosPedido->sum(fn($d) => $d->precio * $d->cantidad), 2) }} €</td>
                            <td>
                                <form action="/admin/pedidos/editar/{{ $pedido->id }}" method="POST" class="d-flex gap-2 align-items-center">
                                    @csrf
                                    <select name="id_estado" class="form-select form-select-sm">
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado->id }}" {{ $pedido->id_estado == $estado->id ? 'selected' : '' }}>
                                                {{ $estado->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No hay pedidos que coincidan con la búsqueda o filtros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($pedidos->hasPages())
        <div class="mt-3">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>
@endsection
