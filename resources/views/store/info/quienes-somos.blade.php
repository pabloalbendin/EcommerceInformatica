@extends('layouts.app')

@section('titulo', 'Quienes somos | BuyByte')

@section('content')
<section class="container py-4">
    <div class="auth-card">
        <p class="home-side-label mb-2">Quienes somos</p>
        <h1 class="h2 mb-3">BuyByte es un proyecto academico de tienda online de informatica.</h1>
        <p class="section-copy mb-0">
            La web se ha desarrollado como parte del grado superior de DAW con el objetivo de reunir en un mismo proyecto distintas funciones habituales de un ecommerce: catalogo, carrito, usuarios, pedidos y panel de administracion.
        </p>
    </div>
</section>

<section class="container pb-4">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="about-story-panel h-100">
                <h2 class="h5 mb-3">Que ofrece</h2>
                <p class="mb-0">
                    BuyByte esta centrada en productos de informatica como componentes, perifericos y accesorios, con una estructura pensada para que la navegacion sea clara y sencilla.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="about-story-panel h-100">
                <h2 class="h5 mb-3">Para quien esta hecha</h2>
                <p class="mb-0">
                    Esta dirigida a usuarios que quieren consultar productos, comparar opciones y recorrer una tienda online organizada de forma comoda.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="about-story-panel h-100">
                <h2 class="h5 mb-3">Quien la desarrolla</h2>
                <p class="mb-0">
                    El proyecto ha sido realizado por Pablo Albendin Cardona como trabajo academico, cuidando tanto la parte visual como la logica principal de la tienda.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="container pb-5">
    <div class="about-services-band px-1 px-md-2">
        <h2 class="h4 mb-4">Servicios planteados</h2>
        <p class="section-copy mb-4">
            Dentro del planteamiento del proyecto, la tienda contempla funciones y servicios habituales de un comercio especializado.
        </p>
        <ul class="mb-0 ps-4 lh-lg">
            <li class="mb-3">Asesoramiento sobre productos y configuraciones.</li>
            <li class="mb-3">Montaje de equipos y preparacion de pedidos.</li>
            <li>Soporte basico para dudas e incidencias.</li>
        </ul>
    </div>
</section>
@endsection
