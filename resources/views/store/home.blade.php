@extends('layouts.app')

@section('titulo', 'Tienda Informatica')

@section('hero')
<section class="container pt-4">
    <div class="home-hero-banner">
        <img src="{{ asset('imagenes/banner.png') }}" alt="Banner principal BuyByte" class="home-hero-banner-image">
        <div class="home-hero-banner-overlay">
            <div class="d-flex">
                <a href="{{ route('configurador') }}" class="btn btn-outline-light">Configurar PC</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="container py-5" id="productos-destacados">
    <div class="home-products-head home-block-head">
        <div>
            <p class="eyebrow mb-3">Catálogo</p>
            <h2 class="mb-2">Productos destacados</h2>
        </div>
        <p class="section-copy mb-0">Selección destacada del catálogo para encontrar rápido lo más relevante.</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($productosNuevos->take(8) as $producto)
            @include('partials.producto', ['producto' => $producto])
        @endforeach
    </div>
</section>

<section class="container pb-5">
    <div class="home-service-strip">
        <div class="home-block-head mb-4">
            <div>
                <p class="eyebrow mb-3">Confianza BuyByte</p>
                <h2 class="mb-2">Servicios pensados para tu compra</h2>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <article class="service-feature-item">
                    <img src="{{ asset('imagenes/creditologo.png') }}" alt="Pago seguro">
                    <div>
                        <h3>Pago seguro</h3>
                        <p>Proceso de pago protegido y claro en cada paso de la compra.</p>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="service-feature-item">
                    <img src="{{ asset('imagenes/logocamion.png') }}" alt="Envío rápido">
                    <div>
                        <h3>Envío rápido</h3>
                        <p>Preparación de pedidos eficiente para recibir tu compra cuanto antes.</p>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="service-feature-item">
                    <img src="{{ asset('imagenes/perfillogo.png') }}" alt="Atención al cliente 24h">
                    <div>
                        <h3>Atención al cliente 24h</h3>
                        <p>Soporte continuo para resolver dudas sobre productos y pedidos.</p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="container pb-5">
    <div class="home-reviews-inline">
        <div class="home-block-head mb-3 d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <p class="eyebrow mb-3">Google Reviews</p>
                <h2 class="mb-0">Lo que opinan nuestros clientes</h2>
            </div>
            <div class="reviews-controls">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="reviewsPrev" aria-label="Ver reseñas anteriores">←</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="reviewsNext" aria-label="Ver reseñas siguientes">→</button>
            </div>
        </div>

        @php
            $resenas = [
                ['estrellas' => '★★★★★', 'texto' => 'Muy buena experiencia. Todo claro desde el primer minuto y compra sin complicaciones.', 'autor' => 'Miguel R.'],
                ['estrellas' => '★★★★★', 'texto' => 'El catálogo está muy bien organizado y el proceso de pago fue rápido y sencillo.', 'autor' => 'Laura G.'],
                ['estrellas' => '★★★★☆', 'texto' => 'Muy buen diseño y navegación cómoda. Encontré periféricos en menos de dos minutos.', 'autor' => 'Daniel P.'],
                ['estrellas' => '★★★★★', 'texto' => 'Me encantó la parte de configurador, está todo bien explicado y se entiende fácil.', 'autor' => 'Andrea S.'],
                ['estrellas' => '★★★★★', 'texto' => 'Buena presentación de productos y filtros útiles. Se nota el cuidado en los detalles.', 'autor' => 'Iván C.'],
                ['estrellas' => '★★★★☆', 'texto' => 'Todo funciona fluido en móvil. El carrito y el resumen de compra están muy bien.', 'autor' => 'Patricia M.'],
                ['estrellas' => '★★★★★', 'texto' => 'Interfaz limpia y moderna. Es fácil comparar opciones y decidir qué comprar.', 'autor' => 'Javier T.'],
                ['estrellas' => '★★★★★', 'texto' => 'Atención visual excelente y navegación intuitiva. Muy buena sensación general.', 'autor' => 'Claudia V.'],
            ];
        @endphp

        <div class="reviews-scroller" id="reviewsScroller" data-base-count="{{ count($resenas) }}" aria-label="Reseñas de clientes">
            <div class="reviews-lane">
                @foreach (array_merge($resenas, $resenas, $resenas) as $resena)
                    <article class="review-inline-item">
                        <div class="review-stars">{{ $resena['estrellas'] }}</div>
                        <p>“{{ $resena['texto'] }}”</p>
                        <strong>{{ $resena['autor'] }}</strong>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        var scroller = document.getElementById('reviewsScroller');
        var btnPrev = document.getElementById('reviewsPrev');
        var btnNext = document.getElementById('reviewsNext');
        var lane = scroller ? scroller.querySelector('.reviews-lane') : null;
        if (!scroller || !lane) {
            return;
        }

        var originalCount = Number(scroller.dataset.baseCount || 0);
        if (!originalCount) {
            return;
        }

        var paused = false;
        var speed = 0.45;
        var isPointerDown = false;
        var startX = 0;
        var startScrollLeft = 0;
        var setWidth = 0;

        function computeSetWidth() {
            var items = lane.querySelectorAll('.review-inline-item');
            if (items.length < originalCount) {
                return scroller.scrollWidth / 3;
            }

            var width = 0;
            for (var i = 0; i < originalCount; i += 1) {
                width += items[i].offsetWidth;
            }

            var laneStyles = window.getComputedStyle(lane);
            var gap = parseFloat(laneStyles.gap || laneStyles.columnGap || '0') || 0;
            if (originalCount > 1) {
                width += gap * (originalCount - 1);
            }

            return width;
        }

        function normalizeLoop() {
            if (!setWidth) {
                return;
            }

            if (scroller.scrollLeft >= setWidth * 2) {
                scroller.scrollLeft -= setWidth;
            } else if (scroller.scrollLeft < setWidth) {
                scroller.scrollLeft += setWidth;
            }
        }

        function refreshMeasurements(restoreCenter) {
            setWidth = computeSetWidth();
            if (!setWidth) {
                return;
            }

            if (restoreCenter) {
                scroller.scrollLeft = setWidth;
            } else {
                normalizeLoop();
            }
        }

        function scrollStep() {
            var firstItem = scroller.querySelector('.review-inline-item');
            return firstItem ? firstItem.offsetWidth + 16 : 260;
        }

        function tick() {
            if (!paused) {
                scroller.scrollLeft += speed;
                normalizeLoop();
            }
            window.requestAnimationFrame(tick);
        }

        scroller.addEventListener('mouseenter', function () { paused = true; });
        scroller.addEventListener('mouseleave', function () { paused = false; });
        scroller.addEventListener('touchstart', function () { paused = true; }, { passive: true });
        scroller.addEventListener('touchend', function () { paused = false; }, { passive: true });

        scroller.addEventListener('mousedown', function (event) {
            isPointerDown = true;
            paused = true;
            startX = event.pageX - scroller.offsetLeft;
            startScrollLeft = scroller.scrollLeft;
            scroller.classList.add('is-dragging');
        });

        scroller.addEventListener('mousemove', function (event) {
            if (!isPointerDown) {
                return;
            }
            event.preventDefault();
            var x = event.pageX - scroller.offsetLeft;
            var walk = (x - startX) * 1.25;
            scroller.scrollLeft = startScrollLeft - walk;
            normalizeLoop();
        });

        ['mouseup', 'mouseleave'].forEach(function (eventName) {
            scroller.addEventListener(eventName, function () {
                isPointerDown = false;
                paused = false;
                scroller.classList.remove('is-dragging');
            });
        });

        if (btnPrev) {
            btnPrev.addEventListener('click', function () {
                paused = true;
                scroller.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
                setTimeout(function () {
                    normalizeLoop();
                    paused = false;
                }, 450);
            });
        }

        if (btnNext) {
            btnNext.addEventListener('click', function () {
                paused = true;
                scroller.scrollBy({ left: scrollStep(), behavior: 'smooth' });
                setTimeout(function () {
                    normalizeLoop();
                    paused = false;
                }, 450);
            });
        }

        window.addEventListener('resize', function () {
            refreshMeasurements(true);
        });

        refreshMeasurements(true);
        window.requestAnimationFrame(tick);
    })();
</script>
@endsection
