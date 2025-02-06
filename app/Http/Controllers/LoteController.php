<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\DB;

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
    public function index($empresaId, $predioId)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_lotes')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para ver listado de lotes");
        }

        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $tipomapas = TipoMapa::all();
        $lotes = Lote::where('predio_id', '=', $predio->id)->paginate(7);
        BitacoraController::store("vio la lista de lotes la empresa" . $empresa->nombre, 'lotes', 'vio la lista de lotes');
        return view('empresa.lotes', compact('lotes', 'empresa', 'predio', 'tipomapas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'crear_lote')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para crear lote");
        }

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
            BitacoraController::store("creo un lote", 'lotes', 'creo un lote llamado' . $request->nombre);
            // Retornar éxito
            return back()->with('success', 'Lote creado correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            BitacoraController::store("error al crear un lote", 'lotes', 'error al crear lotes' . $request->nombre);
            return back()->with('error', 'Error al crear el lote: ' . $e->getMessage());
        }
    }

    public function cargaMasiva(Request $request)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'crear_lote')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para crear lote");
        }
        // Validar que se ha enviado un archivo Excel
        $request->validate([
            'archivoExcel' => 'required|mimes:xlsx,xls',
        ]);
        $predioId = $request->predio_id;
        try {
            DB::beginTransaction();

            // Cargar el archivo Excel y procesarlo
            Excel::import(new LotesImport($predioId), $request->file('archivoExcel'));
            // Retornar éxito
            BitacoraController::store("Exito al cargar lotes", 'lotes', 'Exito al cargar lotes masivamente');
            DB::commit();
            return back()->with('success', 'Lotes cargados correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            BitacoraController::store("error al cargar lotes", 'lotes', 'error al cargar lotes masivamente');
            DB::rollBack();
            return back()->with('error', 'Error al cargar el archivo: ' . $e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'editar_lote')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para editar lote");
        }

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
            BitacoraController::store("Exito al cargar lotes", 'lotes', 'Exito al cargar lotes masivamente');
            // Retornar éxito
            return back()->with('success', 'Lote actualizado correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            BitacoraController::store("error al actualizar lote", 'lotes', 'error al actualizar lotes  ');
            return back()->with('error', 'Error al actualizar el lote: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {    if (!Auth::user()->rol->permisos->contains('accion', 'eliminar_lote')) {
        // Validación de los datos
        return back()->with("error", "No tiene permisos para eliminar lote");
    }
    
        try {
            // Buscar el lote por el ID
            $lote = Lote::findOrFail($id);

            // Eliminar el lote
            $lote->delete();
            BitacoraController::store("error al cargar lotes", 'lotes', 'error al cargar lotes masivamente');
            // Retornar éxito
            return back()->with('success', 'Lote eliminado correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            BitacoraController::store("error al eliminar lote", 'lotes', 'error al eliminar lote  ');
            return back()->with('error', 'Error al eliminar el lote: ' . $e->getMessage());
        }
    }
}
