<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bichero extends Model
{
    //


    
    protected $table = "bicheros";

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'latitud',
        'longitud',
        'descripcion', // Descripción del registro
        'solucion',
        'json',        // Datos en formato JSON
        'empresa_id',  // ID de la empresa
        'lote_id',  // ID de la empresa
        'tipo_bichero_id',  // ID tipo de bichero
        'hora',        // Hora del registro
        'fecha'        // Fecha del registro
    ];
    public function tipoBichero(): BelongsTo
    {
        return $this->belongsTo(TipoBichero::class, 'tipo_bichero_id', 'id');
    }
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'lote_id', 'id');
    }
    
    public function imagenes(): HasMany
    {
      return $this->hasMany(ImagenesBichero::class, "bichero_id", "id");
  
    }
}
