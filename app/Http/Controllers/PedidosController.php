<?php

// Namespace del controlador dentro de la aplicación
namespace App\Http\Controllers;

// Importa la clase Request para manejar peticiones HTTP
use Illuminate\Http\Request;

// Importa los modelos necesarios para gestionar pedidos
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DatosPedido;
use App\Models\EstadoPedido;

// Importa la fachada Auth para obtener el usuario autenticado
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Controlador encargado de la gestión de pedidos
class PedidosController extends Controller
{
    // Muestra la pasarela de pago de pruebas antes de crear el pedido
    public function checkout()
    {
        $carrito = session()->get('carrito', []);
        $productos = $this->obtenerProductosDesdeLineas($carrito);

        if ($productos->isEmpty()) {
            return redirect()->route('carrito')
                ->with('error', 'El carrito está vacío');
        }

        $total = $productos->sum(fn ($producto) => $producto->precio * $producto->cantidad);
        $checkoutSource = 'carrito';
        $volverRoute = 'carrito';

        return view('store.pago', compact('productos', 'total', 'checkoutSource', 'volverRoute'));
    }

    public function checkoutConfiguracion()
    {
        $lineas = session()->get('configurador_checkout', []);
        $productos = $this->obtenerProductosDesdeLineas($lineas);

        if ($productos->isEmpty()) {
            return redirect()->route('configurador')
                ->with('error', 'La configuración no está disponible para compra.');
        }

        $total = $productos->sum(fn ($producto) => $producto->precio * $producto->cantidad);
        $checkoutSource = 'configurador';
        $volverRoute = 'configurador';

        return view('store.pago', compact('productos', 'total', 'checkoutSource', 'volverRoute'));
    }

    // Procesa el pago y genera un pedido a partir del carrito
    public function pago(Request $request){
        $request->validate([
            'titular' => ['required', 'string', 'max:120'],
            'numero_tarjeta' => ['required', 'regex:/^[0-9 ]{16,19}$/'],
            'caducidad' => ['required', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
            'cvv' => ['required', 'digits_between:3,4'],
        ], [
            'titular.required' => 'Introduce el titular de la tarjeta de prueba.',
            'numero_tarjeta.required' => 'Introduce un número de tarjeta de prueba.',
            'numero_tarjeta.regex' => 'El número de tarjeta debe tener 16 dígitos.',
            'caducidad.required' => 'Introduce la caducidad de la tarjeta de prueba.',
            'caducidad.regex' => 'La caducidad debe tener el formato MM/AA.',
            'cvv.required' => 'Introduce el CVV de prueba.',
            'cvv.digits_between' => 'El CVV debe tener 3 o 4 dígitos.',
        ]);

        $checkoutSource = $request->input('checkout_source', 'carrito');
        $lineasCompra = $checkoutSource === 'configurador'
            ? session()->get('configurador_checkout', [])
            : session()->get('carrito', []);

        // Comprueba si el carrito está vacío
        if (empty($lineasCompra)) {
            return redirect()->route($checkoutSource === 'configurador' ? 'configurador' : 'carrito')
                ->with('error', 'No hay productos para procesar el pago.');
        }

        // Verifica que exista stock suficiente para cada producto del carrito
        foreach ($lineasCompra as $idProducto => $cantidad) {

            // Busca el producto por su ID
            $producto = Producto::find($idProducto);

            // Si el producto no existe o no hay suficiente stock, redirige con error
            if (!$producto || $cantidad > $producto->stock) {
                return redirect()->route($checkoutSource === 'configurador' ? 'configurador' : 'carrito')
                    ->with('error', 'Stock insuficiente');
            }
        }

        $idEstadoPendiente = EstadoPedido::where('nombre', 'Pendiente')->value('id') ?? 1;

        try {
            DB::transaction(function () use ($lineasCompra, $idEstadoPendiente) {
                // Crea un nuevo pedido con estado pendiente para revisión de admin
                $pedido = Pedido::create([
                    'fecha' => now(),
                    'id_usuario' => Auth::user()->id,
                    'id_estado' => $idEstadoPendiente,
                ]);

                // Recorre el carrito para guardar los datos del pedido
                foreach ($lineasCompra as $idProducto => $cantidad) {

                    // Bloquea el producto mientras se descuenta el stock
                    $producto = Producto::lockForUpdate()->find($idProducto);

                    if (!$producto || $cantidad > $producto->stock) {
                        throw new \RuntimeException('Stock insuficiente');
                    }

                    // Crea el registro del producto dentro del pedido
                    DatosPedido::create([
                        'id_pedido' => $pedido->id,
                        'id_producto' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio' => $producto->precio,
                    ]);

                    // Reduce el stock del producto según la cantidad comprada
                    $producto->stock -= $cantidad;

                    // Guarda el nuevo stock en la base de datos
                    $producto->save();
                }
            });
        } catch (\RuntimeException $exception) {
            return redirect()->route($checkoutSource === 'configurador' ? 'configurador' : 'carrito')
                ->with('error', 'Stock insuficiente');
        }

        if ($checkoutSource === 'configurador') {
            session()->forget('configurador_checkout');
        } else {
            // Vacía el carrito de la sesión tras completar el pedido
            session()->forget('carrito');
        }

        // Muestra la vista de confirmación de compra
        return view('store.exito')->with('referenciaPago', 'TEST-' . now()->format('YmdHis'));
    }

    private function obtenerProductosDesdeLineas(array $lineas)
    {
        $idsProductos = array_keys($lineas);
        $productos = Producto::with('categoria')->whereIn('id', $idsProductos)->get();

        foreach ($productos as $producto) {
            $producto->cantidad = $lineas[$producto->id];
        }

        return $productos;
    }

    // Muestra el panel de administración de pedidos
    public function verAdmin(Request $request){
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'estado' => ['nullable', 'integer', 'exists:estado_pedidos,id'],
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date'],
            'orden' => ['nullable', 'in:recientes,antiguos'],
            'per_page' => ['nullable', 'in:10,50,100'],
        ]);

        // Obtiene pedidos junto con su usuario, estado y líneas de producto
        $query = Pedido::with(['usuario', 'estado', 'datosPedido.producto']);

        if (!empty($filtros['q'])) {
            $texto = trim($filtros['q']);
            $query->where(function ($subQuery) use ($texto) {
                $subQuery->whereHas('usuario', function ($usuarioQuery) use ($texto) {
                    $usuarioQuery->where('nombre', 'like', "%{$texto}%")
                        ->orWhere('correo', 'like', "%{$texto}%");
                });

                if (is_numeric($texto)) {
                    $subQuery->orWhere('id', (int) $texto);
                }
            });
        }

        if (!empty($filtros['estado'])) {
            $query->where('id_estado', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('fecha', '>=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('fecha', '<=', $filtros['fecha_hasta']);
        }

        $queryMetrica = clone $query;

        $orden = $filtros['orden'] ?? 'recientes';
        if ($orden === 'antiguos') {
            $query->orderBy('fecha');
        } else {
            $query->orderByDesc('fecha');
        }

        $perPage = (int) ($filtros['per_page'] ?? 10);
        $pedidos = $query->paginate($perPage)->withQueryString();

        // Obtiene todos los posibles estados de pedido
        $estados = EstadoPedido::orderBy('nombre')->get();

        $totalPedidos = (clone $queryMetrica)->count();
        $pedidosPendientes = (clone $queryMetrica)->where('id_estado', 1)->count();
        $pedidosCompletados = (clone $queryMetrica)->where('id_estado', 3)->count();
        $facturacionTotal = DatosPedido::query()
            ->whereIn('id_pedido', (clone $queryMetrica)->select('id'))
            ->selectRaw('COALESCE(SUM(precio * cantidad), 0) as total')
            ->value('total');

        // Devuelve la vista de pedidos pasando los datos necesarios
        return view('admin.pedidos.index', compact('pedidos', 'estados', 'totalPedidos', 'pedidosPendientes', 'pedidosCompletados', 'facturacionTotal', 'filtros'));

    }


    // Permite al administrador cambiar el estado de un pedido
    public function editarEstado(Request $request , $id){

        // Busca el pedido por su ID
        $pedido = Pedido::find($id);

        // Actualiza el estado del pedido
        $pedido->id_estado = $request->id_estado;

        // Guarda los cambios en la base de datos
        $pedido->save();

        // Vuelve atrás con un mensaje de éxito
        return redirect()->back()->with('success', 'Estado del pedido actualizado');
    }

}
    
