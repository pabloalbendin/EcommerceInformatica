@extends('layouts.app')

@section('titulo', 'Quienes somos | BuyByte')

@section('content')
<section class="container py-4">
    <div class="about-rebuild-hero">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="eyebrow">Proyecto academico</span>
                <p class="home-kicker">Una tienda online planteada como ejercicio completo de diseno, estructura y desarrollo.</p>
                <h1 class="about-rebuild-title">Asi nace BuyByte.</h1>
                <p class="about-rebuild-lead">
                    El proyecto se desarrolla dentro del grado superior de DAW y sirve para reunir en una sola web distintas piezas de un ecommerce: catalogo, usuarios, pedidos, carrito y una presentacion visual coherente.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="about-rebuild-scene">
                    <div class="about-scene-main">
                        <img src="{{ asset('imagenes/banner.png') }}" alt="Banner BuyByte" class="about-scene-image">
                    </div>
                    <div class="about-scene-person person-card-a">
                        <img src="{{ asset('imagenes/perfillogo.png') }}" alt="Perfil BuyByte">
                    </div>
                    <div class="about-scene-person person-card-b">
                        <img src="{{ asset('imagenes/logocamion.png') }}" alt="Servicio BuyByte">
                    </div>
                    <div class="about-scene-note">
                        <strong>Idea del proyecto</strong>
                        <p>Un ecommerce academico pensado para demostrar un trabajo completo de DAW.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container pb-4">
    <div class="about-story-grid">
        <section class="about-story-panel">
            <p class="home-side-label mb-2">Origen</p>
            <h2>Como nace BuyByte</h2>
            <p>
                BuyByte nace como una propuesta academica orientada a aplicar conocimientos de desarrollo web en un caso realista: una tienda online de informatica con catalogo, carrito, gestion de usuarios y panel de administracion.
            </p>
        </section>

        <section class="about-story-panel">
            <p class="home-side-label mb-2">Enfoque</p>
            <h2>Que puedes encontrar</h2>
            <p>
                La tienda esta enfocada a la venta de productos de informatica, incluyendo componentes, perifericos, accesorios y equipos pensados para usuarios que buscan una compra comoda y organizada.
            </p>
        </section>

        <section class="about-story-panel">
            <p class="home-side-label mb-2">Publico</p>
            <h2>A quien va dirigida</h2>
            <p>
                BuyByte esta pensada para personas interesadas en comprar productos de informatica a traves de una web sencilla, visual y facil de recorrer, tanto si buscan un componente concreto como si quieren comparar opciones.
            </p>
        </section>

        <section class="about-story-panel">
            <p class="home-side-label mb-2">Diferencia</p>
            <h2>Que la hace distinta</h2>
            <p>
                El proyecto apuesta por una web completamente personalizada, con una interfaz propia, un catalogo ordenado y una presentacion pensada para transmitir claridad y confianza.
            </p>
        </section>
    </div>
</section>

<section class="container pb-4">
    <div class="about-highlight-band">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="about-profile-card">
                    <div class="about-profile-avatar">
                        <img src="{{ asset('imagenes/perfillogo.png') }}" alt="Perfil">
                    </div>
                    <div>
                        <p class="home-side-label mb-2">Autor</p>
                        <h3 class="h4 mb-2">Pablo Albendin Cardona</h3>
                        <p class="section-copy mb-0">Responsable del desarrollo del proyecto academico dentro del grado superior de DAW.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-process-line">
                    <article>
                        <span>01</span>
                        <div>
                            <h3>Diseno de interfaz</h3>
                            <p>Organizacion visual del catalogo, fichas de producto y navegacion general.</p>
                        </div>
                    </article>
                    <article>
                        <span>02</span>
                        <div>
                            <h3>Logica de tienda</h3>
                            <p>Usuarios, carrito, pedidos y gestion del contenido desde el panel de administracion.</p>
                        </div>
                    </article>
                    <article>
                        <span>03</span>
                        <div>
                            <h3>Presentacion final</h3>
                            <p>Una tienda online academica con estructura completa y una identidad visual coherente.</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container pb-4">
    <section class="about-services-band">
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <p class="home-side-label mb-2">Servicios previstos</p>
                <h2 class="mb-3">Servicios planteados en la tienda</h2>
                <p class="section-copy mb-0">
                    Dentro del planteamiento del proyecto, BuyByte contempla servicios habituales de una tienda especializada como ayuda en la eleccion de productos, montaje de equipos, soporte al cliente y envios dentro de Espana.
                </p>
            </div>
            <div class="col-lg-5">
                <div class="about-service-list">
                    <div>Asesoramiento sobre productos y configuraciones</div>
                    <div>Montaje de equipos y preparacion de pedidos</div>
                    <div>Soporte y seguimiento de incidencias</div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
