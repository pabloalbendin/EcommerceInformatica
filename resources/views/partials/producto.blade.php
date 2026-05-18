<div class="col mb-4">
    <article class="card product-card h-100">
        <a href="/producto/{{ $producto->id }}" class="product-card-link">
            <div class="product-media">
                <img src="{{ asset('storage/'.$producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
            </div>
            <div class="card-body">
                <h3 class="h5 mb-2">{{ $producto->nombre }}</h3>
                <p class="product-description mb-2">
                    {{ $producto->descripcion }}
                </p>
                <div class="product-meta">
                    @if ($producto->stock > 0)
                        <span class="badge-soft badge-stock-ok">Stock: {{ $producto->stock }}</span>
                    @else
                        <span class="badge-soft badge-stock-empty">Sin stock</span>
                    @endif
                    <span class="badge-soft">{{ $producto->categoria->nombre }}</span>
                </div>
                <p class="price-tag mb-0">{{ number_format($producto->precio, 2) }} €</p>
            </div>
        </a>
    </article>
</div>
