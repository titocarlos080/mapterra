<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Predio;
use App\Models\SolicitudesDeEstudio;
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

        // Valida los datos
        if (!$descripcion || !$json) {
            return response()->json(['message' => 'Faltan datos'], 400);
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

            $solicitud->save();

           
            return response()->json(['message' => 'Datos recibidos correctamente'], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);
        }



    }

}
