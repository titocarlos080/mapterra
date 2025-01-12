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
        $trabajos = SolicitudesDeEstudio::where('estado_id','=',1)->get();
        return view("empresa.solicitud-estudio",compact('trabajos'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function clienteSolicitudEstudio()
    {
         
        $empresa = Auth::user()->empresa;
       
        return view('clients.solicitud-estudio',  compact('empresa'));

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         
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

    /**
     * Display the specified resource.
     */
    public function show(SolicitudesDeEstudio $solicitudesDeEstudio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SolicitudesDeEstudio $solicitudesDeEstudio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SolicitudesDeEstudio $solicitudesDeEstudio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SolicitudesDeEstudio $solicitudesDeEstudio)
    {
        //
    }
}
