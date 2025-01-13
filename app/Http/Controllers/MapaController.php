<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Mapa;
use App\Models\Predio;
use App\Models\TipoMapa;
use Auth;
use Illuminate\Http\Request;
use Storage;
use Str;

class MapaController extends Controller
{
    public function index($tipoMapa, $empresaId, $predioId)
    { 
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_mapas')) {
        return back()->with("error", "No tiene permisos para ver listado de mapas ");
    }

        try {
            $empresa = Empresa::findOrFail($empresaId); 
            $predio = Predio::where('empresa_id', $empresaId)->where('id', $predioId)->firstOrFail(); 
            $tipomapas = TipoMapa::all(); 
            $mapas = Mapa::where('tipomapa_id', $tipoMapa)->where('predio_id', $predioId)->get(); 
            $tipoMapa = TipoMapa::findOrFail($tipoMapa);

            BitacoraController::store("visualizó mapas", 'mapas', "Se visualizaron los mapas del tipo {$tipoMapa->nombre}");

            return view('empresa.mapas', compact('empresa', 'predio', 'tipomapas', 'mapas', 'tipoMapa'));
        } catch (\Exception $e) {
            BitacoraController::store("error al visualizar mapas", 'mapas', $e->getMessage());
            return back()->with('error', 'Error al cargar los mapas.');
        }
    }

    public function store(Request $request)
    {if (!Auth::user()->rol->permisos->contains('accion', 'crear_mapa')) {
        return back()->with("error", "No tiene permisos para crear mapa ");
    }

        $validated = $request->validate([
            'titulo' => 'string|max:255', 
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240',
            'descripcion' => 'string|max:255'
        ]);

        try {
            $predio = Predio::findOrFail($request->predio_id);
            $empresa = $predio->empresa;
            $tipo = TipoMapa::findOrFail($request->tipomapa_id);

            $mapa = new Mapa();
            $mapa->predio_id = $predio->id;
            $mapa->tipomapa_id = $tipo->id;
            $mapa->fecha = now()->toDateString();
            $mapa->hora = now()->format('H:i:s');
            $mapa->descripcion = $validated['descripcion'];
            $mapa->titulo = $validated['titulo'];

            if ($request->hasFile('archivo_pdf')) {
                $archivo = $request->file('archivo_pdf');
                $nombreEmpresa = Str::slug($empresa->nombre, '_');
                $nombrePredio = Str::slug($predio->nombre, '_');

                $rutaArchivo = $archivo->storeAs(
                    "{$nombreEmpresa}/predios/mapas/{$tipo->nombre}",
                    sprintf('%s_%s_%s.%s', $nombrePredio, now()->toDateString(), now()->format('H-i-s'), $archivo->getClientOriginalExtension()),
                    'public'
                );

                $mapa->path_file = $rutaArchivo;
            }

            $mapa->save();

            BitacoraController::store("mapa agregado", 'mapas', "Mapa {$mapa->titulo} agregado exitosamente");

            return back()->with('success', 'Mapa agregado exitosamente.');
        } catch (\Exception $e) {
            BitacoraController::store("error al agregar mapa", 'mapas', $e->getMessage());
            return back()->with('error', 'Error al guardar el mapa.');
        }
    }

    public function mapas($empresaId, $predioId)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_mapas')) {
            return back()->with("error", "No tiene permisos para ver listado de mapas ");
        }
        try {
            $empresa = Empresa::findOrFail($empresaId);
            $predio = Predio::findOrFail($predioId);
            $tipomapas = TipoMapa::all();

            BitacoraController::store("visualizó predios", 'predios', "Se visualizaron los predios de la empresa {$empresa->nombre}");

            return view('empresa.predio', compact('empresa', 'predio', 'tipomapas'));
        } catch (\Exception $e) {
            BitacoraController::store("error al visualizar predios", 'predios', $e->getMessage());
            return back()->with('error', 'Error al cargar los predios.');
        }
    }

    public function delete($mapaId)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'eliminar_mapa')) {
            return back()->with("error", "No tiene permisos para eliminar mapa ");
        }
        try {
            $mapa = Mapa::findOrFail($mapaId);

            if ($mapa->path_file && Storage::disk('public')->exists($mapa->path_file)) {
                Storage::disk('public')->delete($mapa->path_file);
            }

            $mapa->delete();

            BitacoraController::store("mapa eliminado", 'mapas', "Mapa {$mapa->titulo} eliminado correctamente");

            return back()->with('success', 'Mapa eliminado correctamente.');
        } catch (\Exception $e) {
            BitacoraController::store("error al eliminar mapa", 'mapas', $e->getMessage());
            return back()->with('error', 'Error al eliminar el mapa.');
        }
    }

    public function getMapas($tipoMapa, $empresaId, $predioId)
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_mapas')) {
            return back()->with("error", "No tiene permisos para ver listado de mapas ");
        }

        try {
            $empresa = Empresa::findOrFail($empresaId); 
            $predio = Predio::where('empresa_id', $empresaId)->where('id', $predioId)->firstOrFail(); 
            $tipomapas = TipoMapa::all(); 
            $mapas = Mapa::where('tipomapa_id', $tipoMapa)->where('predio_id', $predioId)->paginate(5); 
            $tipoMapa = TipoMapa::findOrFail($tipoMapa);

            BitacoraController::store("visualizó mapas para cliente", 'mapas', "Se visualizaron los mapas del tipo {$tipoMapa->nombre} para el cliente");

            return view('clients.mapas', compact('empresa', 'predio', 'tipomapas', 'mapas', 'tipoMapa'));
        } catch (\Exception $e) {
            BitacoraController::store("error al visualizar mapas para cliente", 'mapas', $e->getMessage());
            return back()->with('error', 'Error al cargar los mapas para el cliente.');
        }
    }
}
