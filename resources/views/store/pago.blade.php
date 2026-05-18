@extends('layouts.app')

@section('titulo', 'Pago de prueba')

@section('content')
<div class="container py-4">
    <div class="section-heading">
        <p class="eyebrow mb-3">Pasarela de pruebas</p>
        <h1>Pago seguro simulado</h1>
        <p class="section-copy mb-0">Este formulario no realiza cargos reales ni guarda datos bancarios. Solo valida la compra dentro del proyecto.</p>
    </div>

    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <div class="payment-panel">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h2 class="h4 mb-2">Datos de tarjeta de prueba</h2>
                        <p class="text-muted mb-0">Puedes usar 4242 4242 4242 4242, cualquier fecha futura y CVV de prueba.</p>
                    </div>
                    <span class="badge-soft payment-test-badge">TEST</span>
                </div>

                <form action="{{ route('pagar') }}" method="POST" class="payment-form">
                    @csrf
                    <input type="hidden" name="checkout_source" value="{{ $checkoutSource ?? 'carrito' }}">

                    <div class="mb-3">
                        <label for="titular" class="form-label">Titular</label>
                        <input type="text" id="titular" name="titular" class="form-control @error('titular') is-invalid @enderror" value="{{ old('titular', auth()->user()->nombre ?? '') }}" autocomplete="off">
                        @error('titular')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="numero_tarjeta" class="form-label">Número de tarjeta</label>
                        <input type="text" id="numero_tarjeta" name="numero_tarjeta" class="form-control @error('numero_tarjeta') is-invalid @enderror" value="{{ old('numero_tarjeta') }}" inputmode="numeric" placeholder="4242 4242 4242 4242" autocomplete="off">
                        @error('numero_tarjeta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="caducidad" class="form-label">Caducidad</label>
                            <input type="text" id="caducidad" name="caducidad" class="form-control @error('caducidad') is-invalid @enderror" value="{{ old('caducidad') }}" placeholder="12/30" autocomplete="off">
                            @error('caducidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" id="cvv" name="cvv" class="form-control @error('cvv') is-invalid @enderror" inputmode="numeric" placeholder="123" autocomplete="off">
                            @error('cvv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="payment-actions mt-4">
                        <a href="{{ route($volverRoute ?? 'carrito') }}" class="btn btn-outline-secondary">Volver</a>
                        <button type="submit" class="btn btn-success">Confirmar pago de prueba</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="payment-summary">
                <h2 class="h4 mb-3">Resumen del pedido</h2>

                <div class="payment-summary-list">
                    @foreach ($productos as $producto)
                        <div class="payment-summary-item">
                            <div>
                                <div class="fw-semibold">{{ $producto->nombre }}</div>
                                <div class="small text-muted">{{ $producto->cantidad }} x {{ number_format($producto->precio, 2) }} €</div>
                            </div>
                            <div class="fw-semibold">{{ number_format($producto->precio * $producto->cantidad, 2) }} €</div>
                        </div>
                    @endforeach
                </div>

                <div class="payment-summary-total">
                    <span>Total</span>
                    <strong>{{ number_format($total, 2) }} €</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
