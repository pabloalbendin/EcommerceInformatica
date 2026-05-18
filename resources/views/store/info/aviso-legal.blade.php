@extends('layouts.app')

@section('titulo', 'Aviso legal | BuyByte')

@section('content')
<section class="container py-4">
    <div class="info-header">
        <p class="eyebrow mb-3">Informacion legal</p>
        <h1 class="info-title">Aviso legal</h1>
        <p class="info-lead mb-0">
            El presente aviso regula el uso del sitio web BuyByte.
        </p>
    </div>
</section>

<section class="container pb-4">
    <div class="legal-content">
        <section class="content-section">
            <h2>Titular del sitio web</h2>
            <p class="mb-0">
                Titular: Pablo Albendin Cardona<br>
                Proyecto: BuyByte<br>
                Naturaleza: proyecto academico<br>
                NIF indicado a efectos academicos: 12345678A<br>
                Correo: contacto@buybyte.com<br>
                Telefono: 123456789<br>
                Ubicacion informativa: Valencia, Espana
            </p>
        </section>

        <section class="content-section">
            <h2>Objeto</h2>
            <p class="mb-0">
                BuyByte es una web desarrollada con fines academicos como parte de un proyecto del grado superior de DAW. Su objetivo es mostrar el desarrollo funcional y visual de una tienda online orientada al sector de la informatica.
            </p>
        </section>

        <section class="content-section">
            <h2>Condicion academica del sitio</h2>
            <p class="mb-0">
                La informacion, los flujos de compra y determinados apartados del sitio pueden responder a una finalidad demostrativa. Algunas funcionalidades podrian no representar una operativa comercial real o definitiva.
            </p>
        </section>

        <section class="content-section">
            <h2>Propiedad intelectual</h2>
            <p class="mb-0">
                Los contenidos, el codigo, el diseno y los elementos visuales de BuyByte forman parte del proyecto academico, salvo aquellos recursos de terceros utilizados legitimamente. No se permite su reproduccion total o parcial sin autorizacion cuando resulte aplicable.
            </p>
        </section>

        <section class="content-section">
            <h2>Responsabilidad</h2>
            <p class="mb-0">
                Se procura que la informacion del sitio sea clara y este actualizada dentro del contexto del proyecto, pero no se garantiza la ausencia total de errores, interrupciones o cambios. El uso del sitio se realiza bajo la responsabilidad del usuario.
            </p>
        </section>
    </div>
</section>
@endsection
