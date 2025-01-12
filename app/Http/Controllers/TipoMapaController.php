<?php

namespace App\Http\Controllers;

use App\Models\TipoMapa;
use Illuminate\Http\Request;

class TipoMapaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tipoMapas = TipoMapa::all();
        return view('empresa.tipomapas',compact('tipoMapas'));
    }

     
    public function store(Request $request)
    {
       
    
        try {
             // Validar los datos recibidos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'icon' => 'required|string|max:255',
        ]);
            // Crear un nuevo registro en la base de datos
            TipoMapa::create([
                'nombre' => $validatedData['nombre'],
                'descripcion' => $validatedData['descripcion'],
                'icon' => $validatedData['icon'],
            ]);
    
            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Tipo de mapa creado correctamente.');
        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()->back()->with('error', 'Ocurrió un error al crear el tipo de mapa: ' . $e->getMessage());
        }
    }
    

    
    public function update(Request $request)
    {
       
        try {
             // Validar los datos recibidos
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:tipo_mapas,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'icon' => 'required|string|max:255',
        ]);
    
            // Buscar el registro correspondiente
            $tipoMapa = TipoMapa::findOrFail($validatedData['id']);
    
            // Actualizar los datos
            $tipoMapa->update([
                'nombre' => $validatedData['nombre'],
                'descripcion' => $validatedData['descripcion'],
                'icon' => $validatedData['icon'],
            ]);
    
            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Tipo de mapa actualizado correctamente.');
        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar el tipo de mapa: ' . $e->getMessage());
        }
    }
    
    public function delete($id)
    {
        try {
            // Buscar el registro correspondiente
            $tipoMapa = TipoMapa::findOrFail($id);
    
            // Eliminar el registro
            $tipoMapa->delete();
    
            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Tipo de mapa eliminado correctamente.');
        } catch (\Exception $e) {
            // Manejo de errores
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el tipo de mapa: ' . $e->getMessage());
        }
    }
    
}
