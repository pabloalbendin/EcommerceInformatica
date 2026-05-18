<?php

// Namespace del controlador dentro de la aplicación
namespace App\Http\Controllers\Store;

// Importa la clase Request para gestionar las peticiones HTTP
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Importa el modelo Producto para acceder a los productos de la base de datos
use App\Models\Producto;
use App\Models\Categoria;

// Controlador encargado de la página principal (home)
class HomeController extends Controller
{
    private const TIPOS_COMPONENTE = [
        'cpu' => 'Procesador (CPU)',
        'placa_base' => 'Placa base',
        'ram' => 'Memoria RAM',
        'gpu' => 'Tarjeta gráfica',
        'almacenamiento' => 'Almacenamiento',
        'fuente' => 'Fuente de alimentación',
        'caja' => 'Caja / Chasis',
        'refrigeracion' => 'Refrigeración',
    ];

    // Muestra la página principal con una selección reducida de contenido
    public function index(){
        $productosNuevos = Producto::visibleEnTienda()
            ->with('categoria')
            ->latest()
            ->take(4)
            ->get();

        $categoriasDestacadas = Categoria::visibleEnTienda()
            ->withCount(['productos' => fn ($query) => $query->visibleEnTienda()])
            ->with([
                'productos' => function ($query) {
                    $query->visibleEnTienda()
                        ->with('categoria')
                        ->latest()
                        ->take(3);
                }
            ])
            ->orderByRaw("CASE WHEN descripcion IS NULL OR descripcion = '' THEN 1 ELSE 0 END")
            ->orderByDesc('productos_count')
            ->take(3)
            ->get()
            ->filter(fn ($categoria) => $categoria->productos->isNotEmpty())
            ->values();

        $totalProductos = Producto::visibleEnTienda()->count();

        return view('store.home', compact('productosNuevos', 'categoriasDestacadas', 'totalProductos'));
    }

    // Muestra la página pública de una categoría con sus productos
    public function categoria(Request $request, $id)
    {
        $categoria = Categoria::visibleEnTienda()->findOrFail($id);
        $query = Producto::visibleEnTienda()
            ->with('categoria')
            ->where('id_categoria', $categoria->id);

        $filtros = $request->validate([
            'precio_min' => ['nullable', 'numeric', 'min:0'],
            'precio_max' => ['nullable', 'numeric', 'min:0'],
            'tipo' => ['nullable', 'string'],
            'stock' => ['nullable', 'in:todos,disponible,sin_stock'],
            'orden' => ['nullable', 'in:recientes,precio_asc,precio_desc,nombre_asc,nombre_desc'],
        ]);

        if (isset($filtros['precio_min'])) {
            $query->where('precio', '>=', $filtros['precio_min']);
        }

        if (isset($filtros['precio_max'])) {
            $query->where('precio', '<=', $filtros['precio_max']);
        }

        if (!empty($filtros['tipo'])) {
            $query->where('tipo_componente', $filtros['tipo']);
        }

        if (($filtros['stock'] ?? 'todos') === 'disponible') {
            $query->where('stock', '>', 0);
        }

        if (($filtros['stock'] ?? 'todos') === 'sin_stock') {
            $query->where('stock', '<=', 0);
        }

        $orden = $filtros['orden'] ?? 'recientes';
        match ($orden) {
            'precio_asc' => $query->orderBy('precio'),
            'precio_desc' => $query->orderByDesc('precio'),
            'nombre_asc' => $query->orderBy('nombre'),
            'nombre_desc' => $query->orderByDesc('nombre'),
            default => $query->latest(),
        };

        $productos = $query->get();
        $tiposDisponibles = Producto::visibleEnTienda()
            ->where('id_categoria', $categoria->id)
            ->whereNotNull('tipo_componente')
            ->distinct()
            ->pluck('tipo_componente')
            ->filter()
            ->values();

        $tiposComponente = collect(self::TIPOS_COMPONENTE)
            ->only($tiposDisponibles->all())
            ->all();
        $todasLasCategorias = Categoria::visibleEnTienda()
            ->withCount(['productos' => fn ($query) => $query->visibleEnTienda()])
            ->orderBy('nombre')
            ->get();

        return view('store.categoria', compact('categoria', 'productos', 'todasLasCategorias', 'tiposComponente', 'filtros'));
    }
}
