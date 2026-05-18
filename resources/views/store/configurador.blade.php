@extends('layouts.app')

@section('titulo', 'Configurador de PC')

@section('content')
<div class="container py-4">
    <div class="section-heading">
        <p class="eyebrow mb-3">Herramienta</p>
        <h1>Configurador de ordenador</h1>
        <p class="section-copy mb-0">Configura un equipo por piezas, recibe recomendaciones y comprueba compatibilidad antes de comprar.</p>
    </div>

    @if($bloques->isEmpty())
        <div class="empty-state table-shell mt-4">
            <p class="mb-0">No hay componentes con stock para configurar un equipo ahora mismo.</p>
        </div>
    @else
        <form action="{{ route('configurador.comprar') }}" method="POST" id="configuratorForm">
            @csrf

            <div class="row g-4 mt-1">
                <div class="col-lg-7">
                    <div class="configurator-panel">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                            <h2 class="h4 mb-0">Componentes</h2>
                            <div class="d-flex align-items-center gap-2">
                                <label for="perfil-uso" class="small text-muted mb-0">Recomendador</label>
                                <select id="perfil-uso" class="form-select form-select-sm">
                                    <option value="">Sin perfil</option>
                                    <option value="gaming">Gaming</option>
                                    <option value="trabajo">Trabajo/Oficina</option>
                                    <option value="equilibrado">Equilibrado</option>
                                </select>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="aplicar-recomendacion">Aplicar</button>
                            </div>
                        </div>

                        <div class="configurator-grid">
                            @foreach($bloques as $bloque)
                                <div class="configurator-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="slot-{{ $bloque['key'] }}" class="form-label fw-semibold mb-0">{{ $bloque['label'] }}</label>
                                        <span class="configurator-tag">{{ $bloque['required'] ? 'Obligatorio' : 'Opcional' }}</span>
                                    </div>

                                    @if($bloque['productos']->isNotEmpty())
                                        <select
                                            id="slot-{{ $bloque['key'] }}"
                                            name="componentes[]"
                                            class="form-select config-component-select"
                                            data-slot-key="{{ $bloque['key'] }}"
                                            data-category="{{ $bloque['label'] }}"
                                            data-required="{{ $bloque['required'] ? '1' : '0' }}"
                                        >
                                            <option value="" data-price="0">Sin seleccionar</option>
                                            @foreach($bloque['productos'] as $producto)
                                            <option
                                                value="{{ $producto->id }}"
                                                data-price="{{ number_format($producto->precio, 2, '.', '') }}"
                                                data-name="{{ $producto->nombre }}"
                                                data-stock="{{ $producto->stock }}"
                                                data-specs='@json($producto->specs ?? [])'
                                            >
                                                {{ $producto->nombre }} ({{ number_format($producto->precio, 2) }} €)
                                            </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select id="slot-{{ $bloque['key'] }}" class="form-select" disabled>
                                            <option>Sin opciones disponibles</option>
                                        </select>
                                        <small class="text-muted mt-1">No hay productos en catálogo que encajen con este bloque todavía.</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="configurator-summary">
                        <h2 class="h4 mb-3">Resumen y servicios</h2>

                        <div class="configurator-services mb-3">
                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input config-extra-checkbox"
                                    type="checkbox"
                                    id="servicio-montaje"
                                    name="incluye_montaje"
                                    value="1"
                                    data-price="{{ number_format($servicioMontaje, 2, '.', '') }}"
                                    data-label="Montaje profesional"
                                >
                                <label class="form-check-label" for="servicio-montaje">
                                    Montaje profesional (+{{ number_format($servicioMontaje, 2) }} €)
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="servicio-so" name="incluye_so" value="1">
                                <label class="form-check-label" for="servicio-so">
                                    Instalar sistema operativo automáticamente
                                </label>
                            </div>

                            <label for="opcion-so" class="form-label small text-muted mb-1">Sistema operativo</label>
                            <select id="opcion-so" name="so_opcion" class="form-select" disabled>
                                <option value="" data-price="0">No seleccionar</option>
                                @foreach($opcionesSistemaOperativo as $opcion)
                                    <option value="{{ $opcion['id'] }}" data-price="{{ number_format($opcion['precio'], 2, '.', '') }}">
                                        {{ $opcion['nombre'] }} ({{ number_format($opcion['precio'], 2) }} €)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-3">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th class="text-end">Precio</th>
                                    </tr>
                                </thead>
                                <tbody id="configurator-breakdown"></tbody>
                            </table>
                        </div>

                        <div id="configurator-required-warning" class="alert alert-warning py-2 px-3 mb-2 d-none"></div>
                        <div id="configurator-compatibility-warning" class="alert alert-danger py-2 px-3 mb-3 d-none"></div>

                        <div class="configurator-total d-flex justify-content-between align-items-center mb-3">
                            <span>Total estimado</span>
                            <strong id="configurator-total">0,00 €</strong>
                        </div>

                        @auth
                            <button type="submit" class="btn btn-success w-100" id="configurator-buy-btn">Añadir configuración al carrito</button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">Inicia sesión para comprar esta configuración</a>
                        @endauth
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>

@if($bloques->isNotEmpty())
    <script>
        (function () {
            var currencyFormatter = new Intl.NumberFormat('es-ES', {
                style: 'currency',
                currency: 'EUR'
            });

            var slots = {
                cpu: document.querySelector('[data-slot-key="cpu"]'),
                placa_base: document.querySelector('[data-slot-key="placa_base"]'),
                ram: document.querySelector('[data-slot-key="ram"]'),
                gpu: document.querySelector('[data-slot-key="gpu"]'),
                almacenamiento: document.querySelector('[data-slot-key="almacenamiento"]'),
                fuente: document.querySelector('[data-slot-key="fuente"]'),
            };

            var selects = Array.from(document.querySelectorAll('.config-component-select'));
            var montajeCheckbox = document.getElementById('servicio-montaje');
            var osCheckbox = document.getElementById('servicio-so');
            var osSelect = document.getElementById('opcion-so');
            var breakdownBody = document.getElementById('configurator-breakdown');
            var totalNode = document.getElementById('configurator-total');
            var requiredWarning = document.getElementById('configurator-required-warning');
            var compatibilityWarning = document.getElementById('configurator-compatibility-warning');
            var buyButton = document.getElementById('configurator-buy-btn');
            var profileSelect = document.getElementById('perfil-uso');
            var applyRecommendationBtn = document.getElementById('aplicar-recomendacion');

            function asPrice(value) {
                var parsed = Number(value || 0);
                return Number.isNaN(parsed) ? 0 : parsed;
            }

            function selectedOption(select) {
                return select ? select.options[select.selectedIndex] : null;
            }

            function selectedTextBySlot(slotKey) {
                var select = slots[slotKey];
                var option = selectedOption(select);
                return option ? (option.dataset.name || option.textContent || '').toLowerCase() : '';
            }

            function selectedSpecs(slotKey) {
                var select = slots[slotKey];
                var option = selectedOption(select);
                if (!option || !option.dataset.specs) {
                    return {};
                }
                try {
                    return JSON.parse(option.dataset.specs);
                } catch (error) {
                    return {};
                }
            }

            function estimatedConsumption() {
                var total = 150;
                var gpuSpecs = selectedSpecs('gpu');
                var cpuSpecs = selectedSpecs('cpu');

                if (gpuSpecs.tdp_w) {
                    total += Number(gpuSpecs.tdp_w);
                }
                if (cpuSpecs.tdp_w) {
                    total += Number(cpuSpecs.tdp_w);
                }

                return total;
            }

            function addLine(label, amount) {
                var row = document.createElement('tr');
                var labelCell = document.createElement('td');
                var amountCell = document.createElement('td');

                labelCell.textContent = label;
                amountCell.textContent = currencyFormatter.format(amount);
                amountCell.className = 'text-end fw-semibold';

                row.appendChild(labelCell);
                row.appendChild(amountCell);
                breakdownBody.appendChild(row);
            }

            function profileSorter(profile, slotKey, options) {
                var sorted = options.slice();
                sorted.sort(function (a, b) {
                    var pa = asPrice(a.dataset.price);
                    var pb = asPrice(b.dataset.price);
                    return pa - pb;
                });

                if (profile === 'trabajo') {
                    if (slotKey === 'gpu') {
                        return sorted[0];
                    }
                    return sorted[Math.max(0, sorted.length - 2)] || sorted[0];
                }

                if (profile === 'gaming') {
                    if (slotKey === 'gpu' || slotKey === 'cpu') {
                        return sorted[sorted.length - 1];
                    }
                    return sorted[Math.max(0, sorted.length - 2)] || sorted[sorted.length - 1];
                }

                return sorted[Math.floor((sorted.length - 1) / 2)];
            }

            function applyRecommendation() {
                var profile = profileSelect ? profileSelect.value : '';
                if (!profile) {
                    return;
                }

                selects.forEach(function (select) {
                    var options = Array.from(select.options).filter(function (option) {
                        return option.value !== '';
                    });

                    if (options.length === 0) {
                        return;
                    }

                    var slotKey = select.dataset.slotKey || '';
                    var bestOption = profileSorter(profile, slotKey, options);
                    if (bestOption) {
                        select.value = bestOption.value;
                    }
                });

                updateSummary();
            }

            function evaluateCompatibility() {
                var messages = [];

                var cpu = selectedSpecs('cpu');
                var placa = selectedSpecs('placa_base');
                var ram = selectedSpecs('ram');
                var fuente = selectedSpecs('fuente');
                var gpu = selectedSpecs('gpu');
                var caja = selectedSpecs('caja');
                var refrigeracion = selectedSpecs('refrigeracion');
                var almacenamientoSeleccionado = selectedTextBySlot('almacenamiento') !== '';

                var socketCpu = cpu.socket || null;
                var socketPlaca = placa.socket || null;
                if (socketCpu && socketPlaca && socketCpu !== socketPlaca) {
                    messages.push('CPU y placa base tienen sockets distintos (' + String(socketCpu).toUpperCase() + ' vs ' + String(socketPlaca).toUpperCase() + ').');
                }

                var ramType = ram.ram_tipo || null;
                var placaRamType = placa.ram_tipo || null;
                if (ramType && placaRamType && ramType !== placaRamType) {
                    messages.push('La memoria RAM seleccionada no coincide con el tipo soportado por la placa base.');
                }

                if (selectedTextBySlot('gpu') !== '' && selectedTextBySlot('fuente') === '') {
                    messages.push('Has seleccionado una tarjeta gráfica pero no una fuente de alimentación.');
                }

                var wattsFuente = Number(fuente.power_w || 0);
                if (wattsFuente > 0) {
                    var consumoEstimado = estimatedConsumption();
                    if (wattsFuente < Math.ceil(consumoEstimado * 1.2)) {
                        messages.push('La fuente puede quedarse corta. Recomendación aproximada: al menos ' + Math.ceil(consumoEstimado * 1.2) + 'W.');
                    }
                }

                var gpuLength = Number(gpu.length_mm || 0);
                var maxGpuLength = Number(caja.max_gpu_length_mm || 0);
                if (gpuLength > 0 && maxGpuLength > 0 && gpuLength > maxGpuLength) {
                    messages.push('La tarjeta gráfica seleccionada no cabe en la caja.');
                }

                var coolerHeight = Number(refrigeracion.height_mm || 0);
                var maxCoolerHeight = Number(caja.max_cooler_height_mm || 0);
                if (coolerHeight > 0 && maxCoolerHeight > 0 && coolerHeight > maxCoolerHeight) {
                    messages.push('La refrigeración seleccionada supera la altura máxima de la caja.');
                }

                var coolerSockets = Array.isArray(refrigeracion.supported_sockets) ? refrigeracion.supported_sockets.map(function (s) { return String(s).toLowerCase(); }) : [];
                if (socketCpu && coolerSockets.length > 0 && coolerSockets.indexOf(String(socketCpu).toLowerCase()) === -1) {
                    messages.push('La refrigeración no es compatible con el socket de la CPU.');
                }

                var supportedFormFactors = Array.isArray(caja.supported_form_factors) ? caja.supported_form_factors.map(function (f) { return String(f).toLowerCase(); }) : [];
                var placaFormFactor = placa.form_factor ? String(placa.form_factor).toLowerCase() : '';
                if (placaFormFactor && supportedFormFactors.length > 0 && supportedFormFactors.indexOf(placaFormFactor) === -1) {
                    messages.push('La caja no soporta el formato de placa base seleccionado.');
                }

                if (osCheckbox && osCheckbox.checked && !almacenamientoSeleccionado) {
                    messages.push('Para instalar sistema operativo automáticamente, conviene seleccionar almacenamiento.');
                }

                if (compatibilityWarning) {
                    if (messages.length > 0) {
                        compatibilityWarning.classList.remove('d-none');
                        compatibilityWarning.textContent = messages.join(' ');
                    } else {
                        compatibilityWarning.classList.add('d-none');
                        compatibilityWarning.textContent = '';
                    }
                }

                return messages.length === 0;
            }

            function updateSummary() {
                var total = 0;
                var faltantes = [];
                breakdownBody.innerHTML = '';

                selects.forEach(function (select) {
                    var option = selectedOption(select);
                    var price = asPrice(option ? option.dataset.price : 0);
                    var isRequired = select.dataset.required === '1';

                    if (option && option.value !== '') {
                        var category = select.dataset.category || 'Componente';
                        var name = option.dataset.name || option.textContent;
                        addLine(category + ': ' + name, price);
                        total += price;
                    } else if (isRequired) {
                        faltantes.push(select.dataset.category || 'Componente obligatorio');
                    }
                });

                if (montajeCheckbox && montajeCheckbox.checked) {
                    var montajePrice = asPrice(montajeCheckbox.dataset.price);
                    addLine(montajeCheckbox.dataset.label || 'Montaje', montajePrice);
                    total += montajePrice;
                }

                if (osCheckbox && osCheckbox.checked) {
                    var osOption = selectedOption(osSelect);
                    var osPrice = asPrice(osOption ? osOption.dataset.price : 0);
                    if (osOption && osOption.value !== '') {
                        addLine('Sistema operativo: ' + osOption.textContent, osPrice);
                        total += osPrice;
                    }
                }

                if (breakdownBody.children.length === 0) {
                    addLine('Sin selección de componentes', 0);
                }

                var faltanObligatorios = faltantes.length > 0;
                if (requiredWarning) {
                    if (faltanObligatorios) {
                        requiredWarning.classList.remove('d-none');
                        requiredWarning.textContent = 'Faltan componentes obligatorios: ' + faltantes.join(', ') + '.';
                    } else {
                        requiredWarning.classList.add('d-none');
                        requiredWarning.textContent = '';
                    }
                }

                var compatible = evaluateCompatibility();

                if (buyButton) {
                    buyButton.disabled = faltanObligatorios || !compatible;
                }

                totalNode.textContent = currencyFormatter.format(total);
            }

            if (applyRecommendationBtn) {
                applyRecommendationBtn.addEventListener('click', applyRecommendation);
            }

            if (osCheckbox && osSelect) {
                osCheckbox.addEventListener('change', function () {
                    osSelect.disabled = !osCheckbox.checked;
                    if (!osCheckbox.checked) {
                        osSelect.value = '';
                    }
                    updateSummary();
                });

                osSelect.addEventListener('change', updateSummary);
            }

            selects.forEach(function (select) {
                select.addEventListener('change', updateSummary);
            });

            if (montajeCheckbox) {
                montajeCheckbox.addEventListener('change', updateSummary);
            }

            updateSummary();
        })();
    </script>
@endif
@endsection
