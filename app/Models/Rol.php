<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
  use HasFactory;
  protected $table = "roles";
  protected $fillable = ["nombre"];
  public function users(): HasMany
  {
    return $this->hasMany(User::class, "rol_id", "id");

  }
  public function permisos(): BelongsToMany
  {
      return $this->belongsToMany(Permiso::class, 'rol_permisos', 'rol_id', 'permiso_id');
  }
}
