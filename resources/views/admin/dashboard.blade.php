@extends('layouts.app')

@section('titulo', 'Panel de administración')
@section('admin_title', 'Resumen general')

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 admin-stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Productos</p>
                    <h3 class="display-6 mb-2">{{ $totalProductos }}</h3>
                    <a href="{{ route('admin.productos') }}" class="btn btn-sm btn-outline-dark">Gestionar productos</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 admin-stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Categorías</p>
                    <h3 class="display-6 mb-2">{{ $totalCategorias }}</h3>
                    <a href="{{ route('admin.categorias') }}" class="btn btn-sm btn-outline-dark">Gestionar categorías</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 admin-stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Pendientes</p>
                    <h3 class="display-6 mb-2">{{ $pedidosPendientes }}</h3>
                    <p class="mb-0 text-muted">Pedidos pendientes de gestionar.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 admin-stat-card">
                <div class="card-body">
                    <p class="text-muted mb-2">Pedidos</p>
                    <h3 class="display-6 mb-2">{{ $totalPedidos }}</h3>
                    <a href="{{ route('verAdmin') }}" class="btn btn-sm btn-outline-dark">Ver pedidos</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card h-100 admin-section-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Gestión de catálogo</h3>
                    <p class="text-muted">Administra el inventario, crea productos nuevos y actualiza el stock desde una sección dedicada.</p>
                    <a href="{{ route('admin.productos') }}" class="btn btn-outline-dark">Ir a productos</a>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card h-100 admin-section-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Organización por categorías</h3>
                    <p class="text-muted">Crea, edita y elimina categorías para mantener el catálogo bien estructurado.</p>
                    <a href="{{ route('admin.categorias') }}" class="btn btn-outline-dark">Ir a categorías</a>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card h-100 admin-section-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Usuarios de la web</h3>
                    <p class="text-muted">Consulta usuarios registrados y crea nuevas cuentas desde el panel de administración.</p>
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-dark">Ir a usuarios</a>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card h-100 admin-section-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Seguimiento de pedidos</h3>
                    <p class="text-muted">Consulta pedidos, revisa su estado actual y actualízalos desde el panel de pedidos.</p>
                    <a href="{{ route('verAdmin') }}" class="btn btn-outline-dark">Ir a pedidos</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 admin-section-card">
        <div class="card-body">
            <h3 class="h5 mb-3">Usuarios registrados</h3>
            <p class="display-6 mb-2">{{ $totalUsuarios }}</p>
            <p class="mb-2 text-muted">Total de cuentas registradas actualmente en la tienda.</p>
            <p class="mb-0 text-muted">Administradores activos: {{ $totalAdmins }}</p>
        </div>
    </div>
</div>
@endsection
