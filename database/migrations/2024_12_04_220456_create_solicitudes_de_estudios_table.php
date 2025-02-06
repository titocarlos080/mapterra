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
        //    protected $fillable = ["descripcion","json","fecha","hora","empresa_id"];

        Schema::create('solicitudes_de_estudios', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->text('json');
            $table->string('fecha');
            $table->string('hora');
            $table->foreignId('estado_id')->constrained('estados'); 
            $table->foreignId('empresa_id')->constrained('empresas'); // asociada a cada empresa
            $table->foreignId('predio_id')->nullable()->constrained('predios'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_de_estudios');
    }
};
