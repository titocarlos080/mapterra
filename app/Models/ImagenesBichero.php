<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenesBichero extends Model
{
    //
    protected $table = "imagenes_bicheros";
    protected $fillable=['archivo','bichero_id'];
    public function bichero(): BelongsTo
    {
        return $this->belongsTo(TipoBichero::class, 'bichero_id', 'id');
    } 
}
