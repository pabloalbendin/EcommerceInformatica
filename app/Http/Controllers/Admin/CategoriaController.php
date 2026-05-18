<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    private const MAX_CATEGORIAS_MENU = 7;

    // Muestra el listado de categorías del backend
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'menu' => ['nullable', 'in:todos,en_menu,fuera_menu'],
            'productos' => ['nullable', 'in:todos,con_productos,sin_productos'],
            'orden' => ['nullable', 'in:default,nombre_asc,nombre_desc,productos_desc,productos_asc'],
            'per_page' => ['nullable', 'in:10,50,100'],
        ]);

        $query = Categoria::withCount('productos');

        if (!empty($filtros['q'])) {
            $texto = trim($filtros['q']);
            $query->where(function ($subQuery) use ($texto) {
                $subQuery->where('nombre', 'like', "%{$texto}%")
                    ->orWhere('descripcion', 'like', "%{$texto}%");

                if (is_numeric($texto)) {
                    $subQuery->orWhere('id', (int) $texto);
                }
            });
        }

        $menu = $filtros['menu'] ?? 'todos';
        if ($menu === 'en_menu') {
            $query->where('mostrar_en_menu', true);
        }
        if ($menu === 'fuera_menu') {
            $query->where('mostrar_en_menu', false);
        }

        $filtroProductos = $filtros['productos'] ?? 'todos';
        if ($filtroProductos === 'con_productos') {
            $query->having('productos_count', '>', 0);
        }
        if ($filtroProductos === 'sin_productos') {
            $query->having('productos_count', '=', 0);
        }

        $orden = $filtros['orden'] ?? 'default';
        match ($orden) {
            'nombre_asc' => $query->orderBy('nombre'),
            'nombre_desc' => $query->orderByDesc('nombre'),
            'productos_desc' => $query->orderByDesc('productos_count')->orderBy('nombre'),
            'productos_asc' => $query->orderBy('productos_count')->orderBy('nombre'),
            default => $query
                ->orderByRaw('CASE WHEN mostrar_en_menu = 1 THEN 0 ELSE 1 END')
                ->orderBy('orden_menu')
                ->orderBy('nombre'),
        };

        $perPage = (int) ($filtros['per_page'] ?? 10);
        $categorias = $query->paginate($perPage)->withQueryString();

        return view('admin.categorias.index', compact('categorias', 'filtros'));
    }

    // Muestra el formulario para crear una categoría
    public function crearIndex()
    {
        return view('admin.categorias.form');
    }

    // Guarda una nueva categoría
    public function crear(Request $request)
    {
        $datosValidados = $request->validate(
            [
                'nombre' => 'required|string|max:255|unique:categorias,nombre',
                'descripcion' => 'nullable|string',
            ],
            [
                'nombre.required' => 'El nombre de la categoría es obligatorio.',
                'nombre.unique' => 'Ya existe una categoría con ese nombre.',
            ]
        );

        Categoria::create($datosValidados);

        return redirect()->route('admin.categorias')->with('success', 'Categoría creada correctamente.');
    }

    // Muestra el formulario para editar una categoría existente
    public function editarIndex($id)
    {
        $categoria = Categoria::findOrFail($id);

        return view('admin.categorias.form', compact('categoria'));
    }

    // Actualiza una categoría existente
    public function editar(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $datosValidados = $request->validate(
            [
                'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
                'descripcion' => 'nullable|string',
            ],
            [
                'nombre.required' => 'El nombre de la categoría es obligatorio.',
                'nombre.unique' => 'Ya existe una categoría con ese nombre.',
            ]
        );

        $categoria->update($datosValidados);

        return redirect()->route('admin.categorias')->with('success', 'Categoría actualizada correctamente.');
    }

    // Elimina una categoría si no tiene productos asociados
    public function delete(Request $request)
    {
        $categoria = Categoria::withCount('productos')->findOrFail($request->id);

        if ($categoria->productos_count > 0) {
            return redirect()->route('admin.categorias')->with('error', 'No puedes eliminar una categoría que tiene productos asociados.');
        }

        $categoria->delete();

        return redirect()->route('admin.categorias')->with('success', 'Categoría eliminada correctamente.');
    }

    public function actualizarMenu(Request $request)
    {
        $datos = $request->validate([
            'categorias_menu' => ['nullable', 'array', 'max:' . self::MAX_CATEGORIAS_MENU],
            'categorias_menu.*' => ['integer', 'exists:categorias,id'],
        ], [
            'categorias_menu.max' => 'Solo puedes mostrar hasta 7 categorías en el menú principal.',
        ]);

        $categoriasSeleccionadas = array_values($datos['categorias_menu'] ?? []);

        Categoria::query()->update([
            'mostrar_en_menu' => false,
            'orden_menu' => null,
        ]);

        foreach ($categoriasSeleccionadas as $indice => $categoriaId) {
            Categoria::where('id', $categoriaId)->update([
                'mostrar_en_menu' => true,
                'orden_menu' => $indice + 1,
            ]);
        }

        return redirect()->route('admin.categorias')->with('success', 'Menú principal actualizado correctamente.');
    }
}
