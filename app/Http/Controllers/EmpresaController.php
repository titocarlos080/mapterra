<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            DB::beginTransaction();

            // Crear el nuevo registro de empresa
            $empresa = new Empresa();
            $empresa->nombre = $request->nombre;
            $empresa->direccion = $request->direccion;
            $empresa->telefono = $request->telefono;
            $empresa->ganaderia= $request->ganaderia?1:0;
            $empresa->agricultura= $request->agricultura?1:0;
            $empresa->save();

            // Crear el nuevo usuario
            $user = new User();
            $user->name = $request->nombre;
            $user->email = $request->email;
            $user->password = bcrypt($request->password); // Encriptar la contraseña
            $user->rol_id = 2; // Asignar el rol adecuado
            $user->empresa_id = $empresa->id; // Asignar el rol adecuado
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

                DB::commit(); // Confirmar la transacción


            return  back()->with('success', 'La empresa se ha creado exitosamente.');
        } catch (\Throwable $th) {
                     DB::rollBack(); // Revertir la transacción en caso de error

            // Manejo de excepciones en caso de error
             return back()->with('error' , 'Hubo un error al guardar los datos'.$th);
        }


        //
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
{
    // Validación de los datos recibidos
    $request->validate([
        'nombre' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'ganaderia' => 'nullable|boolean',
        'agricultura' => 'nullable|boolean',
    ], [
        'nombre.required' => 'El nombre de la empresa es obligatorio.',
        'telefono.required' => 'El teléfono es obligatorio.',
    ]);

    try {
        // Buscar la empresa por su ID
        $empresa = Empresa::findOrFail($request->id);

        // Actualizar solo los campos que no están vacíos
        if ($request->has('nombre')) {
            $empresa->nombre = $request->nombre;
        }
        if ($request->has('direccion')) {
            $empresa->direccion = $request->direccion;
        }
        if ($request->has('telefono')) {
            $empresa->telefono = $request->telefono;
        }
        if ($request->has('ganaderia')) {
            $empresa->ganaderia = $request->ganaderia ? 1 : 0;
        }
        if ($request->has('agricultura')) {
            $empresa->agricultura = $request->agricultura ? 1 : 0;
        }

        // Guardar los cambios en la base de datos
        $empresa->save();

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('admin-empresas')->with('success', 'La empresa fue actualizada correctamente.');

    } catch (\Throwable $th) {
        // Capturar cualquier error y mostrar mensaje
        return redirect()->back()->with('error', 'Ocurrió un error al intentar actualizar la empresa.');
    }
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {  
        try {
           
            // Buscar la empresa por su ID
            $empresa = Empresa::findOrFail($id);
    
            // Eliminar la empresa
            $empresa->delete();
    
            // Redirigir con mensaje de éxito
            return back()->with('success', 'La empresa fue eliminada correctamente.');
        } catch (\Throwable $th) {
            
            // Capturar cualquier error y mostrar mensaje de error
            return back()->with('error', 'Ocurrió un error al intentar eliminar la empresa:'.$th);
        }
    }
    
}
