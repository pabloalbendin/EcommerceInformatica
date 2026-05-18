<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConfiguradorController extends Controller
{
    public function __construct(private CompatibilityService $compatibilityService)
    {
    }

    public function index()
    {
        $productos = Producto::with('categoria')
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        $slots = [
            ['key' => 'cpu', 'label' => 'Procesador (CPU)', 'required' => true, 'component_type' => 'cpu', 'keywords' => ['cpu', 'procesador', 'ryzen', 'intel core', 'threadripper']],
            ['key' => 'placa_base', 'label' => 'Placa base', 'required' => true, 'component_type' => 'placa_base', 'keywords' => ['placa', 'motherboard', 'chipset', 'am5', 'lga1700']],
            ['key' => 'ram', 'label' => 'Memoria RAM', 'required' => true, 'component_type' => 'ram', 'keywords' => ['ram', 'ddr4', 'ddr5']],
            ['key' => 'gpu', 'label' => 'Tarjeta gráfica', 'required' => true, 'component_type' => 'gpu', 'keywords' => ['grafica', 'gráfica', 'gpu', 'rtx', 'radeon']],
            ['key' => 'almacenamiento', 'label' => 'Almacenamiento', 'required' => true, 'component_type' => 'almacenamiento', 'keywords' => ['ssd', 'hdd', 'nvme', 'm.2']],
            ['key' => 'fuente', 'label' => 'Fuente de alimentación', 'required' => true, 'component_type' => 'fuente', 'keywords' => ['fuente', 'psu', 'watt', '80 plus']],
            ['key' => 'caja', 'label' => 'Caja / Chasis', 'required' => true, 'component_type' => 'caja', 'keywords' => ['caja', 'chasis', 'tower', 'gabinete']],
            ['key' => 'refrigeracion', 'label' => 'Refrigeración', 'required' => false, 'component_type' => 'refrigeracion', 'keywords' => ['refrigeracion', 'refrigeración', 'cooler', 'disipador', 'liquida', 'líquida']],
            ['key' => 'monitor', 'label' => 'Monitor (opcional)', 'required' => false, 'keywords' => ['monitor', 'display']],
            ['key' => 'teclado', 'label' => 'Teclado (opcional)', 'required' => false, 'keywords' => ['teclado']],
            ['key' => 'raton', 'label' => 'Ratón (opcional)', 'required' => false, 'keywords' => ['raton', 'ratón', 'mouse']],
            ['key' => 'auriculares', 'label' => 'Auriculares (opcional)', 'required' => false, 'keywords' => ['auricular', 'headset', 'cascos']],
        ];

        $bloques = collect($slots)->map(function ($slot) use ($productos) {
            $slotProductos = $productos->filter(function ($producto) use ($slot) {
                return $this->productoCoincideConSlot($producto, $slot);
            })->values();

            return [
                'key' => $slot['key'],
                'label' => $slot['label'],
                'required' => $slot['required'],
                'productos' => $slotProductos,
            ];
        });

        $servicioMontaje = 59.90;
        $opcionesSistemaOperativo = [
            ['id' => 'windows-11-home', 'nombre' => 'Windows 11 Home', 'precio' => 129.00],
            ['id' => 'windows-11-pro', 'nombre' => 'Windows 11 Pro', 'precio' => 199.00],
            ['id' => 'ubuntu', 'nombre' => 'Ubuntu (Software libre)', 'precio' => 0.00],
        ];

        return view('store.configurador', compact('bloques', 'servicioMontaje', 'opcionesSistemaOperativo'));
    }

    public function comprar(Request $request)
    {
        $idsSeleccionados = collect($request->input('componentes', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($idsSeleccionados->isEmpty()) {
            return redirect()->route('configurador')
                ->with('error', 'Selecciona al menos un componente para continuar a la compra.');
        }

        $productos = Producto::whereIn('id', $idsSeleccionados)->get()->keyBy('id');

        if ($productos->count() !== $idsSeleccionados->count()) {
            return redirect()->route('configurador')
                ->with('error', 'Se han detectado productos no válidos en la configuración.');
        }

        $lineasCheckout = $idsSeleccionados->mapWithKeys(fn ($idProducto) => [$idProducto => 1])->toArray();

        if ($request->boolean('incluye_montaje')) {
            $idMontaje = $this->resolverServicio(
                'Servicio de montaje de PC',
                'Montaje profesional del equipo por parte del servicio técnico.',
                59.90
            );
            $lineasCheckout[$idMontaje] = 1;
        }

        if ($request->boolean('incluye_so')) {
            $so = $request->input('so_opcion');
            $opcionesSo = [
                'windows-11-home' => ['nombre' => 'Instalación Windows 11 Home', 'precio' => 129.00],
                'windows-11-pro' => ['nombre' => 'Instalación Windows 11 Pro', 'precio' => 199.00],
                'ubuntu' => ['nombre' => 'Instalación Ubuntu', 'precio' => 0.00],
            ];

            if (isset($opcionesSo[$so])) {
                $config = $opcionesSo[$so];
                $idSo = $this->resolverServicio(
                    $config['nombre'],
                    'Instalación y configuración inicial del sistema operativo.',
                    $config['precio']
                );
                $lineasCheckout[$idSo] = 1;
            }
        }

        $productosCheckout = Producto::whereIn('id', array_keys($lineasCheckout))->get()->keyBy('id');
        foreach ($lineasCheckout as $idProducto => $cantidad) {
            $producto = $productosCheckout[$idProducto] ?? null;
            if (!$producto || $cantidad > $producto->stock) {
                return redirect()->route('configurador')
                    ->with('error', $producto ? "No hay stock suficiente para {$producto->nombre}." : 'Hay un producto no válido en la configuración.');
            }
        }

        $resultadoCompatibilidad = $this->compatibilityService->evaluate(
            $productosCheckout->filter(fn ($producto) => !empty($producto->tipo_componente))
        );

        if (!$resultadoCompatibilidad['compatible']) {
            return redirect()->route('configurador')
                ->with('error', 'Configuración incompatible: ' . implode(' ', $resultadoCompatibilidad['errors']));
        }

        // Limpia posibles restos de esta configuración en el carrito para que no queden pendientes.
        $carrito = session()->get('carrito', []);
        foreach (array_keys($lineasCheckout) as $idProducto) {
            unset($carrito[$idProducto]);
        }

        session()->put('carrito', $carrito);
        session()->put('configurador_checkout', $lineasCheckout);

        return redirect()->route('pago.configuracion')
            ->with('success', 'Configuración lista. Completa el pago para confirmar el pedido.');
    }

    private function productoCoincideConSlot(Producto $producto, array $slot): bool
    {
        $slotType = $slot['component_type'] ?? null;
        $keywords = $slot['keywords'] ?? [];

        if ($slotType && !empty($producto->tipo_componente)) {
            return $producto->tipo_componente === $slotType;
        }

        $texto = Str::lower($producto->nombre . ' ' . ($producto->descripcion ?? '') . ' ' . ($producto->categoria->nombre ?? ''));

        foreach ($keywords as $keyword) {
            if (Str::contains($texto, Str::lower($keyword))) {
                return true;
            }
        }

        return false;
    }

    private function resolverServicio(string $nombre, string $descripcion, float $precio): int
    {
        $categoriaServicios = Categoria::firstOrCreate(
            ['nombre' => 'Servicios'],
            ['descripcion' => 'Servicios de montaje, instalación y puesta a punto.']
        );

        $producto = Producto::withTrashed()->firstOrNew(['nombre' => $nombre]);

        $producto->id_categoria = $categoriaServicios->id;
        $producto->descripcion = $descripcion;
        $producto->precio = $precio;
        $producto->stock = 9999;
        $producto->imagen = $producto->imagen ?: null;
        $producto->tipo_componente = null;

        if ($producto->trashed()) {
            $producto->restore();
        }

        $producto->save();

        return $producto->id;
    }
}
