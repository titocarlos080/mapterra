<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bicheros', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitud', 10, 6)->nullable(); // Latitud con precisión
            $table->decimal('longitud', 10, 6)->nullable(); // Longitud con precisión
            $table->text('descripcion')->nullable(); // Descripción del bichero
            $table->text('solucion')->nullable(); // Solución para el bichero
            $table->json('json')->nullable(); // Datos adicionales en formato JSON
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('tipo_bichero_id');
            $table->foreignId('lote_id')->constrained('lotes');
            $table->time('hora')->nullable(); // Hora del registro
            $table->date('fecha')->nullable(); // Fecha del registro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bicheros');
    }
};
