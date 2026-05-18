<?php

// Namespace del controlador dentro de la aplicación
namespace App\Http\Controllers\Auth;

// Importa la fachada Auth para la autenticación de usuarios
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

// Importa la clase Request para manejar peticiones HTTP
use Illuminate\Http\Request;

// Importa la fachada Hash para encriptar y comprobar contraseñas
use Illuminate\Support\Facades\Hash;

// Importa el modelo Usuario para interactuar con la tabla de usuarios
use App\Models\Usuario;

// Controlador encargado del login, registro y logout de usuarios
class LoginController extends Controller
{
    // Muestra la vista del formulario de login
    public function verLogin(){

        return view('auth.login');

    }

    // Muestra la vista del formulario de registro
    public function verRegistro(){

        return view('auth.registro');

    }

    // Procesa el formulario de registro de un nuevo usuario
    public function procesarregistro(Request $request){


        // Valida los datos enviados desde el formulario de registro
        $datosValidados = $request->validate(
            [
                'nombre' => 'required|string',
                'correo' => 'required|email|unique:usuarios,correo',
                'contrasena' => 'required|min:6',
            ],
            [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.string' => 'El nombre no tiene formato valido',

                'correo.required' => 'El correo es obligatorio',
                'correo.email' => 'El correo no tiene un formato valido',
                'correo.unique' => 'Este correo ya está registrado',

                'contrasena.required' => 'La contraseña es obligatoria',
                'contrasena.min' => 'Longitud mínima de la contraseña es de 6 carácteres',
            ]
        );

        // Asigna por defecto el rol de usuario normal
        $datosValidados['id_rol'] = 1;

        // Encripta la contraseña antes de guardarla en la base de datos
        $datosValidados['contrasena'] = Hash::make($datosValidados['contrasena']);

        // Crea el usuario en la base de datos
        Usuario::create($datosValidados);

        // Redirige al login con un mensaje de éxito
        return redirect()->route('login')->with('success', '¡Cuenta creada con exito!');

    }



    // Procesa el formulario de login
    public function procesarlogin(Request $request){
    
        // Valida los datos enviados desde el formulario de login
        $request->validate([
                'correo' => 'required|email',
                'contrasena' => 'required',
            ],
            [
                'correo.required' => 'El correo es obligatorio',
                'correo.email' => 'El correo no tiene un formato valido',

                'contrasena.required' => 'La contraseña es obligatoria',
            ]
        );

        // Busca el usuario por su correo electrónico
        $usuario = usuario::where('correo', $request->correo)->first();

        // Comprueba si el usuario existe y si la contraseña es correcta
        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena)) {

            // Inicia sesión del usuario
            Auth::login($usuario);

            // Si el usuario es administrador, redirige al panel de admin
            if($usuario->id_rol == 2){
                return redirect()->intended('admin');
            }

            // Si es usuario normal, redirige al home
            return redirect()->intended('home');
        }

        // Si las credenciales no son correctas, vuelve atrás con error
        return back()->withErrors([
            'correo' => 'Correo o contraseña incorrectos',
        ]);

    }

    // Cierra la sesión del usuario
    public function logout(Request $request){
        
        // Cierra la sesión de autenticación
        Auth::logout(); 

        // Invalida la sesión actual
        $request->session()->invalidate();

        // Regenera el token CSRF por seguridad
        $request->session()->regenerateToken();

        // Redirige al login con un mensaje de confirmación
        return redirect()->route('login')->with('success', 'Has cerrado sesión');
    }
 
}
