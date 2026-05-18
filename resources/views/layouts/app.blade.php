<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('titulo')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
        @if (!empty($cssPersonalizado))
            <style>
                {!! $cssPersonalizado !!}
            </style>
        @endif
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="app-body d-flex flex-column min-vh-100">

        @include('partials.header')
        
        @yield('hero')

        <main class="main-shell flex-grow-1">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('auth_notice'))
                    <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
                        <p class="mb-2">{{ session('auth_notice') }}</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Iniciar sesión</a>
                            <a href="{{ route('registro') }}" class="btn btn-sm btn-outline-primary">Registrarse</a>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            @yield('content')
        </main>

        @include('partials.footer')

        <script src="{{ asset('js/form-validations.js') }}"></script>
    </body>
</html>
