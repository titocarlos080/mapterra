<?php

namespace App\Http\Controllers;
use App\Models\Mapa;
use App\Models\SolicitudesDeEstudio;
use App\Models\TipoMapa;
use App\Models\Empresa;
use App\Models\Predio;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PredioController extends Controller
{
     
    public function index($empresaId)
    {
        // Verificar si el usuario tiene permiso para ver los predios
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_predios')) {
            return back()->with('error', 'No tienes permiso para ver los predios.');
        }

        try {
            $empresa = Empresa::findOrFail($empresaId);
            $predios = Predio::where('empresa_id', $empresaId)->paginate(5);
            return view('empresa.predios', compact('empresa', 'predios'));
        } catch (\Throwable $th) {
            BitacoraController::store('Error Consulta de Predios', 'predios', "Error al listar predios: {$th->getMessage()}.");
            return back()->with('error', 'Error al listar predios: ' . $th->getMessage());
        }
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        // Verificar si el usuario tiene permiso para crear un predio
        if (!Auth::user()->rol->permisos->contains('accion', 'crear_predio')) {
            return back()->with('error', 'No tienes permiso para crear un predio.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'archivo_kmz' => 'nullable|file|max:10240',
        ]);

        try {
            $empresa = Empresa::findOrFail($request->empresaId);
            $predio = new Predio();
            $predio->nombre = $validated['nombre'];
            $predio->municipio = $validated['municipio'];
            $predio->provincia = $validated['provincia'];
            $predio->departamento = $validated['departamento'];
            $predio->empresa_id = $empresa->id;

            if ($request->hasFile('archivo_kmz')) {
                $archivo = $request->file('archivo_kmz');
                $nombreEmpresa = Str::slug($empresa->nombre, '_');
                $nombrePredio = Str::slug($predio->nombre, '_');
                $rutaArchivo = $archivo->storeAs(
                    "{$nombreEmpresa}/predios",
                    "{$nombrePredio}." . $archivo->getClientOriginalExtension(),
                    'public'
                );
                $predio->path_kmz = $rutaArchivo;
            }

            $predio->save();

            BitacoraController::store('Creación de predio', 'predios', "Se creó el predio {$predio->nombre} para la empresa {$empresa->nombre}.");

            return back()->with('success', 'Predio creado exitosamente');
        } catch (\Throwable $th) {
            BitacoraController::store('Error en creación de predio', 'predios', $th->getMessage());
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request)
    {
        // Verificar si el usuario tiene permiso para actualizar un predio
        if (!Auth::user()->rol->permisos->contains('accion', 'editar_predio')) {
            return back()->with('error', 'No tienes permiso para actualizar el predio.');
        }

        $request->validate([
            'empresa_id' => 'required|integer|exists:empresas,id',
            'predio_id' => 'required|integer|exists:predios,id',
            'nombre' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
        ]);

        try {
            $predio = Predio::findOrFail($request->predio_id);
            $predio->nombre = $request->nombre;
            $predio->municipio = $request->municipio;
            $predio->provincia = $request->provincia;
            $predio->departamento = $request->departamento;
            $predio->empresa_id = $request->empresa_id;
            $predio->save();

            BitacoraController::store('Actualización de predio', 'predios', "Se actualizó el predio {$predio->nombre}.");

            return back()->with('success', 'Predio actualizado correctamente.');
        } catch (\Exception $e) {
            BitacoraController::store('Error en actualización de predio', 'predios', $e->getMessage());
            return back()->with('error', 'Ocurrió un error al actualizar el predio: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource.
     */
    public function delete($predioId)
    {
        // Verificar si el usuario tiene permiso para eliminar un predio
        if (!Auth::user()->rol->permisos->contains('accion', 'eliminar_predio')) {
            return back()->with('error', 'No tienes permiso para eliminar el predio.');
        }

        try {
            $predio = Predio::findOrFail($predioId);
            $predio->delete();

            BitacoraController::store('Eliminación de predio', 'predios', "Se eliminó el predio {$predio->nombre}.");

            return back()->with('success', 'Predio eliminado correctamente.');
        } catch (\Throwable $th) {
            BitacoraController::store('Error en eliminación de predio', 'predios', $th->getMessage());
            return back()->with('error', 'Error al intentar eliminar: ' . $th->getMessage());
        }
    }



    public function getPredio($empresaId, $predioId)
    {  
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_predios')) {
            return back()->with('error', 'No tienes permiso para ver los predios.');
        }

        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $mapas = Mapa::where('predio_id', $predio->id)
            ->where('tipomapa_id', '1')
            ->paginate(5);
        $tipomapas = TipoMapa::all();

        return view('clients.predios', compact('empresa', 'predio', 'mapas', 'tipomapas'));
    }

}
