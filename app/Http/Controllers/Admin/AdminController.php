<?php

// Definición del namespace del controlador dentro de la aplicación
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Importa el modelo Producto para interactuar con la tabla productos
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Usuario;
use App\Models\Categoria;

// Controlador para la parte de administración
class AdminController extends Controller
{
    
    // Método que se ejecuta al acceder al panel de administración
    public function index(){
        $totalProductos = Producto::count();
        $totalCategorias = Categoria::count();
        $totalPedidos = Pedido::count();
        $pedidosPendientes = Pedido::where('id_estado', 1)->count();
        $totalUsuarios = Usuario::count();
        $totalAdmins = Usuario::where('id_rol', 2)->count();
        
        // Devuelve la vista del panel con un resumen general
        return view('admin.dashboard', compact('totalProductos', 'totalCategorias', 'totalPedidos', 'pedidosPendientes', 'totalUsuarios', 'totalAdmins'));
    }

    // Muestra la gestión de productos dentro del backend
    public function productos(Request $request)
    {
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'categoria' => ['nullable', 'integer', 'exists:categorias,id'],
            'stock' => ['nullable', 'in:todos,disponible,sin_stock,bajo_stock'],
            'precio_min' => ['nullable', 'numeric', 'min:0'],
            'precio_max' => ['nullable', 'numeric', 'min:0'],
            'orden' => ['nullable', 'in:recientes,nombre_asc,nombre_desc,precio_asc,precio_desc,stock_asc,stock_desc'],
            'per_page' => ['nullable', 'in:10,50,100'],
        ]);

        $query = Producto::with('categoria');

        if (!empty($filtros['q'])) {
            $texto = trim($filtros['q']);
            $query->where(function ($subQuery) use ($texto) {
                $subQuery->where('nombre', 'like', "%{$texto}%")
                    ->orWhere('descripcion', 'like', "%{$texto}%")
                    ->orWhereHas('categoria', fn ($categoriaQuery) => $categoriaQuery->where('nombre', 'like', "%{$texto}%"));

                if (is_numeric($texto)) {
                    $subQuery->orWhere('id', (int) $texto);
                }
            });
        }

        if (!empty($filtros['categoria'])) {
            $query->where('id_categoria', $filtros['categoria']);
        }

        if (isset($filtros['precio_min'])) {
            $query->where('precio', '>=', $filtros['precio_min']);
        }

        if (isset($filtros['precio_max'])) {
            $query->where('precio', '<=', $filtros['precio_max']);
        }

        $stock = $filtros['stock'] ?? 'todos';
        if ($stock === 'disponible') {
            $query->where('stock', '>', 0);
        }
        if ($stock === 'sin_stock') {
            $query->where('stock', '<=', 0);
        }
        if ($stock === 'bajo_stock') {
            $query->whereBetween('stock', [1, 5]);
        }

        $orden = $filtros['orden'] ?? 'recientes';
        match ($orden) {
            'nombre_asc' => $query->orderBy('nombre'),
            'nombre_desc' => $query->orderByDesc('nombre'),
            'precio_asc' => $query->orderBy('precio'),
            'precio_desc' => $query->orderByDesc('precio'),
            'stock_asc' => $query->orderBy('stock'),
            'stock_desc' => $query->orderByDesc('stock'),
            default => $query->latest(),
        };

        $perPage = (int) ($filtros['per_page'] ?? 10);
        $productos = $query->paginate($perPage)->withQueryString();
        $categorias = Categoria::orderBy('nombre')->get();

        // Devuelve la vista de productos del backend
        return view('admin.productos.index', compact('productos', 'categorias', 'filtros'));
    }
}
