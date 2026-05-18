<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function quienesSomos()
    {
        return view('store.info.quienes-somos');
    }

    public function contacto(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'nombre' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:160'],
                'asunto' => ['required', 'string', 'max:150'],
                'mensaje' => ['required', 'string', 'max:2000'],
            ]);

            return back()->with('success', 'Formulario recibido en modo demostracion. Por ahora no se envia ningun correo.');
        }

        return view('store.info.contacto');
    }

    public function cookies()
    {
        return view('store.info.cookies');
    }

    public function faq()
    {
        return view('store.info.faq');
    }

    public function avisoLegal()
    {
        return view('store.info.aviso-legal');
    }

    public function privacidad()
    {
        return view('store.info.privacidad');
    }
}
