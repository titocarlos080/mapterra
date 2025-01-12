<?php

use App\Models\Bitacora;
// protected $fillable = ['usuario_id', 'accion', 'tabla_afectada', 'empresa_id',descripcion];

function registrarBitacora($accion, $tabla,   $descripcion = null)
{
    Bitacora::create([
        'usuario_id' => Auth::user()->id,
        'accion' => $accion,
        'tabla_afectada' => $tabla,
        'empresa' => Auth::user()->empresa->id,
        'descripcion' => $descripcion,
    ]);
}









 