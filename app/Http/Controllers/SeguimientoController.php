<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Mapa;
use App\Models\Predio;
use App\Models\TipoMapa;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    //

    public function index($tipoMapa, $empresaId, $predioId)  {
        try {
            $empresa = Empresa::findOrFail($empresaId);
            $predio = Predio::where('empresa_id', $empresaId)->where('id', $predioId)->firstOrFail();
            $tipomapas = TipoMapa::all();
            $mapas = Mapa::where('tipomapa_id', $tipoMapa)->where('predio_id', $predioId)->paginate(5);
            $tipoMapa = TipoMapa::findOrFail($tipoMapa);

            BitacoraController::store("visualizó mapas para cliente", 'mapas', "Se visualizaron los mapas del tipo {$tipoMapa->nombre} para el cliente");

            return view('clients.seguimiento', compact('empresa', 'predio', 'tipomapas', 'mapas', 'tipoMapa'));
        } catch (\Exception $e) {
            BitacoraController::store("error al visualizar mapas para cliente", 'mapas', $e->getMessage());
            return back()->with('error', 'Error al cargar los mapas para el cliente.');
        }
    }
    public function store(Request $request)  {
        dd($request->all());
    }


    public function delete()  {
        
    }

    
    

}
