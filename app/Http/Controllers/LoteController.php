<?php

namespace App\Http\Controllers;

use App\Imports\LotesImport;
use App\Models\Empresa;
use App\Models\Lote;
use App\Models\Predio;
use App\Models\TipoMapa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($empresaId,$predioId)
    { 
         $empresa= Empresa::findOrFail($empresaId);
        $predio= Predio::findOrFail($predioId);
        $tipomapas = TipoMapa::all();
        $lotes = Lote::where('predio_id','=' ,$predio->id)->paginate(7);
        return view('empresa.lotes', compact('lotes','empresa','predio','tipomapas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validación de datos
            $request->validate([
                'codigo' => 'required|string|max:255',
                'nombre' => 'required|string|max:255',
                'hectareas' => 'required|numeric',
                'predio_id' => 'required|exists:predios,id',
            ]);

            // Crear un nuevo lote
            Lote::create($request->all());

            // Retornar éxito
            return back()->with('success', 'Lote creado correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            return back()->with('error', 'Error al crear el lote: ' . $e->getMessage());
        }
    }

    public function cargaMasiva(Request $request)
    {
        // Validar que se ha enviado un archivo Excel
         $request->validate([
            'archivoExcel' => 'required|mimes:xlsx,xls',
        ]);
         $predioId= $request->predio_id;
        try {
 

            // Cargar el archivo Excel y procesarlo
            Excel::import(new LotesImport($predioId), $request->file('archivoExcel'));
            // Retornar éxito
            return back()->with('success', 'Lotes cargados correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            return back()->with('error', 'Error al cargar el archivo: ' . $e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // Validación de datos
            $request->validate([
                'codigo' => 'required|string|max:255',
                'nombre' => 'required|string|max:255',
                'hectareas' => 'required|numeric',
                'predio_id' => 'required|exists:predios,id',
            ], [
                'codigo.required' => 'El código es obligatorio.',
                'codigo.string' => 'El código debe ser un texto.',
                'codigo.max' => 'El código no puede tener más de 255 caracteres.',
                
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser un texto.',
                'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
                
                'hectareas.required' => 'Las hectáreas son obligatorias.',
                'hectareas.numeric' => 'Las hectáreas deben ser un valor numérico.',
                
                'predio_id.required' => 'El predio es obligatorio.',
                'predio_id.exists' => 'El predio seleccionado no es válido.',
            ]);
            

            // Buscar el lote por el ID
            $lote = Lote::findOrFail($request->lote_id);

            // Actualizar los datos del lote
            $lote->update($request->all());

            // Retornar éxito
            return back()->with('success', 'Lote actualizado correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            return back()->with('error', 'Error al actualizar el lote: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try {
            // Buscar el lote por el ID
            $lote = Lote::findOrFail($id);

            // Eliminar el lote
            $lote->delete();

            // Retornar éxito
            return back()->with('success', 'Lote eliminado correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            return back()->with('error', 'Error al eliminar el lote: ' . $e->getMessage());
        }
    }
}
