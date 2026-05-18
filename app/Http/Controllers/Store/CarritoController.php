<?php

// Namespace del controlador dentro de la aplicación
namespace App\Http\Controllers\Store;

// Importa la clase Request para manejar peticiones HTTP
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

// Importa el modelo Producto para acceder a los productos de la base de datos
use App\Models\Producto;

// Controlador encargado de gestionar el carrito de la compra
class CarritoController extends Controller
{
    // Redirige al login con un aviso cuando el usuario intenta usar el carrito sin autenticarse
    private function redirigirSiNoAutenticado()
    {
        if (Auth::check()) {
            return null;
        }

        return redirect()->route('login')->with(
            'auth_notice',
            'Debes iniciar sesión o registrarte para añadir productos al carrito y poder verlo.'
        );
    }

    // Añade un producto al carrito
    public function anyadir($id){
        $redirect = $this->redirigirSiNoAutenticado();

        if ($redirect) {
            return $redirect;
        }
        
        // Busca el producto por su ID
        $producto = Producto::find($id);

        // Obtiene el carrito de la sesión o un array vacío si no existe
        $carrito = session()->get('carrito', []);

        // Obtiene la cantidad actual del producto en el carrito (o 0 si no existe)
        $cantidadActual = $carrito[$id] ?? 0;

        // Comprueba si al añadir uno más se supera el stock disponible
        if ($cantidadActual + 1 > $producto->stock) {
            return redirect()->back()->with('error', 'No hay stock suficiente para este producto');
        }

        // Incrementa la cantidad del producto en el carrito
        $carrito[$id] = $cantidadActual + 1;

        // Guarda el carrito actualizado en la sesión
        session()->put('carrito', $carrito);

        // Redirige a la página principal con un mensaje de éxito
        return redirect('/')->with('success', 'Producto añadido al carrito');
    }

    // Muestra el contenido del carrito
    public function index(){
        $redirect = $this->redirigirSiNoAutenticado();

        if ($redirect) {
            return $redirect;
        }

        // Obtiene el carrito desde la sesión
        $carrito = session()->get('carrito', []);

        // Obtiene los IDs de los productos almacenados en el carrito
        $idsProductos = array_keys($carrito);

        // Obtiene los productos junto con su categoría usando los IDs del carrito
        $productos = Producto::with('categoria')->whereIn('id', $idsProductos)->get();

        // Asigna a cada producto la cantidad correspondiente del carrito
        foreach ($productos as $producto) {
            $producto->cantidad = $carrito[$producto->id];
        }

        // Devuelve la vista del carrito pasando los productos
        return view('store.carrito', compact('productos'));

    }

    // Actualiza la cantidad de un producto en el carrito
    public function cantidad($id, $cantidad)
    {
        $redirect = $this->redirigirSiNoAutenticado();

        if ($redirect) {
            return $redirect;
        }

        // Busca el producto por su ID
        $producto = Producto::find($id);

        // Comprueba que la cantidad solicitada no supere el stock disponible
        if ($cantidad > $producto->stock) {
            return redirect()->route('carrito')->with('error', 'No hay suficiente stock disponible.');
        }

        // Obtiene el carrito actual desde la sesión
        $carrito = session()->get('carrito', []);

        // Si la cantidad es 0, elimina el producto del carrito
        if ($cantidad == 0) {
            unset($carrito[$id]);
        } else {
            // Actualiza la cantidad del producto en el carrito
            $carrito[$id] = $cantidad;
        }

        // Guarda el carrito actualizado en la sesión
        session()->put('carrito', $carrito);

        // Redirige al carrito con un mensaje de éxito
        return redirect()->route('carrito')->with('success', 'Carrito actualizado correctamente.');
    }

}
