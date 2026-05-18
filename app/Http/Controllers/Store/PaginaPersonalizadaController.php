<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\PaginaPersonalizada;

class PaginaPersonalizadaController extends Controller
{
    public function ver(string $slug)
    {
        $pagina = PaginaPersonalizada::where('slug', $slug)->firstOrFail();

        return view('store.paginas.personalizada', compact('pagina'));
    }
}
