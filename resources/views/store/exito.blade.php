@extends('layouts.app')

@section('titulo', 'Tienda Informatica')

@section('content')
<div class="container py-5 text-center">
    <div class="success-panel">
        <p class="eyebrow mb-3">Pedido confirmado</p>
        <h1 class="mb-3">Pedido generado con éxito</h1>
        <p class="section-copy mb-2">Gracias por tu compra. Tu pedido ha sido registrado correctamente y ya está listo para gestión.</p>
        @isset($referenciaPago)
            <p class="text-muted mb-4">Referencia de pago de prueba: <strong>{{ $referenciaPago }}</strong></p>
        @else
            <p class="section-copy mb-4">El pago se ha procesado en modo de pruebas.</p>
        @endisset
        <a href="/" class="btn btn-primary btn-lg">Volver a la página principal</a>
    </div>
</div>
@endsection
