<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Rol::all();
        return view('empresa.roles',compact('roles'));
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
        //
        try {
            //code...
            $request->validate(['nombre' => 'required|string|max:255']);
        Rol::create($request->all());
        return back()->with('success', 'Rol creado con éxito.');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', 'Error al crear rol'.$th);

        }
        
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $rol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request )
    {
        //
         try {
            //code...
            $rol = Rol::findOrFail($request->rol_id);
            $rol->nombre = $request->nombre;
            $rol->save();
            return back()->with('success','Rol actualizado correctamente');
        } catch (\Throwable $th) {
            //throw $th;

            return back()->with('error','Error al intentar acutalizar rol'.$th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        //
        try {
            //code...
            $rol = Rol::findOrFail($id);
            $rol->delete();
            return back()->with('success','Rol eliminado correctamente');
        } catch (\Throwable $th) {
            //throw $th;

            return back()->with('error','Error al intentar eliminar rol'.$th);
        }
    }
}
