<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoBichero extends Model
{
    //
    protected $table = "tipo_bicheros";
    protected $fillable = ["nombre"];
    public function bicheros(): HasMany
    {
      return $this->hasMany(Bichero::class, "tipo_bichero_id", "id");
  
    }
}
