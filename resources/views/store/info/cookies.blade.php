@extends('layouts.app')

@section('titulo', 'Politica de cookies | BuyByte')

@section('content')
<section class="container py-4">
    <div class="info-header">
        <p class="eyebrow mb-3">Informacion legal</p>
        <h1 class="info-title">Politica de cookies</h1>
        <p class="info-lead mb-0">
            Esta politica explica el uso actual de cookies en BuyByte, proyecto academico de ecommerce desarrollado por Pablo Albendin Cardona.
        </p>
    </div>
</section>

<section class="container pb-4">
    <div class="legal-content">
        <section class="content-section">
            <h2>Que son las cookies</h2>
            <p>
                Las cookies son pequenos archivos que se almacenan en el navegador y permiten recordar cierta informacion tecnica necesaria para el funcionamiento del sitio.
            </p>
        </section>

        <section class="content-section">
            <h2>Que cookies usa actualmente BuyByte</h2>
            <p>
                Segun la configuracion actual del proyecto, BuyByte utiliza cookies tecnicas y de sesion propias de Laravel. Estas cookies se emplean para funciones basicas como mantener la sesion del usuario, proteger formularios mediante tokens de seguridad y gestionar el carrito y la navegacion autenticada.
            </p>
        </section>

        <section class="content-section">
            <h2>Cookies de analitica o terceros</h2>
            <p>
                En este momento no se han detectado integraciones activas con servicios de analitica, publicidad, mapas, chat o seguimiento de terceros como Google Analytics, Meta Pixel u otros servicios similares.
            </p>
        </section>

        <section class="content-section">
            <h2>Gestion de cookies</h2>
            <p>
                Actualmente BuyByte no muestra un banner de cookies. Si en el futuro se incorporan cookies no tecnicas o servicios externos, esta politica y el sistema de consentimiento deberan actualizarse en consecuencia.
            </p>
        </section>

        <section class="content-section">
            <h2>Resumen rapido</h2>
            <ul class="content-list mb-0">
                <li>Cookies tecnicas y de sesion.</li>
                <li>Sin analitica de terceros detectada.</li>
                <li>Sin banner de consentimiento por ahora.</li>
            </ul>
        </section>
    </div>
</section>
@endsection
