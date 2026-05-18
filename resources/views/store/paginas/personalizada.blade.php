@extends('layouts.app')

@section('titulo', $pagina->titulo . ' | BuyByte')

@section('content')
<section class="container py-4">
    <div class="info-header">
        <h1 class="info-title">{{ $pagina->titulo }}</h1>
    </div>
</section>

<section class="container pb-4">
    <div class="custom-page-shell">
        {!! $pagina->contenido_html !!}
    </div>
</section>
@endsection
