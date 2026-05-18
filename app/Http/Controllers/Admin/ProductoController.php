<?php

// Namespace del controlador dentro de la aplicación
namespace App\Http\Controllers\Admin;

// Importa la clase Request para manejar peticiones HTTP
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Importa los modelos necesarios para gestionar productos y categorías
use App\Models\Producto;
use App\Models\Categoria;

// Importa la fachada Storage para manejar archivos (imágenes)
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// Controlador encargado de la gestión de productos (CRUD)
class ProductoController extends Controller
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

    // Elimina un producto existente
    public function delete(Request $request){
        
        // Obtiene el ID del producto desde la petición
        $id = $request->id;

        // Busca el producto por su ID
        $producto = Producto::find($id);

        // Elimina el producto de la base de datos
        $producto->delete();

        // Vuelve atrás con un mensaje de confirmación
        return redirect()->route('admin.productos')->with('success', 'Producto eliminado correctamente');

    }

    // Muestra el formulario para crear un nuevo producto
    public function crearIndex(){
        
        // Obtiene todas las categorías disponibles
        $categorias = Categoria::all();
        $tiposComponente = self::TIPOS_COMPONENTE;

        // Devuelve la vista del formulario de producto
        return view('admin.productos.form', compact('categorias', 'tiposComponente'));
    }

    // Muestra el formulario para editar un producto existente
    public function editarIndex($id){
        
        // Busca el producto por su ID o lanza error si no existe
        $producto = Producto::findOrFail($id);

        // Obtiene todas las categorías disponibles
        $categorias = Categoria::all();
        $tiposComponente = self::TIPOS_COMPONENTE;

        // Devuelve la vista del formulario con el producto y las categorías
        return view('admin.productos.form', compact('producto', 'categorias', 'tiposComponente'));
    }

    // Procesa la creación de un nuevo producto
    public function crear(Request $request)
    {
        $datosValidados = $this->validarProducto($request);

        // Crea una nueva instancia del producto
        $producto = new Producto();

        // Asigna los valores al producto
        $producto->nombre = $datosValidados['nombre'];
        $producto->descripcion = $datosValidados['descripcion'] ?? null;
        $producto->precio = $datosValidados['precio'];
        $producto->stock = $datosValidados['stock'];
        $producto->id_categoria = $datosValidados['id_categoria'];
        $producto->tipo_componente = $this->resolverTipoComponente($datosValidados);
        $producto->specs = $this->resolverSpecs($datosValidados);

        // Si se ha subido una imagen, la guarda en el almacenamiento público
        if ($request->hasFile('imagen')) {
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        // Guarda el producto en la base de datos
        $producto->save();

        // Redirige al panel de administración con un mensaje de éxito
        return redirect()->route('admin.productos')->with('success', 'Producto creado correctamente');
    }

    // Procesa la edición de un producto existente
    public function editar(Request $request, $id)
    {
        // Busca el producto por su ID o lanza error si no existe
        $producto = Producto::findOrFail($id);

        $datosValidados = $this->validarProducto($request);

        // Actualiza los datos del producto
        $producto->nombre = $datosValidados['nombre'];
        $producto->descripcion = $datosValidados['descripcion'] ?? null;
        $producto->precio = $datosValidados['precio'];
        $producto->stock = $datosValidados['stock'];
        $producto->id_categoria = $datosValidados['id_categoria'];
        $producto->tipo_componente = $this->resolverTipoComponente($datosValidados);
        $producto->specs = $this->resolverSpecs($datosValidados);

        // Si se ha subido una nueva imagen
        if ($request->hasFile('imagen')) {
            
            // Elimina la imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            // Guarda la nueva imagen
            $producto->imagen = $request->file('imagen')->store('productos', 'public');
        }

        // Guarda los cambios del producto
        $producto->save();

        // Redirige al panel de administración con un mensaje de éxito
        return redirect()->route('admin.productos')->with('success', 'Producto actualizado correctamente');
    }

    private function validarProducto(Request $request): array
    {
        $categoriaSeleccionada = Categoria::find($request->id_categoria);
        $esComponente = $categoriaSeleccionada && $this->esCategoriaComponentes($categoriaSeleccionada);

        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'id_categoria' => ['required', 'exists:categorias,id'],
            'tipo_componente' => [
                Rule::requiredIf($esComponente),
                'nullable',
                'string',
                Rule::in(array_keys(self::TIPOS_COMPONENTE)),
            ],
            'specs' => ['nullable', 'array'],
            'specs.socket' => ['nullable', 'string', 'max:30'],
            'specs.tdp_w' => ['nullable', 'integer', 'min:1', 'max:2000'],
            'specs.ram_tipo' => ['nullable', Rule::in(['ddr4', 'ddr5'])],
            'specs.form_factor' => ['nullable', Rule::in(['atx', 'matx', 'itx'])],
            'specs.modules' => ['nullable', 'integer', 'min:1', 'max:8'],
            'specs.capacity_gb' => ['nullable', 'integer', 'min:1', 'max:1024'],
            'specs.speed_mhz' => ['nullable', 'integer', 'min:800', 'max:10000'],
            'specs.interface' => ['nullable', 'string', 'max:30'],
            'specs.length_mm' => ['nullable', 'integer', 'min:1', 'max:2000'],
            'specs.power_w' => ['nullable', 'integer', 'min:50', 'max:3000'],
            'specs.max_gpu_length_mm' => ['nullable', 'integer', 'min:100', 'max:1000'],
            'specs.max_cooler_height_mm' => ['nullable', 'integer', 'min:50', 'max:500'],
            'specs.height_mm' => ['nullable', 'integer', 'min:20', 'max:500'],
            'specs.supported_form_factors' => ['nullable', 'array'],
            'specs.supported_form_factors.*' => [Rule::in(['atx', 'matx', 'itx'])],
            'specs.supported_sockets' => ['nullable', 'array'],
            'specs.supported_sockets.*' => ['string', 'max:30'],
            'imagen' => ['nullable', 'image', 'max:2048'],
        ], [
            'tipo_componente.required' => 'Debes indicar el tipo de componente para esta categoría.',
            'tipo_componente.in' => 'El tipo de componente seleccionado no es válido.',
        ]);
    }

    private function resolverTipoComponente(array $datosValidados): ?string
    {
        $categoria = Categoria::find($datosValidados['id_categoria']);

        if (!$categoria || !$this->esCategoriaComponentes($categoria)) {
            return null;
        }

        return $datosValidados['tipo_componente'] ?? null;
    }

    private function resolverSpecs(array $datosValidados): ?array
    {
        if (empty($datosValidados['tipo_componente'])) {
            return null;
        }

        $specs = $datosValidados['specs'] ?? [];

        $allowedByType = [
            'cpu' => ['socket', 'tdp_w'],
            'placa_base' => ['socket', 'ram_tipo', 'form_factor'],
            'ram' => ['ram_tipo', 'modules', 'capacity_gb', 'speed_mhz'],
            'gpu' => ['tdp_w', 'length_mm'],
            'almacenamiento' => ['interface', 'capacity_gb'],
            'fuente' => ['power_w'],
            'caja' => ['supported_form_factors', 'max_gpu_length_mm', 'max_cooler_height_mm'],
            'refrigeracion' => ['supported_sockets', 'height_mm'],
        ];

        $tipo = $datosValidados['tipo_componente'];
        $allowed = $allowedByType[$tipo] ?? [];
        $result = [];

        foreach ($allowed as $key) {
            if (!array_key_exists($key, $specs)) {
                continue;
            }

            $value = $specs[$key];

            if (is_array($value)) {
                $filtered = array_values(array_filter($value, fn ($item) => $item !== null && $item !== ''));
                if (!empty($filtered)) {
                    $result[$key] = $filtered;
                }
            } elseif ($value !== null && $value !== '') {
                $result[$key] = $value;
            }
        }

        return empty($result) ? null : $result;
    }

    private function esCategoriaComponentes(Categoria $categoria): bool
    {
        return Str::lower(Str::ascii($categoria->nombre)) === 'componentes';
    }
}
