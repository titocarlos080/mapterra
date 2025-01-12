<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = ['usuario_id', 'accion', 'tabla_afectada','descripcion', 'empresa_id',];

    public function empresa(): BelongsTo
    {
      return $this->BelongsTo(Predio::class, "empresa_id", "id");
    }

}
