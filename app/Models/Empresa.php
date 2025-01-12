<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
  //
  use HasFactory;
  protected $fillable = ['nombre', 'direccion', 'telefono', 'ganaderia', 'agricultura'];
  public function users(): HasMany
  {
    return $this->hasMany(User::class, "empresa_id", "id");

  }

  public function predios(): HasMany
  {
    return $this->hasMany(Predio::class, "empresa_id", "id");
  }
  public function bitacoras(): HasMany
  {
    return $this->hasMany(Bitacora::class, "empresa_id", "id");
  }

}
