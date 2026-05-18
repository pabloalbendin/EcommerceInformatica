<?php

// Namespace del controlador dentro de la aplicación
namespace App\Http\Controllers\Store;

// Importa la clase Request para manejar peticiones HTTP
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Importa el modelo Producto para acceder a los datos de los productos
use App\Models\Producto;

// Controlador encargado de mostrar el detalle de un producto
class DetalleController extends Controller
{
    // Muestra la vista de detalle de un producto concreto
    public function index($id){
        
        // Busca el producto por su ID e incluye su categoría relacionada
        $producto = Producto::visibleEnTienda()
            ->with('categoria')
            ->findOrFail($id);

        // Devuelve la vista 'detalle' pasando el producto encontrado
        return view('store.detalle', compact('producto'));
    }
}
