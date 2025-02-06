<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Mapa;
use App\Models\Predio;
use App\Models\SolicitudesDeEstudio;
use App\Models\TipoMapa;
use Auth;
use Illuminate\Http\Request;

class SolicitudesDeEstudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_solicitudes_estudio')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para ver lista solicitudes estudio");
        }
        
        $trabajos = SolicitudesDeEstudio::where('estado_id','=',1)->get();
        return view("empresa.solicitud-estudio",compact('trabajos'));

    }

    

    public function clienteSolicitudEstudio()
    {    if (!Auth::user()->rol->permisos->contains('accion', 'crear_solicitud_estudio')) {
        // Validación de los datos
        return back()->with("error", "No tiene permisos para crear solicitudes estudio");
    }
         
        $empresa = Auth::user()->empresa;
       
        return view('clients.solicitud-estudio',  compact('empresa'));

    }

    public function clienteSolicitudEstudioPredio($tipoMapa, $empresaId, $predioId)
    {    if (!Auth::user()->rol->permisos->contains('accion', 'crear_solicitud_estudio')) {
        // Validación de los datos
        return back()->with("error", "No tiene permisos para crear solicitudes estudio");
    }
         
    try {
        $empresa = Empresa::findOrFail($empresaId); 
        $predio = Predio::where('empresa_id', $empresaId)->where('id', $predioId)->firstOrFail(); 
        $tipomapas = TipoMapa::all(); 
        $mapas = Mapa::where('tipomapa_id', $tipoMapa)->where('predio_id', $predioId)->paginate(5); 
        $tipoMapa = TipoMapa::findOrFail($tipoMapa);
         
        BitacoraController::store("visualizó mapas para cliente", 'mapas', "Se visualizaron los mapas del tipo {$tipoMapa->nombre} para el cliente");

        return view('clients.solicitud-estudio-predio', compact('empresa', 'predio', 'tipomapas', 'mapas', 'tipoMapa'));
    } catch (\Exception $e) {
        BitacoraController::store("error al visualizar mapas para cliente", 'mapas', $e->getMessage());
        return back()->with('error', 'Error al cargar los mapas para el cliente.');
    }

    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        if (!Auth::user()->rol->permisos->contains('accion', 'crear_solicitud_estudio')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para ver crear solicitudes estudio");
        }
        // Verifica los datos recibidos
        $descripcion = $request->input('descripcion');
        $json = $request->input('json');
        $empresaId= $request->input('empresaId');
        $predioId= $request->input('predioId');

        // Valida los datos
        if (!$descripcion || !$json) {
            return back()->with('error' ,'Faltan Datos');

         }

        try {
            //code...
            $solicitud = new SolicitudesDeEstudio();
            $solicitud->descripcion = $descripcion;
            $solicitud->json = $json;
            $solicitud->fecha = now()->toDateString(); // Fecha actual sin hora (YYYY-MM-DD)
            $solicitud->hora = now()->format('H:i:s'); // Hora actual Hms
            $solicitud->estado_id = 1;
            $solicitud->empresa_id=$empresaId;
            $solicitud->predio_id=$predioId;

            $solicitud->save();

           
            return back()->with('success' ,'Datos recibidos correctamente');

        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error' ,'Nose pudo guardar correctamente.!!');
         }



    }

}
