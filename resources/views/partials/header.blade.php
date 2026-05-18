<!-- Cabecera principal del sitio -->
<header class="main-header">
    @auth
        @if(auth()->user()->id_rol == 2)
            <div class="top-strip">
                <div class="container d-flex justify-content-between align-items-center py-2 small">
                    <span class="fw-semibold text-dark">Modo administración activado</span>
                    <a href="{{ route('admin') }}" class="btn btn-sm btn-dark">Acceder al panel de admin</a>
                </div>
            </div>
        @endif
    @endauth
    
    <nav class="navbar navbar-expand-lg site-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-mark">
                    <img src="{{ asset('imagenes/logo.png') }}" alt="BuyByte">
                </span>
                <span class="brand-copy">
                    <span>BuyByte</span>
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                    </li>

                    @forelse($categoriasMenu as $categoriaMenu)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categoria') && request()->route('id') == $categoriaMenu->id ? 'active' : '' }}" href="{{ route('categoria', $categoriaMenu->id) }}">
                                {{ $categoriaMenu->nombre }}
                            </a>
                        </li>
                    @empty
                        <li class="nav-item">
                            <span class="nav-link text-white-50">Sin categorías</span>
                        </li>
                    @endforelse
                </ul>

                <div class="nav-actions d-flex flex-column flex-lg-row align-items-lg-center gap-2 mt-3 mt-lg-0">
                    <a class="btn btn-outline-light btn-sm {{ request()->routeIs('configurador') ? 'active' : '' }}" href="{{ route('configurador') }}">Configurar PC</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('carrito') }}">Carrito</a>

                    @auth
                        <a href="{{ route('perfil') }}" class="btn btn-outline-info btn-sm">Perfil</a>

                        <form action="/logout" method="POST" class="d-flex">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                        <a href="{{ route('registro') }}" class="btn btn-primary btn-sm">Registro</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
