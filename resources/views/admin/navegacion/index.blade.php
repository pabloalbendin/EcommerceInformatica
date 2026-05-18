@extends('layouts.app')

@section('titulo', 'Panel de administracion - Navegacion')
@section('admin_title', 'Navegacion del sitio')

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')

    <div class="admin-menu-editor mb-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <p class="eyebrow mb-3">Menu principal</p>
                <h1 class="h3 mb-3">Categorias visibles en la cabecera</h1>
                <p class="section-copy mb-0">
                    Selecciona y ordena hasta 7 categorias. El orden en esta lista sera el mismo que aparecera en el menu principal de la tienda.
                </p>
            </div>
            <div class="col-lg-8">
                <form action="{{ route('admin.navegacion.menu-principal') }}" method="POST" id="menuCategoriasForm">
                    @csrf
                    <div class="admin-menu-list" id="adminMenuList">
                        @foreach($categorias as $categoria)
                            <label class="admin-menu-item {{ $categoria->mostrar_en_menu ? 'is-selected' : '' }}" data-categoria-id="{{ $categoria->id }}">
                                <input
                                    type="checkbox"
                                    value="{{ $categoria->id }}"
                                    class="form-check-input admin-menu-checkbox"
                                    {{ $categoria->mostrar_en_menu ? 'checked' : '' }}
                                >
                                <div class="admin-menu-content">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <strong>{{ $categoria->nombre }}</strong>
                                            <p class="section-copy mb-0">{{ $categoria->descripcion ?: 'Sin descripcion disponible.' }}</p>
                                        </div>
                                        <span class="badge-soft admin-menu-position {{ $categoria->mostrar_en_menu ? '' : 'd-none' }}">
                                            Posicion <span class="admin-menu-position-number">{{ $categoria->orden_menu }}</span>
                                        </span>
                                    </div>
                                    <small class="text-muted d-block mt-2">{{ $categoria->productos_count }} productos asociados</small>
                                </div>
                                <div class="admin-menu-actions">
                                    <button type="button" class="btn btn-sm btn-outline-secondary admin-menu-move" data-direction="up">Subir</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary admin-menu-move" data-direction="down">Bajar</button>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div id="adminMenuInputs"></div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                        <span class="text-muted small" id="adminMenuHelp">Marca como maximo 7 categorias. Puedes reordenarlas con los botones de subir y bajar.</span>
                        <button type="submit" class="btn btn-primary">Guardar menu principal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-menu-editor mb-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <p class="eyebrow mb-3">Footer</p>
                <h2 class="h4 mb-3">Configuracion general</h2>
                <p class="section-copy mb-0">
                    Define el numero de columnas del footer y el texto descriptivo principal de la marca.
                </p>
            </div>
            <div class="col-lg-8">
                <form action="{{ route('admin.navegacion.footer-config') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label for="footer_columnas" class="form-label">Numero de columnas</label>
                        <select name="footer_columnas" id="footer_columnas" class="form-select">
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}" {{ $footerColumnas === $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label for="footer_descripcion" class="form-label">Descripcion del footer</label>
                        <input type="text" name="footer_descripcion" id="footer_descripcion" class="form-control" value="{{ old('footer_descripcion', $footerDescripcion) }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar configuracion del footer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-menu-editor mb-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <p class="eyebrow mb-3">Enlaces del footer</p>
                <h2 class="h4 mb-3">{{ $enlaceFooterEditando ? 'Editar enlace' : 'Nuevo enlace' }}</h2>
                <p class="section-copy mb-0">
                    Puedes añadir enlaces hacia paginas personalizadas, categorias de la tienda o URLs propias definidas por el admin.
                </p>
            </div>
            <div class="col-lg-8">
                <form action="{{ $enlaceFooterEditando ? route('admin.navegacion.footer-enlaces.actualizar', $enlaceFooterEditando->id) : route('admin.navegacion.footer-enlaces.guardar') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="titulo" class="form-label">Texto del enlace</label>
                        <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $enlaceFooterEditando->titulo ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tipo_destino" class="form-label">Tipo de destino</label>
                        <select name="tipo_destino" id="tipo_destino" class="form-select footer-destino-select">
                            <option value="pagina" {{ old('tipo_destino', $enlaceFooterEditando->tipo_destino ?? 'pagina') === 'pagina' ? 'selected' : '' }}>Pagina personalizada</option>
                            <option value="categoria" {{ old('tipo_destino', $enlaceFooterEditando->tipo_destino ?? '') === 'categoria' ? 'selected' : '' }}>Categoria</option>
                            <option value="url" {{ old('tipo_destino', $enlaceFooterEditando->tipo_destino ?? '') === 'url' ? 'selected' : '' }}>URL personalizada</option>
                        </select>
                    </div>
                    <div class="col-md-6 footer-target footer-target-pagina">
                        <label for="pagina_personalizada_id" class="form-label">Pagina personalizada</label>
                        <select name="pagina_personalizada_id" id="pagina_personalizada_id" class="form-select">
                            <option value="">Selecciona una pagina</option>
                            @foreach($paginas as $pagina)
                                <option value="{{ $pagina->id }}" {{ (string) old('pagina_personalizada_id', $enlaceFooterEditando->pagina_personalizada_id ?? '') === (string) $pagina->id ? 'selected' : '' }}>
                                    {{ $pagina->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 footer-target footer-target-categoria">
                        <label for="categoria_id" class="form-label">Categoria</label>
                        <select name="categoria_id" id="categoria_id" class="form-select">
                            <option value="">Selecciona una categoria</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ (string) old('categoria_id', $enlaceFooterEditando->categoria_id ?? '') === (string) $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 footer-target footer-target-url">
                        <label for="url_personalizada" class="form-label">URL personalizada</label>
                        <input type="text" name="url_personalizada" id="url_personalizada" class="form-control" value="{{ old('url_personalizada', $enlaceFooterEditando->url_personalizada ?? '') }}" placeholder="https://... o /ruta-propia">
                    </div>
                    <div class="col-md-4">
                        <label for="columna" class="form-label">Columna</label>
                        <select name="columna" id="columna" class="form-select">
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}" {{ (int) old('columna', $enlaceFooterEditando->columna ?? 1) === $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="orden" class="form-label">Orden</label>
                        <input type="number" min="1" name="orden" id="orden" class="form-control" value="{{ old('orden', $enlaceFooterEditando->orden ?? 1) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="nueva_pestana" name="nueva_pestana" {{ old('nueva_pestana', $enlaceFooterEditando->nueva_pestana ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="nueva_pestana">
                                Abrir en nueva pestana
                            </label>
                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary">{{ $enlaceFooterEditando ? 'Actualizar enlace' : 'Crear enlace' }}</button>
                        @if($enlaceFooterEditando)
                            <a href="{{ route('admin.navegacion') }}" class="btn btn-outline-secondary">Cancelar edicion</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-shell">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Texto</th>
                        <th>Tipo</th>
                        <th>Destino</th>
                        <th>Columna</th>
                        <th>Orden</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enlacesFooter as $enlace)
                        <tr>
                            <td class="fw-semibold">{{ $enlace->titulo }}</td>
                            <td>{{ ucfirst($enlace->tipo_destino) }}</td>
                            <td>
                                @if($enlace->tipo_destino === 'pagina' && $enlace->paginaPersonalizada)
                                    /{{ $enlace->paginaPersonalizada->slug }}
                                @elseif($enlace->tipo_destino === 'categoria' && $enlace->categoria)
                                    {{ $enlace->categoria->nombre }}
                                @else
                                    {{ $enlace->url_personalizada }}
                                @endif
                            </td>
                            <td>{{ $enlace->columna }}</td>
                            <td>{{ $enlace->orden }}</td>
                            <td class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.navegacion', ['editar_enlace' => $enlace->id]) }}" class="btn btn-sm btn-primary">Editar</a>
                                <form action="{{ route('admin.navegacion.footer-enlaces.eliminar', $enlace->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Todavia no hay enlaces personalizados en el footer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('menuCategoriasForm');
        const lista = document.getElementById('adminMenuList');
        const inputsContainer = document.getElementById('adminMenuInputs');
        const ayuda = document.getElementById('adminMenuHelp');
        let items = Array.from(document.querySelectorAll('.admin-menu-item'));
        const checkboxes = Array.from(document.querySelectorAll('.admin-menu-checkbox'));
        const maxSeleccionadas = 7;

        function crearInputsOrdenados() {
            inputsContainer.innerHTML = '';

            items
                .filter((item) => item.querySelector('.admin-menu-checkbox').checked)
                .forEach((item) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'categorias_menu[]';
                    input.value = item.dataset.categoriaId;
                    inputsContainer.appendChild(input);
                });
        }

        function actualizarPosiciones() {
            let posicion = 1;

            items.forEach((item) => {
                const checkbox = item.querySelector('.admin-menu-checkbox');
                const badge = item.querySelector('.admin-menu-position');
                const numero = item.querySelector('.admin-menu-position-number');
                const botones = item.querySelectorAll('.admin-menu-move');

                item.classList.toggle('is-selected', checkbox.checked);

                if (checkbox.checked) {
                    badge.classList.remove('d-none');
                    numero.textContent = posicion;
                    posicion += 1;
                    botones.forEach((boton) => boton.disabled = false);
                } else {
                    badge.classList.add('d-none');
                    numero.textContent = '';
                    botones.forEach((boton) => boton.disabled = true);
                }
            });
        }

        function actualizarEstado() {
            const marcadas = checkboxes.filter((checkbox) => checkbox.checked);
            const bloquear = marcadas.length >= maxSeleccionadas;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = bloquear && !checkbox.checked;
            });

            ayuda.textContent = `Seleccionadas ${marcadas.length} de ${maxSeleccionadas}. Puedes reordenarlas con los botones de subir y bajar.`;
            actualizarPosiciones();
            crearInputsOrdenados();
        }

        function refrescarItems() {
            items = Array.from(lista.querySelectorAll('.admin-menu-item'));
        }

        function colocarEntreSeleccionadas(item) {
            const checkbox = item.querySelector('.admin-menu-checkbox');

            if (!checkbox.checked) {
                return;
            }

            const seleccionadas = items.filter((elemento) => {
                return elemento !== item && elemento.querySelector('.admin-menu-checkbox').checked;
            });

            if (!seleccionadas.length) {
                lista.insertBefore(item, lista.firstChild);
            } else {
                seleccionadas[seleccionadas.length - 1].after(item);
            }

            refrescarItems();
        }

        function moverItem(item, direccion) {
            const checkbox = item.querySelector('.admin-menu-checkbox');
            if (!checkbox.checked) {
                return;
            }

            let hermano = direccion === 'up' ? item.previousElementSibling : item.nextElementSibling;

            while (hermano && !hermano.querySelector('.admin-menu-checkbox').checked) {
                hermano = direccion === 'up' ? hermano.previousElementSibling : hermano.nextElementSibling;
            }

            if (!hermano) {
                return;
            }

            if (direccion === 'up') {
                lista.insertBefore(item, hermano);
            } else {
                lista.insertBefore(hermano, item);
            }

            refrescarItems();
            actualizarEstado();
        }

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                colocarEntreSeleccionadas(this.closest('.admin-menu-item'));
                actualizarEstado();
            });
        });

        document.querySelectorAll('.admin-menu-move').forEach((boton) => {
            boton.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                moverItem(this.closest('.admin-menu-item'), this.dataset.direction);
            });
        });

        form.addEventListener('submit', function () {
            crearInputsOrdenados();
        });

        const selectorDestino = document.querySelector('.footer-destino-select');
        const bloquesDestino = document.querySelectorAll('.footer-target');

        function actualizarDestinoFooter() {
            const valor = selectorDestino.value;

            bloquesDestino.forEach((bloque) => {
                bloque.style.display = 'none';
            });

            const activo = document.querySelector('.footer-target-' + valor);
            if (activo) {
                activo.style.display = 'block';
            }
        }

        if (selectorDestino) {
            selectorDestino.addEventListener('change', actualizarDestinoFooter);
            actualizarDestinoFooter();
        }

        actualizarEstado();
    });
</script>
@endsection
