<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Empresa;
use Auth;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->rol->permisos->contains('accion', 'ver_lista_bitacoras')) {
            // Validación de los datos
            return back()->with("error", "No tiene permisos para ver los registros de la bitacora");
        }
        
        $bitacoras = Bitacora::paginate(10);
        $empresas = Empresa::all();
        return view('empresa.bitacora',compact('bitacoras','empresas'));

        
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public static function store($accion,$tabla,$descripcion)
    {
        Bitacora::create([
            'usuario_id' => Auth::user()->id,
            'accion' => $accion,
            'tabla_afectada' => $tabla,
            'empresa_id' => Auth::user()->empresa->id,
            'descripcion' => $descripcion,
        ]);
    }
   
 
    
}
