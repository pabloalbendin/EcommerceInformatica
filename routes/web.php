<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PerfilController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\DetalleController;
use App\Http\Controllers\Store\CarritoController;
use App\Http\Controllers\Store\ConfiguradorController;
use App\Http\Controllers\Store\InfoController;
use App\Http\Controllers\Store\PaginaPersonalizadaController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\NavegacionController;
use App\Http\Controllers\Admin\PaginaPersonalizadaController as AdminPaginaPersonalizadaController;
use App\Http\Controllers\Admin\UsuarioController;



Route::get('/login', [LoginController::class, 'verLogin'])->name('login');
Route::post('/login', [LoginController::class, 'procesarlogin'])->name('login.process');


Route::get('/registro', [LoginController::class, 'verRegistro'])->name('registro');
Route::post('/registro', [LoginController::class, 'procesarregistro'])->name('registro.process');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);
Route::get('/categoria/{id}', [HomeController::class, 'categoria'])->name('categoria');
Route::get('/producto/{id}', [DetalleController::class, 'index'])->name('detalle');
Route::get('/configurador-pc', [ConfiguradorController::class, 'index'])->name('configurador');
Route::get('/carrito/add/{id}', [CarritoController::class, 'anyadir'])->name('carrito_add');
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito');
Route::get('/carrito/{id}/{cantidad}', [CarritoController::class, 'cantidad'])->name('cambiarCantidad');
Route::get('/quienes-somos', [InfoController::class, 'quienesSomos'])->name('quienes-somos');
Route::match(['get', 'post'], '/contactanos', [InfoController::class, 'contacto'])->name('contacto');
Route::get('/politica-de-cookies', [InfoController::class, 'cookies'])->name('cookies');
Route::get('/preguntas-frecuentes', [InfoController::class, 'faq'])->name('faq');
Route::get('/aviso-legal', [InfoController::class, 'avisoLegal'])->name('aviso-legal');
Route::get('/politica-de-privacidad', [InfoController::class, 'privacidad'])->name('privacidad');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::post('/perfil', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');
    Route::get('/pago', [PedidosController::class, 'checkout'])->name('pago.form');
    Route::get('/pago/configuracion', [PedidosController::class, 'checkoutConfiguracion'])->name('pago.configuracion');
    Route::post('/pagar', [PedidosController::class, 'pago'])->name('pagar');
    Route::post('/configurador-pc/comprar', [ConfiguradorController::class, 'comprar'])->name('configurador.comprar');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/productos', [AdminController::class, 'productos'])->name('admin.productos');
    Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('admin.categorias');
    Route::get('/admin/categorias/crear', [CategoriaController::class, 'crearIndex'])->name('admin.categorias.crear');
    Route::post('/admin/categorias/crear', [CategoriaController::class, 'crear'])->name('admin.categorias.guardar');
    Route::get('/admin/categorias/editar/{id}', [CategoriaController::class, 'editarIndex'])->name('admin.categorias.editar');
    Route::post('/admin/categorias/editar/{id}', [CategoriaController::class, 'editar'])->name('admin.categorias.actualizar');
    Route::post('/admin/categorias/delete', [CategoriaController::class, 'delete'])->name('admin.categorias.delete');
    Route::get('/admin/navegacion', [NavegacionController::class, 'index'])->name('admin.navegacion');
    Route::post('/admin/navegacion/menu-principal', [NavegacionController::class, 'actualizarMenuPrincipal'])->name('admin.navegacion.menu-principal');
    Route::post('/admin/navegacion/footer-config', [NavegacionController::class, 'guardarConfiguracionFooter'])->name('admin.navegacion.footer-config');
    Route::post('/admin/navegacion/footer-enlaces', [NavegacionController::class, 'guardarEnlaceFooter'])->name('admin.navegacion.footer-enlaces.guardar');
    Route::post('/admin/navegacion/footer-enlaces/{id}', [NavegacionController::class, 'actualizarEnlaceFooter'])->name('admin.navegacion.footer-enlaces.actualizar');
    Route::post('/admin/navegacion/footer-enlaces/{id}/delete', [NavegacionController::class, 'eliminarEnlaceFooter'])->name('admin.navegacion.footer-enlaces.eliminar');
    Route::get('/admin/paginas', [AdminPaginaPersonalizadaController::class, 'index'])->name('admin.paginas');
    Route::post('/admin/paginas', [AdminPaginaPersonalizadaController::class, 'guardar'])->name('admin.paginas.guardar');
    Route::post('/admin/paginas/{id}', [AdminPaginaPersonalizadaController::class, 'actualizar'])->name('admin.paginas.actualizar');
    Route::post('/admin/paginas/{id}/delete', [AdminPaginaPersonalizadaController::class, 'eliminar'])->name('admin.paginas.eliminar');
    Route::post('/admin/paginas/css', [AdminPaginaPersonalizadaController::class, 'guardarCss'])->name('admin.paginas.css');
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios');
    Route::get('/admin/usuarios/crear', [UsuarioController::class, 'crearIndex'])->name('admin.usuarios.crear');
    Route::post('/admin/usuarios/crear', [UsuarioController::class, 'crear'])->name('admin.usuarios.guardar');
    Route::post('/admin/usuarios/delete', [UsuarioController::class, 'delete'])->name('admin.usuarios.delete');

    Route::post('/admin/delete', [ProductoController::class, 'delete'])->name('delete');

    Route::get('/admin/crear', [ProductoController::class, 'crearIndex'])->name('creacion');
    Route::get('/admin/editar/{id}', [ProductoController::class, 'editarIndex'])->name('edicion');

    Route::post('/admin/crear', [ProductoController::class, 'crear'])->name('crear');
    Route::post('/admin/editar/{id}', [ProductoController::class, 'editar'])->name('editar');

    Route::get('/admin/pedidos', [PedidosController::class, 'verAdmin'])->name('verAdmin');
    Route::post('/admin/pedidos/editar/{id}', [PedidosController::class, 'editarEstado'])->name('editarEstado');


});

Route::get('/{slug}', [PaginaPersonalizadaController::class, 'ver'])->name('paginas.personalizadas.ver');
