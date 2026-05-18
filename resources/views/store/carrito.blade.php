@extends('layouts.app')

@section('titulo', 'Carrito')

@section('content')
<div class="container py-4">
    <div class="section-heading">
        <p class="eyebrow mb-3">Resumen</p>
        <h1>Tu carrito</h1>
        <p class="section-copy mb-0">Revisa cantidades, controla el importe y confirma el pedido en un entorno mas claro.</p>
    </div>

    <div class="table-shell">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr>
                            <td class="fw-semibold">{{ $producto->nombre }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="/carrito/{{ $producto->id }}/{{ $producto->cantidad-1 }}" class="btn btn-sm btn-outline-secondary">-</a>
                                    <span class="badge-soft">{{ $producto->cantidad }}</span>
                                    <a href="/carrito/{{ $producto->id }}/{{ $producto->cantidad+1 }}" class="btn btn-sm btn-outline-secondary">+</a>
                                </div>
                            </td>
                            <td>{{ number_format($producto->precio, 2) }} €</td>
                            <td class="fw-semibold">{{ number_format($producto->precio * $producto->cantidad, 2) }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">Todavia no has añadido productos al carrito.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($productos->count())
        <div class="cart-summary mt-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <p class="mb-1 text-muted">Total estimado</p>
                <div class="price-tag">{{ number_format($productos->sum(fn($producto) => $producto->precio * $producto->cantidad), 2) }} €</div>
            </div>
            <a href="{{ route('pago.form') }}" class="btn btn-success btn-lg">Continuar al pago</a>
        </div>
    @endif
</div>
@endsection
