<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mapa extends Model
{
    //
    use HasFactory;
    protected $table = "mapas";
    protected $fillable = ['path_file','descripcion','tipomapa_id','predio_id'];


    public function tipomapa(): BelongsTo
    {
        return $this->belongsTo(TipoMapa::class, 'tipomapa_id', 'id');
    }
    public function predio(): BelongsTo
    {
        return $this->belongsTo(Predio::class, 'predio_id', 'id');
    }
}
 