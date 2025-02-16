<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Mail\PasswordResetMail;
class AuthController extends Controller
{
    public function showLoginForm()
    {

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);
         // Intentar autenticar al usuario
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            BitacoraController::store("inicio session",'users','ha inciado session');
            // Si el login es exitoso, redirige al usuario a la página deseada
            $user = Auth::user();
            
            $rol = $user->rol_id;
            if ( $user->empresa->nombre === "MapTerra") {

                return redirect()->intended('/home');
            }
            if (($rol == 2) || ($user->empresa->nombre != "MapTerra")) {
                return redirect()->intended('/cliente');
            }
        }

        // Si las credenciales son incorrectas, redirige con un mensaje de error
        return back()->with('error','Registros incorrectos.');
    }


    public function olvide_password()
    {
        return view("auth.olvide_password");
        // Enviar el correo con el enlace de recuperación


        /*
       // Validar el correo electrónico ingresado
       $request->validate([
           'email' => 'required|email|exists:users,email', // Asegúrate de que el email existe en la tabla `users`
       ]);

       try {
           // Generar un token único
           $token = Str::random(60);

           // Guardar el token en la base de datos para uso futuro (tabla `password_resets`)
           DB::table('password_resets')->updateOrInsert(
               ['email' => $request->email],
               [
                   'email' => $request->email,
                   'token' => $token,
                   'created_at' => now()
               ]
           );

           // Generar el enlace de recuperación
           $resetLink = route('password.reset', ['token' => $token]);

           // Enviar el correo con el enlace de recuperación
           Mail::to($request->email)->send(new PasswordResetMail($resetLink));

           // Retornar con un mensaje de éxito
           return redirect()->back()->with('success', 'Solicitud enviada con éxito. Por favor, revisa tu correo electrónico para encontrar el enlace que te permitirá restablecer tu contraseña.');
       } catch (\Exception $e) {
           // Manejar errores en caso de que algo salga mal
           return redirect()->back()->withErrors([
               'message' => 'Ocurrió un error al intentar enviar el correo. Por favor, inténtalo nuevamente más tarde.'
           ]);
       }
*/
    }

    public function password_email(Request $request)
    {
        try {
            $resetLink = "https://mapterrabo/appweb";
            // Tu lógica para enviar el correo
            Mail::to($request->email)->send(new PasswordResetMail($resetLink));

            // Mensaje de éxito sin usar session
            return redirect()->back()->with('success', 'Solicitud enviada con éxito. Por favor, revisa tu correo electrónico.');
        } catch (\Throwable $th) {
            // Error al enviar el correo

            return redirect()->back()->withErrors([
                'error' => 'Ocurrió un error al intentar enviar el correo. Por favor, inténtalo nuevamente más tarde.'
            ]);
        }
    }




    public function logout()
    {
        BitacoraController::store("cerro session",'users','ha cerra session');

        Auth::logout();

        return redirect()->route('index');
    }
}