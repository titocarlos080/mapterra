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
        $bitacoras = Bitacora::paginate(10);
        $empresas = Empresa::all();
        return view('empresa.bitacora',compact('bitacoras','empresas'));

        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
