<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class EmpresaController extends Controller
{




    public function empresas()
    {
        $empresas = Empresa::paginate( 5); // Cambia 10 al número de resultados por página
        return view("empresa.index", compact('empresas'));
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Crear el nuevo usuario
            $user = new User();
            $user->name = $request->nombre;
            $user->email = $request->email;
            $user->password = bcrypt($request->password); // Encriptar la contraseña
            $user->rol_id = 2; // Asignar el rol adecuado
            $user->save();
  
            // Verificar si se ha subido una imagen
                if ($request->hasFile('foto')) {
                    $foto = $request->file('foto');

                    // Normalizar el nombre del usuario para evitar problemas con caracteres
                    $nombreUsuario = Str::slug($user->name, '_');

                    // Generar la ruta de almacenamiento
                    $fotoPath = $foto->storeAs(
                        "fotos/clientes/{$nombreUsuario}/{$user->id}", // Carpeta de destino
                        "perfil_{$user->id}." . $foto->getClientOriginalExtension(), // Nombre del archivo con su extensión original
                        'public' // Disco de almacenamiento
                    );

                    // Guardar la ruta relativa en la base de datos
                    $user->foto_path = $fotoPath;
                    $user->save(); // Guardar el registro con la nueva ruta
                }



            // Crear el nuevo registro de empresa
            $empresa = new Empresa();
            $empresa->nombre = $request->nombre;
            $empresa->direccion = $request->direccion;
            $empresa->telefono = $request->telefono;
            $empresa->user_id = $user->id; // Relacionar el usuario con la empresa
            $empresa->save();

            return redirect()->route('admin.empresas')->with('success', 'La empresa se ha creado exitosamente.');
        } catch (\Throwable $th) {
            // Manejo de excepciones en caso de error
            return response()->json(['error' => 'Hubo un error al guardar los datos'.$th], 500);
        }


        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
