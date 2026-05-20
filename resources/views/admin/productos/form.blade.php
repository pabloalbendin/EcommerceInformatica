@extends('layouts.app')

<!-- Título dinámico de la página según si se está creando o editando un producto -->
@section('titulo')
    {{ isset($producto) ? 'Editar producto' : 'Crear producto' }}
@endsection
@section('admin_title')
    {{ isset($producto) ? 'Editar producto' : 'Crear producto' }}
@endsection

@section('content')
<div class="container py-4">
    @include('partials.admin-nav')

    <div class="auth-card">
        <h1 class="h3 mb-4">{{ isset($producto) ? 'Editar producto' : 'Crear producto' }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($producto) ? '/admin/editar/' . $producto->id : '/admin/crear' }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="{{ old('precio', $producto->precio ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $producto->stock ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="id_categoria" class="form-label">Categoría</label>
                    <select class="form-select" id="id_categoria" name="id_categoria">
                        <option value="">-- Selecciona categoría --</option>
                        @foreach($categorias as $categoria)
                            <option
                                value="{{ $categoria->id }}"
                                data-es-componentes="{{ \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($categoria->nombre)) === 'componentes' ? '1' : '0' }}"
                                {{ old('id_categoria', $producto->id_categoria ?? '') == $categoria->id ? 'selected' : '' }}
                            >
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 d-none" id="tipoComponenteWrapper">
                <label for="tipo_componente" class="form-label">Tipo de componente</label>
                <select class="form-select" id="tipo_componente" name="tipo_componente">
                    <option value="">-- Selecciona tipo de componente --</option>
                    @foreach($tiposComponente as $clave => $etiqueta)
                        <option value="{{ $clave }}" {{ old('tipo_componente', $producto->tipo_componente ?? '') === $clave ? 'selected' : '' }}>
                            {{ $etiqueta }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Este campo es obligatorio cuando la categoría es Componentes.</small>
            </div>

            <div class="d-none" id="specsWrapper">
                <div class="row" id="specs-cpu">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Socket</label>
                        <input type="text" class="form-control" name="specs[socket]" value="{{ old('specs.socket', data_get($producto->specs ?? [], 'socket')) }}" placeholder="am5, lga1700...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">TDP (W)</label>
                        <input type="number" class="form-control" name="specs[tdp_w]" value="{{ old('specs.tdp_w', data_get($producto->specs ?? [], 'tdp_w')) }}" min="1">
                    </div>
                </div>

                <div class="row d-none" id="specs-placa_base">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Socket</label>
                        <input type="text" class="form-control" name="specs[socket]" value="{{ old('specs.socket', data_get($producto->specs ?? [], 'socket')) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo RAM</label>
                        <select class="form-select" name="specs[ram_tipo]">
                            <option value="">-- Selecciona --</option>
                            @foreach(['ddr4' => 'DDR4', 'ddr5' => 'DDR5'] as $valor => $label)
                                <option value="{{ $valor }}" {{ old('specs.ram_tipo', data_get($producto->specs ?? [], 'ram_tipo')) === $valor ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Formato placa</label>
                        <select class="form-select" name="specs[form_factor]">
                            <option value="">-- Selecciona --</option>
                            @foreach(['atx' => 'ATX', 'matx' => 'mATX', 'itx' => 'ITX'] as $valor => $label)
                                <option value="{{ $valor }}" {{ old('specs.form_factor', data_get($producto->specs ?? [], 'form_factor')) === $valor ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row d-none" id="specs-ram">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tipo RAM</label>
                        <select class="form-select" name="specs[ram_tipo]">
                            <option value="">-- Selecciona --</option>
                            @foreach(['ddr4' => 'DDR4', 'ddr5' => 'DDR5'] as $valor => $label)
                                <option value="{{ $valor }}" {{ old('specs.ram_tipo', data_get($producto->specs ?? [], 'ram_tipo')) === $valor ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Módulos</label>
                        <input type="number" class="form-control" name="specs[modules]" min="1" max="8" value="{{ old('specs.modules', data_get($producto->specs ?? [], 'modules')) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Capacidad (GB)</label>
                        <input type="number" class="form-control" name="specs[capacity_gb]" min="1" value="{{ old('specs.capacity_gb', data_get($producto->specs ?? [], 'capacity_gb')) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Frecuencia (MHz)</label>
                        <input type="number" class="form-control" name="specs[speed_mhz]" min="800" value="{{ old('specs.speed_mhz', data_get($producto->specs ?? [], 'speed_mhz')) }}">
                    </div>
                </div>

                <div class="row d-none" id="specs-gpu">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">TDP (W)</label>
                        <input type="number" class="form-control" name="specs[tdp_w]" min="1" value="{{ old('specs.tdp_w', data_get($producto->specs ?? [], 'tdp_w')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Longitud (mm)</label>
                        <input type="number" class="form-control" name="specs[length_mm]" min="1" value="{{ old('specs.length_mm', data_get($producto->specs ?? [], 'length_mm')) }}">
                    </div>
                </div>

                <div class="row d-none" id="specs-almacenamiento">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Interfaz</label>
                        <input type="text" class="form-control" name="specs[interface]" value="{{ old('specs.interface', data_get($producto->specs ?? [], 'interface')) }}" placeholder="nvme, sata...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Capacidad (GB)</label>
                        <input type="number" class="form-control" name="specs[capacity_gb]" min="1" value="{{ old('specs.capacity_gb', data_get($producto->specs ?? [], 'capacity_gb')) }}">
                    </div>
                </div>

                <div class="row d-none" id="specs-fuente">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Potencia (W)</label>
                        <input type="number" class="form-control" name="specs[power_w]" min="50" value="{{ old('specs.power_w', data_get($producto->specs ?? [], 'power_w')) }}">
                    </div>
                </div>

                <div class="row d-none" id="specs-caja">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Formato soportado 1</label>
                        <select class="form-select" name="specs[supported_form_factors][]">
                            <option value="">-- Selecciona --</option>
                            @foreach(['atx' => 'ATX', 'matx' => 'mATX', 'itx' => 'ITX'] as $valor => $label)
                                <option value="{{ $valor }}" {{ in_array($valor, old('specs.supported_form_factors', data_get($producto->specs ?? [], 'supported_form_factors', [])), true) ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Formato soportado 2</label>
                        <select class="form-select" name="specs[supported_form_factors][]">
                            <option value="">-- Selecciona --</option>
                            @foreach(['atx' => 'ATX', 'matx' => 'mATX', 'itx' => 'ITX'] as $valor => $label)
                                <option value="{{ $valor }}" {{ in_array($valor, old('specs.supported_form_factors', data_get($producto->specs ?? [], 'supported_form_factors', [])), true) ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Formato soportado 3</label>
                        <select class="form-select" name="specs[supported_form_factors][]">
                            <option value="">-- Selecciona --</option>
                            @foreach(['atx' => 'ATX', 'matx' => 'mATX', 'itx' => 'ITX'] as $valor => $label)
                                <option value="{{ $valor }}" {{ in_array($valor, old('specs.supported_form_factors', data_get($producto->specs ?? [], 'supported_form_factors', [])), true) ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Máx. longitud GPU (mm)</label>
                        <input type="number" class="form-control" name="specs[max_gpu_length_mm]" min="100" value="{{ old('specs.max_gpu_length_mm', data_get($producto->specs ?? [], 'max_gpu_length_mm')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Máx. altura disipador (mm)</label>
                        <input type="number" class="form-control" name="specs[max_cooler_height_mm]" min="50" value="{{ old('specs.max_cooler_height_mm', data_get($producto->specs ?? [], 'max_cooler_height_mm')) }}">
                    </div>
                </div>

                <div class="row d-none" id="specs-refrigeracion">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Socket compatible 1</label>
                        <input type="text" class="form-control" name="specs[supported_sockets][]" value="{{ old('specs.supported_sockets.0', data_get($producto->specs ?? [], 'supported_sockets.0')) }}" placeholder="am5">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Socket compatible 2</label>
                        <input type="text" class="form-control" name="specs[supported_sockets][]" value="{{ old('specs.supported_sockets.1', data_get($producto->specs ?? [], 'supported_sockets.1')) }}" placeholder="lga1700">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Altura (mm)</label>
                        <input type="number" class="form-control" name="specs[height_mm]" min="20" value="{{ old('specs.height_mm', data_get($producto->specs ?? [], 'height_mm')) }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen</label>
                @if(isset($producto) && $producto->imagen)
                    <div class="mb-2">
                        <p>Imagen actual:</p>
                        <img src="{{ asset('storage/'.$producto->imagen) }}" width="100" class="img-thumbnail">
                    </div>
                @endif
                <input type="file" class="form-control" id="imagen" name="imagen">
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-primary">
                    {{ isset($producto) ? 'Actualizar producto' : 'Crear producto' }}
                </button>
                <a href="{{ route('admin.productos') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        var categoriaSelect = document.getElementById('id_categoria');
        var tipoWrapper = document.getElementById('tipoComponenteWrapper');
        var tipoSelect = document.getElementById('tipo_componente');

        if (!categoriaSelect || !tipoWrapper || !tipoSelect) {
            return;
        }

        function toggleTipoComponente() {
            var selectedOption = categoriaSelect.options[categoriaSelect.selectedIndex];
            var esComponente = selectedOption && selectedOption.dataset.esComponentes === '1';
            var specsWrapper = document.getElementById('specsWrapper');
            var specsBlocks = [
                'cpu',
                'placa_base',
                'ram',
                'gpu',
                'almacenamiento',
                'fuente',
                'caja',
                'refrigeracion'
            ];
            var activeBlock = null;

            tipoWrapper.classList.toggle('d-none', !esComponente);
            tipoSelect.required = esComponente;
            tipoSelect.disabled = !esComponente;

            if (!esComponente) {
                tipoSelect.value = '';
            }

            if (specsWrapper) {
                specsWrapper.classList.toggle('d-none', !esComponente);
            }

            specsBlocks.forEach(function (key) {
                var block = document.getElementById('specs-' + key);
                if (block) {
                    var isActive = tipoSelect.value === key && esComponente;
                    block.classList.toggle('d-none', !isActive);

                    Array.prototype.forEach.call(block.querySelectorAll('input, select, textarea'), function (field) {
                        field.disabled = !isActive;
                    });

                    if (isActive) {
                        activeBlock = block;
                    }
                }
            });

            if (!activeBlock && specsWrapper) {
                Array.prototype.forEach.call(specsWrapper.querySelectorAll('input, select, textarea'), function (field) {
                    field.disabled = true;
                });
            }
        }

        tipoSelect.addEventListener('change', toggleTipoComponente);
        categoriaSelect.addEventListener('change', toggleTipoComponente);
        toggleTipoComponente();
    })();
</script>
@endsection
