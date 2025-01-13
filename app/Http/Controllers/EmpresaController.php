<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class EmpresaController extends Controller
{
    // Mostrar lista de empresas
    public function empresas()
    {   
        if (Auth::user()->rol->permisos->contains('accion', 'ver_lista_empresas')) {
            $empresas = Empresa::paginate(5); 
            BitacoraController::store("vio la lista de empresas", 'empresas', 'vio la lista de empresas');
            return view("empresa.index", compact('empresas'));
        }
        return back()->with("error", "No tiene permisos para esta opción");
    }

    // Crear una empresa
    public function store(Request $request)
    {            
        if (Auth::user()->rol->permisos->contains('accion', 'crear_empresa')) {
            try {
                DB::beginTransaction();

                // Crear el nuevo registro de empresa
                $empresa = new Empresa();
                $empresa->nombre = $request->nombre;
                $empresa->direccion = $request->direccion;
                $empresa->telefono = $request->telefono;
                $empresa->ganaderia = $request->ganaderia ? 1 : 0;
                $empresa->agricultura = $request->agricultura ? 1 : 0;
                $empresa->save();

                // Crear el nuevo usuario
                $user = new User();
                $user->name = $request->nombre;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->rol_id = 2;
                $user->empresa_id = $empresa->id;
                $user->save();

                if ($request->hasFile('foto')) {
                    $foto = $request->file('foto');
                    $nombreUsuario = Str::slug($user->name, '_');
                    $fotoPath = $foto->storeAs(
                        "fotos/clientes/{$nombreUsuario}/{$user->id}",
                        "perfil_{$user->id}." . $foto->getClientOriginalExtension(),
                        'public'
                    );
                    $user->foto_path = $fotoPath;
                    $user->save();
                }

                DB::commit();
                BitacoraController::store("creo una empresa", 'empresas', 'usuario creo una empresa');
                return back()->with('success', 'La empresa se ha creado exitosamente.');
            } catch (\Throwable $th) {
                DB::rollBack();
                BitacoraController::store("Error al crear una empresa", 'empresas', 'usuario fallo al intentar crear una empresa');
                return back()->with('error', 'Hubo un error al guardar los datos: ' . $th);
            }
        }
        return back()->with("error", "No tiene permisos para crear una empresa");
    }

    // Actualizar una empresa
    public function update(Request $request)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'editar_empresa')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para actualizar la empresa");
        }
            $request->validate([
                'nombre' => 'required|string|max:255',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'ganaderia' => 'nullable|boolean',
                'agricultura' => 'nullable|boolean',
            ]);

            try {
                // Buscar la empresa
                $empresa = Empresa::findOrFail($request->id);

                // Actualizar los datos
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

                $empresa->save();
                BitacoraController::store("actualizo una empresa", 'empresas', 'usuario actualizo una empresa');
                return redirect()->route('admin-empresas')->with('success', 'La empresa fue actualizada correctamente.');
            } catch (\Throwable $th) {
                BitacoraController::store("fallo al actualizar una empresa", 'empresas', 'usuario fallo al actualizar una empresa');
                return redirect()->back()->with('error', 'Ocurrió un error al intentar actualizar la empresa.');
            }
       
    }

    // Eliminar una empresa
    public function delete($id)
    {
        if (Auth::user()->rol->permisos->contains('accion', 'eliminar_empresa')) {
            return back()->with("error", "No tiene permisos para eliminar la empresa");
        }
            try {
                // Buscar la empresa
                $empresa = Empresa::findOrFail($id);
    
                // Eliminar la empresa
                $empresa->delete();
    
                BitacoraController::store("elimino una empresa", 'empresas', 'usuario elimino una empresa');
                return back()->with('success', 'La empresa fue eliminada correctamente.');
            } catch (\Throwable $th) {
                BitacoraController::store("error al intentar eliminar una empresa", 'empresas', 'usuario fallo al intentar eliminar una empresa');
                return back()->with('error', 'Ocurrió un error al intentar eliminar la empresa: ' . $th);
            }
        }
      
}
