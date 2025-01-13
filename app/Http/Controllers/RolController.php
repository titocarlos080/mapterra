<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Rol;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\BitacoraController; // Importamos el controlador de Bitácora

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar si el usuario tiene permiso para ver los roles
           if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_roles')) {
              return back()->with('error', 'No tienes permiso para ver los roles.');
          }

        try {
            $roles = Rol::all();
            $permisos = Permiso::all();
            BitacoraController::store('Consulta de Roles', 'roles', 'Se listaron todos los roles.');
            return view('empresa.roles', compact('roles', 'permisos'));
        } catch (\Throwable $th) {
            BitacoraController::store('Error Consulta de Roles', 'roles', "Error al listar roles: {$th->getMessage()}.");
            return back()->with('error', 'Error al listar roles: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Verificar si el usuario tiene permiso para crear un rol
        if (!Auth::user()->rol->permisos->contains('accion', 'crear_rol')) {
            return back()->with('error', 'No tienes permiso para crear un rol.');
        }

        try {
            $request->validate(['nombre' => 'required|string|max:255']);
            $rol = Rol::create($request->all());
            BitacoraController::store('Crear Rol', 'roles', "Se creó un nuevo rol: {$rol->nombre}.");
            return back()->with('success', 'Rol creado con éxito.');
        } catch (\Throwable $th) {
            BitacoraController::store('Error Crear Rol', 'roles', "Error al crear rol: {$th->getMessage()}.");
            return back()->with('error', 'Error al crear rol: ' . $th->getMessage());
        }
    }

    public function update(Request $request)
    {
        // Verificar si el usuario tiene permiso para actualizar un rol
        if (!Auth::user()->rol->permisos->contains('accion', 'actualizar_rol')) {
            return back()->with('error', 'No tienes permiso para actualizar el rol.');
        }

        try {
            $rol = Rol::findOrFail($request->rol_id);
            $rol->nombre = $request->nombre;
            $rol->save();
            BitacoraController::store('Actualizar Rol', 'roles', "Se actualizó el rol: {$rol->nombre}.");
            return back()->with('success', 'Rol actualizado correctamente.');
        } catch (\Throwable $th) {
            BitacoraController::store('Error Actualizar Rol', 'roles', "Error al actualizar rol: {$th->getMessage()}.");
            return back()->with('error', 'Error al actualizar rol: ' . $th->getMessage());
        }
    }

    public function delete($id)
    {
        // Verificar si el usuario tiene permiso para eliminar un rol
        if (!Auth::user()->rol->permisos->contains('accion', 'eliminar_rol')) {
            return back()->with('error', 'No tienes permiso para eliminar el rol.');
        }

        try {
            $rol = Rol::findOrFail($id);
            $rol->delete();
            BitacoraController::store('Eliminar Rol', 'roles', "Se eliminó el rol: {$rol->nombre}.");
            return back()->with('success', 'Rol eliminado correctamente.');
        } catch (\Throwable $th) {
            BitacoraController::store('Error Eliminar Rol', 'roles', "Error al eliminar rol: {$th->getMessage()}.");
            return back()->with('error', 'Error al eliminar rol: ' . $th->getMessage());
        }
    }
}
