<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMapa extends Model
{
    
    use HasFactory;
    protected $table = "tipo_mapas";
    protected $fillable = ['nombre','descripcion','icon'];
    public function mapas(): HasMany {
        return $this->hasMany(Mapa::class, "tipomapa_id", "id");  // 'rol_id' es la columna en la tabla users que guarda el ID del rol
    }

}
