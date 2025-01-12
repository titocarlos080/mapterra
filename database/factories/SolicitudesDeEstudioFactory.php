<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SolicitudesDeEstudio>
 */
class SolicitudesDeEstudioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "descripcion"=> fake()->word(), 
            "json"=> fake()->word(), 
            "fecha"=> fake()->word(), 
            "hora"=> fake()->word()
         ];


    }
}
