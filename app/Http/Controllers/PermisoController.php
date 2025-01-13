<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_permisos')) {
            return back()->with("error", "No tiene permisos para ver la lista de permisos");
        }

        $permisos = Permiso::paginate(10);
        return view('empresa.permisos', compact('permisos'));
    }

    public function asignacion(Request $request, $rolId)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'asginar_permisos')) {
            return back()->with("error", "No tiene permisos para asignar ");
        }

        $rol = Rol::findOrFail($rolId); // Busca el rol por ID

        DB::beginTransaction(); // Inicia la transacción

        try {
            // Asignar permisos nuevos
            if ($request->has('permisos_no_asignado')) {
                $rol->permisos()->attach($request->permisos_no_asignado);
            }

            DB::commit(); // Confirma la transacción
            BitacoraController::store("Permisos asignados", 'roles', "Se asignaron permisos al rol ID: $rolId");
            return back()->with(['success' => 'Permisos asignados correctamente']);
        } catch (\Throwable $th) {
            DB::rollBack(); // Revierte la transacción en caso de error
            BitacoraController::store("Error al asignar permisos", 'roles', "Error: {$th->getMessage()} en el rol ID: $rolId");
            return back()->with(['error' => 'Error al asignar permisos: ' . $th->getMessage()]);
        }
    }

    public function desAsignacion(Request $request, $rolId, $permisoId)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'desasginar_permisos')) {
            return back()->with("error", "No tiene permisos para asignar ");
        }
        $rol = Rol::findOrFail($rolId); // Busca el rol por ID

        DB::beginTransaction(); // Inicia la transacción

        try {
            $rol->permisos()->detach($permisoId);

            DB::commit(); // Confirma la transacción
            BitacoraController::store("Permiso desasignado", 'roles', "Se desasignó el permiso ID: $permisoId del rol ID: $rolId");
            return back()->with(['success' => 'Permisos desasignados correctamente']);
        } catch (\Throwable $th) {
            DB::rollBack(); // Revierte la transacción en caso de error
            BitacoraController::store("Error al desasignar permisos", 'roles', "Error: {$th->getMessage()} al desasignar el permiso ID: $permisoId del rol ID: $rolId");
            return back()->with(['error' => 'Error al desasignar permisos: ' . $th->getMessage()]);
        }
    }
}
