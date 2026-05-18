<footer class="main-footer mt-auto">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-brand-head">
                    <img src="{{ asset('imagenes/logo.png') }}" alt="BuyByte" class="footer-logo">
                    <p class="mb-0 fw-semibold">BuyByte</p>
                </div>
                <p class="footer-note">{{ $footerDescripcion }}</p>
            </div>
            <div class="footer-columns footer-columns-{{ max(1, min(4, $footerColumnas)) }}">
                @if($footerEnlaces->isNotEmpty())
                    @for($columna = 1; $columna <= max(1, min(4, $footerColumnas)); $columna++)
                        @php
                            $enlacesColumna = $footerEnlaces->where('columna', $columna);
                        @endphp
                        @if($enlacesColumna->isNotEmpty())
                            <div class="footer-links">
                                @foreach($enlacesColumna as $enlaceFooter)
                                    @php
                                        $href = '#';

                                        if ($enlaceFooter->tipo_destino === 'pagina' && $enlaceFooter->paginaPersonalizada) {
                                            $href = url('/' . $enlaceFooter->paginaPersonalizada->slug);
                                        }

                                        if ($enlaceFooter->tipo_destino === 'categoria' && $enlaceFooter->categoria) {
                                            $href = route('categoria', $enlaceFooter->categoria->id);
                                        }

                                        if ($enlaceFooter->tipo_destino === 'url' && $enlaceFooter->url_personalizada) {
                                            $href = $enlaceFooter->url_personalizada;
                                        }
                                    @endphp
                                    <a href="{{ $href }}" @if($enlaceFooter->nueva_pestana) target="_blank" rel="noopener noreferrer" @endif>
                                        {{ $enlaceFooter->titulo }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endfor
                @else
                    <div class="footer-links">
                        <a href="{{ route('quienes-somos') }}">Quienes somos</a>
                        <a href="{{ route('contacto') }}">Contactanos</a>
                        <a href="{{ route('faq') }}">Preguntas frecuentes</a>
                    </div>
                    <div class="footer-links">
                        <a href="{{ route('cookies') }}">Politica de cookies</a>
                        <a href="{{ route('aviso-legal') }}">Aviso legal</a>
                        <a href="{{ route('privacidad') }}">Privacidad</a>
                    </div>
                @endif
            </div>
            <p class="mb-0 footer-copy">&copy; {{ now()->year }} BuyByte</p>
        </div>
    </div>
</footer>
