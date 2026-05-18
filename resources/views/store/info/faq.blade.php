@extends('layouts.app')

@section('titulo', 'Preguntas frecuentes | BuyByte')

@section('content')
<section class="container py-4">
    <div class="section-heading">
        <p class="eyebrow mb-3">Ayuda</p>
        <h1>Preguntas frecuentes</h1>
        <p class="section-copy mb-0">
            Aqui reunimos respuestas breves sobre el funcionamiento general de BuyByte dentro del contexto actual del proyecto.
        </p>
    </div>

    <div class="accordion faq-accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqEnvios" aria-expanded="true">
                    Donde se realizan los envios y cuanto tardan
                </button>
            </h2>
            <div id="faqEnvios" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    BuyByte plantea envios a toda Espana con una entrega estimada inferior a 72 horas. Los gastos de envio no estan definidos todavia y, cuando se concreten, deberan mostrarse antes de finalizar la compra.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqPago">
                    Que metodos de pago hay disponibles
                </button>
            </h2>
            <div id="faqPago" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    BuyByte utiliza una pasarela de pago de pruebas. No se realizan cargos reales ni se guardan datos bancarios; el formulario valida los datos introducidos y permite completar el flujo del pedido dentro del proyecto academico.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqDevoluciones">
                    Como funcionan las devoluciones
                </button>
            </h2>
            <div id="faqDevoluciones" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Al tratarse de un proyecto academico, BuyByte no aplica aun una politica comercial real de devoluciones. En un entorno de venta real, las condiciones de devolucion deberian detallarse de forma expresa antes de la compra, indicando plazos, estado del producto y procedimiento de solicitud.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqGarantia">
                    Como se gestionaria la garantia
                </button>
            </h2>
            <div id="faqGarantia" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    La garantia no esta desarrollada como proceso operativo dentro del proyecto. Si la tienda se adaptara a un uso comercial real, la gestion de garantia deberia ajustarse a la normativa aplicable y comunicarse con claridad al usuario.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqPedidos">
                    Se pueden seguir, cancelar o comunicar incidencias sobre pedidos
                </button>
            </h2>
            <div id="faqPedidos" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    BuyByte contempla la gestion de pedidos como parte del proyecto, pero algunos procesos pueden estar sujetos a futuras mejoras. Para incidencias o dudas, el canal de contacto previsto es el formulario y el correo de soporte mostrado en la web.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCuenta">
                    Es necesario tener cuenta para comprar
                </button>
            </h2>
            <div id="faqCuenta" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Si. En la configuracion actual, la compra requiere disponer de una cuenta de usuario para poder identificarse y continuar con el proceso.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
