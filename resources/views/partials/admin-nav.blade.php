<div class="admin-nav mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <p class="eyebrow mb-2">Panel de administración</p>
            <h2 class="h4 mb-0">@yield('admin_title', 'Backend')</h2>
        </div>

        <div class="admin-nav-links d-flex flex-wrap gap-2">
            <a href="{{ route('admin') }}" class="btn btn-sm {{ request()->routeIs('admin') ? 'btn-dark' : 'btn-outline-dark' }}">
                Resumen
            </a>
            <a href="{{ route('admin.productos') }}" class="btn btn-sm {{ request()->routeIs('admin.productos') || request()->routeIs('creacion') || request()->routeIs('edicion') ? 'btn-dark' : 'btn-outline-dark' }}">
                Productos
            </a>
            <a href="{{ route('admin.categorias') }}" class="btn btn-sm {{ request()->routeIs('admin.categorias') || request()->routeIs('admin.categorias.crear') || request()->routeIs('admin.categorias.editar') ? 'btn-dark' : 'btn-outline-dark' }}">
                Categorías
            </a>
            <a href="{{ route('admin.navegacion') }}" class="btn btn-sm {{ request()->routeIs('admin.navegacion') ? 'btn-dark' : 'btn-outline-dark' }}">
                Navegacion
            </a>
            <a href="{{ route('admin.paginas') }}" class="btn btn-sm {{ request()->routeIs('admin.paginas') ? 'btn-dark' : 'btn-outline-dark' }}">
                Paginas
            </a>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-sm {{ request()->routeIs('admin.usuarios') || request()->routeIs('admin.usuarios.crear') ? 'btn-dark' : 'btn-outline-dark' }}">
                Usuarios
            </a>
            <a href="{{ route('verAdmin') }}" class="btn btn-sm {{ request()->routeIs('verAdmin') ? 'btn-dark' : 'btn-outline-dark' }}">
                Pedidos
            </a>
        </div>
    </div>
</div>
