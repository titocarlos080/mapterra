<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    use HasFactory;

  
    protected $table = 'lotes';

     protected $fillable = [
        'codigo',
        'nombre',
        'hectareas',
        'predio_id',
    ];

 
    public function predio()
    {
        return $this->belongsTo(Predio::class,'predio_id','id');
    }
    public function bicheros(): HasMany
    {
      return $this->hasMany(Bichero::class, "lote_id", "id");
  
    }
}
