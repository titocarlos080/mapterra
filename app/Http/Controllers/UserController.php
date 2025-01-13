<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Verificar si el usuario tiene permiso para ver la lista de usuarios
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_usuarios')) {
            return back()->with('error', 'No tienes permiso para ver la lista de usuarios.');
        }
        $users = User::paginate(5);
        return view('empresa.usuarios', compact('users'));


    }


    public function store(Request $request)
    {
        // Verificar si el usuario tiene permiso para crear un usuario
        if (!Auth::user()->rol->permisos->contains('accion', 'crear_usuario')) {
            return back()->with('error', 'No tienes permiso para crear un usuario.');
        }
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string',
                'rolId' => 'required|integer|exists:roles,id',
                'empresaId' => 'required|integer|exists:empresas,id',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);



            // Usar una transacción para asegurar la integridad de los datos
            DB::beginTransaction();

            $user = new User();
            $user->name = $request->nombre;
            $user->email = $request->email;
            $user->password = bcrypt($request->password); // Encriptar la contraseña
            $user->rol_id = $request->rolId; // Asignar el rol adecuado
            $user->empresa_id = $request->empresaId; // Asignar la empresa
            $user->save();

            // Verificar si se ha subido una imagen
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');

                // Normalizar el nombre del usuario
                $nombreUsuario = Str::slug($user->name, '_');

                // Generar la ruta de almacenamiento
                $fotoPath = $foto->storeAs(
                    "fotos/clientes/{$nombreUsuario}/{$user->id}", // Carpeta de destino
                    "perfil_{$user->id}." . $foto->getClientOriginalExtension(), // Nombre del archivo
                    'public' // Disco de almacenamiento
                );

                // Guardar la ruta relativa en la base de datos
                $user->foto_path = $fotoPath;
                $user->save();
            }

            DB::commit(); // Confirmar la transacción

            return back()->with('success', 'Usuario agregado correctamente');
        } catch (\Throwable $th) {

            DB::rollBack(); // Revertir la transacción en caso de error
            return back()->with('error', 'El usuario no pudo ser agregado' . $th);
        }
    }


    public function usuariosPorEmpresa()
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_usuarios')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para ver los usuarios");
        }
        $empresa = Auth::user()->empresa; // Obtener la empresa del usuario autenticado

        if ($empresa) {
            // Obtener los usuarios relacionados con la empresa
            $users = $empresa->users()->paginate(5);
        } else {
            $users = collect(); // Retornar una colección vacía si no hay empresa
        }

        return view('clients.users', compact('empresa', 'users'));
    }




}
