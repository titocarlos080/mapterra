<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mapas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('path_file'); 
            $table->string('fecha');
            $table->string('hora');
            $table->string('descripcion');
            $table->foreignId('tipomapa_id')->constrained('tipo_mapas');   
            $table->foreignId('predio_id')->constrained('predios');   

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapas');
    }
};
