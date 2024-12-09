<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Predio extends Model
{
    //
    use HasFactory;
    protected $table = "predios"; 
    protected $fillable = ["nombre","path_kmz","municipio","provincia","departamento,empresa_id"];
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
    public function mapas(): HasMany {
        return $this->hasMany(Mapa::class, "predio_id", "id");  // 'rol_id' es la columna en la tabla users que guarda el ID del rol
    }

}
