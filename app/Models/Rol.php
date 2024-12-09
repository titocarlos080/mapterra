<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
      use HasFactory;  
    protected $table ="roles";
      protected $fillable = ["nombre"];
      public function users(): HasMany {
        return $this->hasMany(User::class, "rol_id", "id");  // 'rol_id' es la columna en la tabla users que guarda el ID del rol
    }
    
}
