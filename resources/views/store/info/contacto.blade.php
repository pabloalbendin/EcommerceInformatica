@extends('layouts.app')

@section('titulo', 'Contactanos | BuyByte')

@section('content')
<section class="container py-5 contact-page">
    <div class="info-header contact-page-header mx-auto">
        <span class="eyebrow">Contacto</span>
        <h1 class="info-title">Contactanos</h1>
        <p class="info-lead mb-3">
            Puedes escribirnos mediante el formulario de esta pagina. El formulario esta operativo a nivel visual y validacion, pero por ahora no envia correos automaticamente.
        </p>
        <p class="text-muted mb-0">Tiempo estimado de respuesta: menos de 24 horas.</p>
    </div>
</section>

<section class="container pb-5 contact-page">
    <div class="row g-4 g-lg-5 align-items-start">
        <div class="col-lg-7">
            <div class="info-form-wrap contact-form-panel">
                <h2 class="h4 mb-4">Formulario de contacto</h2>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <p class="fw-semibold mb-2">Revisa el formulario:</p>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contacto') }}" method="POST" class="row g-4">
                    @csrf
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electronico</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-12">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" id="asunto" name="asunto" class="form-control" value="{{ old('asunto') }}" required>
                    </div>
                    <div class="col-12">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="6" class="form-control" required>{{ old('mensaje') }}</textarea>
                    </div>
                    <div class="col-12 d-flex flex-wrap gap-3 align-items-center pt-2">
                        <button type="submit" class="btn btn-primary">Enviar formulario</button>
                        <span class="text-muted small">Modo demostracion: el envio real por correo se activara mas adelante.</span>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-5">
            <section class="content-section contact-data contact-data-panel">
                <h2 class="h4 mb-4">Datos de contacto</h2>
                <div class="contact-list">
                    <div>
                        <span class="info-label">Correo</span>
                        <a href="mailto:contacto@buybyte.com">contacto@buybyte.com</a>
                    </div>
                    <div>
                        <span class="info-label">Telefono</span>
                        <a href="tel:123456789">123456789</a>
                    </div>
                    <div>
                        <span class="info-label">Horario</span>
                        <span>De 9:00 a 20:00</span>
                    </div>
                    <div>
                        <span class="info-label">Ubicacion</span>
                        <span>Valencia, Espana</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
