<?php

namespace App\Services;

use Illuminate\Support\Collection;

class CompatibilityService
{
    public function evaluate(Collection $productos): array
    {
        $componentes = $productos
            ->filter(fn ($producto) => !empty($producto->tipo_componente))
            ->keyBy('tipo_componente');

        $errores = [];
        $avisos = [];

        $cpu = $componentes->get('cpu');
        $placa = $componentes->get('placa_base');
        $ram = $componentes->get('ram');
        $gpu = $componentes->get('gpu');
        $fuente = $componentes->get('fuente');
        $caja = $componentes->get('caja');
        $refrigeracion = $componentes->get('refrigeracion');

        $cpuSocket = data_get($cpu, 'specs.socket');
        $placaSocket = data_get($placa, 'specs.socket');
        if ($cpuSocket && $placaSocket && $cpuSocket !== $placaSocket) {
            $errores[] = "Incompatibilidad de socket CPU/placa ({$cpuSocket} vs {$placaSocket}).";
        }

        $ramTipo = data_get($ram, 'specs.ram_tipo');
        $placaRamTipo = data_get($placa, 'specs.ram_tipo');
        if ($ramTipo && $placaRamTipo && $ramTipo !== $placaRamTipo) {
            $errores[] = "Incompatibilidad de memoria RAM ({$ramTipo}) con placa base ({$placaRamTipo}).";
        }

        $placaFormFactor = data_get($placa, 'specs.form_factor');
        $cajaFormFactors = collect(data_get($caja, 'specs.supported_form_factors', []))
            ->map(fn ($v) => strtolower((string) $v))
            ->all();
        if ($placaFormFactor && !empty($cajaFormFactors) && !in_array(strtolower((string) $placaFormFactor), $cajaFormFactors, true)) {
            $errores[] = "La caja no soporta el formato de placa base ({$placaFormFactor}).";
        }

        $gpuLength = (int) data_get($gpu, 'specs.length_mm', 0);
        $cajaGpuMax = (int) data_get($caja, 'specs.max_gpu_length_mm', 0);
        if ($gpuLength > 0 && $cajaGpuMax > 0 && $gpuLength > $cajaGpuMax) {
            $errores[] = "La tarjeta gráfica ({$gpuLength} mm) no cabe en la caja ({$cajaGpuMax} mm máx.).";
        }

        $coolerHeight = (int) data_get($refrigeracion, 'specs.height_mm', 0);
        $cajaCoolerMax = (int) data_get($caja, 'specs.max_cooler_height_mm', 0);
        if ($coolerHeight > 0 && $cajaCoolerMax > 0 && $coolerHeight > $cajaCoolerMax) {
            $errores[] = "La refrigeración ({$coolerHeight} mm) supera la altura soportada por la caja ({$cajaCoolerMax} mm).";
        }

        $coolerSockets = collect(data_get($refrigeracion, 'specs.supported_sockets', []))
            ->map(fn ($v) => strtolower((string) $v))
            ->all();
        if ($cpuSocket && !empty($coolerSockets) && !in_array(strtolower((string) $cpuSocket), $coolerSockets, true)) {
            $errores[] = "La refrigeración no soporta el socket de la CPU ({$cpuSocket}).";
        }

        $gpuTdp = (int) data_get($gpu, 'specs.tdp_w', 0);
        $cpuTdp = (int) data_get($cpu, 'specs.tdp_w', 0);
        $fuenteW = (int) data_get($fuente, 'specs.power_w', 0);
        if ($fuenteW > 0 && ($gpuTdp > 0 || $cpuTdp > 0)) {
            $consumoEstimado = $gpuTdp + $cpuTdp + 150;
            $potenciaRecomendada = (int) ceil($consumoEstimado * 1.2);

            if ($fuenteW < $potenciaRecomendada) {
                $errores[] = "La fuente ({$fuenteW}W) es insuficiente para una recomendación de {$potenciaRecomendada}W.";
            } elseif ($fuenteW < $consumoEstimado) {
                $avisos[] = "La fuente está cerca del límite de consumo estimado.";
            }
        }

        return [
            'compatible' => empty($errores),
            'errors' => $errores,
            'warnings' => $avisos,
        ];
    }
}
