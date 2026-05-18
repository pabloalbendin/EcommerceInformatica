<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AjusteSitio;
use App\Models\PaginaPersonalizada;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaginaPersonalizadaController extends Controller
{
    private array $slugsReservados = [
        'login',
        'registro',
        'home',
        'carrito',
        'contactanos',
        'quienes-somos',
        'politica-de-cookies',
        'preguntas-frecuentes',
        'aviso-legal',
        'politica-de-privacidad',
        'perfil',
        'admin',
    ];

    public function index(Request $request)
    {
        $paginas = PaginaPersonalizada::orderBy('titulo')->get();
        $paginaEditando = null;

        if ($request->filled('editar_pagina')) {
            $paginaEditando = PaginaPersonalizada::find($request->integer('editar_pagina'));
        }

        $cssPersonalizado = AjusteSitio::obtener('css_personalizado', '');

        return view('admin.paginas.index', compact('paginas', 'paginaEditando', 'cssPersonalizado'));
    }

    public function guardar(Request $request)
    {
        $datos = $this->validarPagina($request);

        PaginaPersonalizada::create($datos);

        return redirect()->route('admin.paginas')->with('success', 'Pagina personalizada creada correctamente.');
    }

    public function actualizar(Request $request, int $id)
    {
        $pagina = PaginaPersonalizada::findOrFail($id);
        $datos = $this->validarPagina($request, $pagina->id);

        $pagina->update($datos);

        return redirect()->route('admin.paginas')->with('success', 'Pagina personalizada actualizada correctamente.');
    }

    public function eliminar(int $id)
    {
        PaginaPersonalizada::findOrFail($id)->delete();

        return redirect()->route('admin.paginas')->with('success', 'Pagina personalizada eliminada correctamente.');
    }

    public function guardarCss(Request $request)
    {
        $datos = $request->validate([
            'css_personalizado' => ['nullable', 'string'],
        ]);

        AjusteSitio::guardar('css_personalizado', $datos['css_personalizado'] ?? null);

        return redirect()->route('admin.paginas')->with('success', 'CSS personalizado guardado correctamente.');
    }

    private function validarPagina(Request $request, ?int $paginaId = null): array
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:140'],
            'contenido_html' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($datos['titulo']);

        if ($slug === '') {
            $slug = 'pagina';
        }

        if (in_array($slug, $this->slugsReservados, true)) {
            $slug .= '-custom';
        }

        $contador = 2;
        $slugBase = $slug;

        while (
            PaginaPersonalizada::where('slug', $slug)
                ->when($paginaId, fn ($query) => $query->where('id', '!=', $paginaId))
                ->exists()
        ) {
            $slug = $slugBase . '-' . $contador;
            $contador++;
        }

        $datos['slug'] = $slug;

        return $datos;
    }
}
