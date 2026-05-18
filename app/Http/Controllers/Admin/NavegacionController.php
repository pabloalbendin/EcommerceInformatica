<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AjusteSitio;
use App\Models\Categoria;
use App\Models\EnlaceFooter;
use App\Models\PaginaPersonalizada;
use Illuminate\Http\Request;

class NavegacionController extends Controller
{
    private const MAX_CATEGORIAS_MENU = 7;
    private const MAX_COLUMNAS_FOOTER = 4;

    public function index(Request $request)
    {
        $categorias = Categoria::withCount('productos')
            ->orderByRaw('CASE WHEN mostrar_en_menu = 1 THEN 0 ELSE 1 END')
            ->orderBy('orden_menu')
            ->orderBy('nombre')
            ->get();

        $paginas = PaginaPersonalizada::orderBy('titulo')->get();
        $enlacesFooter = EnlaceFooter::with(['paginaPersonalizada', 'categoria'])
            ->orderBy('columna')
            ->orderBy('orden')
            ->orderBy('titulo')
            ->get();

        $enlaceFooterEditando = null;
        if ($request->filled('editar_enlace')) {
            $enlaceFooterEditando = EnlaceFooter::find($request->integer('editar_enlace'));
        }

        $footerColumnas = (int) AjusteSitio::obtener('footer_columnas', '2');
        $footerDescripcion = AjusteSitio::obtener('footer_descripcion', 'Proyecto academico de ecommerce centrado en productos de informatica.');

        return view('admin.navegacion.index', compact(
            'categorias',
            'paginas',
            'enlacesFooter',
            'enlaceFooterEditando',
            'footerColumnas',
            'footerDescripcion'
        ));
    }

    public function actualizarMenuPrincipal(Request $request)
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

        return redirect()->route('admin.navegacion')->with('success', 'Menu principal actualizado correctamente.');
    }

    public function guardarConfiguracionFooter(Request $request)
    {
        $datos = $request->validate([
            'footer_columnas' => ['required', 'integer', 'min:1', 'max:' . self::MAX_COLUMNAS_FOOTER],
            'footer_descripcion' => ['nullable', 'string', 'max:400'],
        ]);

        AjusteSitio::guardar('footer_columnas', (string) $datos['footer_columnas']);
        AjusteSitio::guardar('footer_descripcion', $datos['footer_descripcion'] ?? null);

        return redirect()->route('admin.navegacion')->with('success', 'Configuracion del footer actualizada correctamente.');
    }

    public function guardarEnlaceFooter(Request $request)
    {
        $datos = $this->validarEnlaceFooter($request);

        EnlaceFooter::create($datos);

        return redirect()->route('admin.navegacion')->with('success', 'Enlace del footer creado correctamente.');
    }

    public function actualizarEnlaceFooter(Request $request, int $id)
    {
        $enlaceFooter = EnlaceFooter::findOrFail($id);
        $datos = $this->validarEnlaceFooter($request);

        $enlaceFooter->update($datos);

        return redirect()->route('admin.navegacion')->with('success', 'Enlace del footer actualizado correctamente.');
    }

    public function eliminarEnlaceFooter(int $id)
    {
        EnlaceFooter::findOrFail($id)->delete();

        return redirect()->route('admin.navegacion')->with('success', 'Enlace del footer eliminado correctamente.');
    }

    private function validarEnlaceFooter(Request $request): array
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:120'],
            'tipo_destino' => ['required', 'in:pagina,categoria,url'],
            'pagina_personalizada_id' => ['nullable', 'integer', 'exists:paginas_personalizadas,id'],
            'categoria_id' => ['nullable', 'integer', 'exists:categorias,id'],
            'url_personalizada' => ['nullable', 'string', 'max:255'],
            'columna' => ['required', 'integer', 'min:1', 'max:' . self::MAX_COLUMNAS_FOOTER],
            'orden' => ['required', 'integer', 'min:1', 'max:999'],
            'nueva_pestana' => ['nullable', 'boolean'],
        ]);

        $datos['nueva_pestana'] = $request->boolean('nueva_pestana');

        if ($datos['tipo_destino'] === 'pagina') {
            $datos['categoria_id'] = null;
            $datos['url_personalizada'] = null;
        }

        if ($datos['tipo_destino'] === 'categoria') {
            $datos['pagina_personalizada_id'] = null;
            $datos['url_personalizada'] = null;
        }

        if ($datos['tipo_destino'] === 'url') {
            $datos['pagina_personalizada_id'] = null;
            $datos['categoria_id'] = null;
        }

        return $datos;
    }
}
