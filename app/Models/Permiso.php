<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permiso extends Model
{
    use HasFactory;

    protected $table = "permisos"; // Cambiado para que coincida con el nombre de la tabla correcta

    protected $fillable = ["nombre"]; // Columnas que se pueden asignar masivamente

    public function roles(): HasMany
    {
        return $this->hasMany(RolPermiso::class, "permiso_id", "id"); // Relación con RolPermiso
    }

}
