<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudesDeEstudio extends Model
{
    
    use HasFactory;
    protected $table = "solicitudes_de_estudios";
    protected $fillable = ["descripcion","json","fecha","hora","estado_id","empresa_id","predio_id"];
    
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
    public function predio(): BelongsTo
    {
        return $this->belongsTo(Predio::class, 'predio_id', 'id');
    }

}
