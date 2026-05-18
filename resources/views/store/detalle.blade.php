@extends('layouts.app')

@section('titulo', $producto->nombre)

@section('content')
<div class="container py-4 detail-page">
    <nav class="detail-breadcrumb mb-3">
        <a href="{{ route('home') }}">Inicio</a>
        <span>/</span>
        <a href="{{ route('categoria', $producto->categoria->id) }}">{{ $producto->categoria->nombre }}</a>
        <span>/</span>
        <span>{{ $producto->nombre }}</span>
    </nav>

    <div class="detail-layout">
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="detail-main-image">
                    <img src="{{ asset('storage/'.$producto->imagen) }}" class="img-fluid" alt="{{ $producto->nombre }}">
                </div>

                <div class="detail-description mt-3">
                    <h2 class="h5 mb-2">Descripcion</h2>
                    <p class="section-copy mb-0">{{ $producto->descripcion }}</p>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="detail-buy-panel">
                    <h1 class="mb-2">{{ $producto->nombre }}</h1>
                    <p class="section-copy mb-3">{{ $producto->categoria->nombre }}</p>

                    <div class="detail-price-line">
                        <span>Precio final</span>
                        <strong>{{ number_format($producto->precio, 2) }} €</strong>
                    </div>

                    <div class="detail-info-list">
                        <div>
                            <span>Disponibilidad</span>
                            @if ($producto->stock > 0)
                                <strong>{{ $producto->stock }} unidades</strong>
                            @else
                                <strong>Sin stock</strong>
                            @endif
                        </div>
                        <div>
                            <span>Envio</span>
                            <strong>Preparacion rapida del pedido</strong>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <a href="/" class="btn btn-outline-secondary">Volver</a>
                        @if($producto->stock > 0)
                            <a href="/carrito/add/{{ $producto->id}}" class="btn btn-primary">Añadir al carrito</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
