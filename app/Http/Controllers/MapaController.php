<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Mapa;
use App\Models\Predio;
use App\Models\TipoMapa;
use Illuminate\Http\Request;
use Storage;
use Str;

class MapaController extends Controller
{
    
    public function index($tipoMapa, $empresaId, $predioId)
    {
        $empresa = Empresa::findOrFail($empresaId); 
        $predio = Predio::where('empresa_id', '=', $empresaId)->where('id', '=', $predioId)->firstOrFail(); 
        
        $tipomapas = TipoMapa::all(); 
        $mapas = Mapa::where('tipomapa_id', '=', $tipoMapa)
                     ->where('predio_id', '=', $predioId)
                     ->get(); 
        
        // Obtener el nombre del tipo de mapa
        $tipoMapa  = TipoMapa::findOrFail($tipoMapa);
    
        return view('empresa.mapas', compact('empresa', 'predio', 'tipomapas', 'mapas', 'tipoMapa'));
    }
    
    
    public function store(Request $request)
    {
         // Validar los campos
        $validated = $request->validate([
            'titulo' => 'string|max:255', 
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240', // Archivo PDF máximo 10 MB
            'descripcion' => 'string|max:255'
         
             
        ]);
 
        // Buscar el predio y validar existencia
        $predio = Predio::find($request->predio_id);
        if (!$predio) {
            return back()->with('error','El predio no existe.');
        }
    
        $empresa = $predio->empresa;
        if (!$empresa) {
            return back()->with('error','El predio no tiene una empresa asociada.');
        }
        $tipo = TipoMapa::find($request->tipomapa_id);
        if (!$tipo) {
            return back()->with('error','El mapa no tiene asociado a ningun tipo');
        }
        
        try {
            // Crear el modelo del mapa
            $mapa = new Mapa();
            $mapa->predio_id = $predio->id;
            $mapa->tipomapa_id = $tipo->id;
            $mapa->fecha = now()->toDateString(); // Fecha actual sin hora (YYYY-MM-DD)
            $mapa->hora = now()->format('H:i:s'); // Hora actual Hms
            
            $mapa->descripcion = $validated['descripcion'];
            $mapa->titulo = $validated['titulo'];
            
            // obtener el nombre de tipo de mapa para archivar correcatmente
    
            if ($request->hasFile('archivo_pdf')) {
                $archivo = $request->file('archivo_pdf');
    
                // Normalizar nombres para evitar problemas
                $nombreEmpresa = Str::slug($empresa->nombre, '_');
                $nombrePredio = Str::slug($predio->nombre, '_');
    
              // Generar la ruta de almacenamiento
            $rutaArchivo = $archivo->storeAs(
                "{$nombreEmpresa}/predios/mapas/{$tipo->nombre}", // Carpeta de destino
                sprintf('%s_%s_%s.%s', $nombrePredio, now()->toDateString(), now()->format('H-i-s'), $archivo->getClientOriginalExtension()), // Nombre del archivo
                'public' // Disco de almacenamiento
            );

    
                // Asignar la ruta del archivo al modelo
                $mapa->path_file = $rutaArchivo;
            }
    
            // Guardar el mapa
            $mapa->save();
    
            // Paginación de los mapas relacionados
            $mapas = Mapa::where('predio_id', $predio->id)
                ->where('tipomapa_id', 1)
                ->paginate(5);
    
            // Redirigir con un mensaje de éxito
            return back()->with('success', 'Mapa agregado exitosamente.');
        } catch (\Exception $e) {
              return back()->with('error','Hubo un error al guardar el mapa. Intenta nuevamente.'.$e);
        }
    }

   
    public function mapas($empresaId, $predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $tipomapas = TipoMapa::all();
        
    
        return view('empresa.predio', compact('empresa', 'predio', 'tipomapas'));
    }
    


    
    public function delete( $mapaId)
    {
        try {
            // Buscar el mapa por su ID
            $mapa = Mapa::findOrFail($mapaId);
    
            // Eliminar el archivo físico del disco
            if ($mapa->path_file && Storage::disk('public')->exists($mapa->path_file)) {
                Storage::disk('public')->delete($mapa->path_file);
            }
    
            // Eliminar el registro del mapa en la base de datos
            $mapa->delete();
    
            // Retornar con éxito
            return back()->with('success', 'Mapa eliminado correctamente.');
        } catch (\Exception $e) {
            // Manejar errores y retornar con mensaje
            return back()->with('error', 'Error al eliminar el mapa: ' . $e->getMessage());
        }
    }
    // FUNCIONES PARA CLIENTES
    public function getMapas($tipoMapa, $empresaId, $predioId)  {
        $empresa = Empresa::findOrFail($empresaId); 
        $predio = Predio::where('empresa_id', '=', $empresaId)->where('id', '=', $predioId)->firstOrFail(); 
        
        $tipomapas = TipoMapa::all(); 
        $mapas = Mapa::where('tipomapa_id', '=', $tipoMapa)
                     ->where('predio_id', '=', $predioId)
                     ->paginate(5); 
        
        // Obtener el nombre del tipo de mapa
        $tipoMapa  = TipoMapa::findOrFail($tipoMapa);
    
        return view('clients.mapas', compact('empresa', 'predio', 'tipomapas', 'mapas', 'tipoMapa'));
    }
    
}
