<?php

namespace App\Providers;

use App\Models\AjusteSitio;
use App\Models\Categoria;
use App\Models\EnlaceFooter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('partials.header', function ($view) {
            $categoriasMenu = Categoria::visibleEnTienda()
                ->where('mostrar_en_menu', true)
                ->orderBy('orden_menu')
                ->orderBy('nombre')
                ->take(7)
                ->get();

            if ($categoriasMenu->isEmpty()) {
                $categoriasMenu = Categoria::visibleEnTienda()
                    ->orderBy('nombre')
                    ->take(7)
                    ->get();
            }

            $view->with('categoriasMenu', $categoriasMenu);
        });

        View::composer('partials.footer', function ($view) {
            $view->with([
                'footerColumnas' => (int) AjusteSitio::obtener('footer_columnas', '2'),
                'footerDescripcion' => AjusteSitio::obtener('footer_descripcion', 'Proyecto academico de ecommerce centrado en productos de informatica.'),
                'footerEnlaces' => EnlaceFooter::with(['paginaPersonalizada', 'categoria'])
                    ->where(function ($query) {
                        $query->where('tipo_destino', '!=', 'categoria')
                            ->orWhereHas('categoria', fn ($categoriaQuery) => $categoriaQuery->visibleEnTienda());
                    })
                    ->orderBy('columna')
                    ->orderBy('orden')
                    ->orderBy('titulo')
                    ->get(),
            ]);
        });

        View::composer('layouts.app', function ($view) {
            $view->with('cssPersonalizado', AjusteSitio::obtener('css_personalizado', ''));
        });
    }
}
