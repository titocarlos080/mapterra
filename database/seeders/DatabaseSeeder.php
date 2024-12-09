<?php

namespace Database\Seeders;

use App\Models\Mapa;
use App\Models\TipoMapa;
use App\Models\User;
use App\Models\Rol;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
       $rol1 = Rol::factory()->create(['nombre' => 'Administrador'] );
       $rol2 = Rol::factory()->create(['nombre' => 'Cliente'] );
       $rol3 = Rol::factory()->create(['nombre' => 'Personal'] );


        User::factory()->create([
            'name' => 'Tito Carlos',
            'email' => 'titocarlos080@gmail.com',
            'password' => bcrypt('123'),
            'rol_id'=> $rol1->id
        ]);
        User::factory()->create([
            'name' => 'kdeoro',
            'email' => 'kdeoro@gmail.com',
            'password' => bcrypt('123'),
            'rol_id'=> $rol2->id
        ]);
        User::factory()->create([
            'name' => 'Juan perez',
            'email' => 'juanperez@gmail.com',
            'password' => bcrypt('123'),
            'rol_id'=> $rol3->id
        ]);


        $cartografica= TipoMapa::factory()->create(["nombre"=> "cartografica","descripcion"=>"es una mapa cartografica"] );
        $historial= TipoMapa::factory()->create(["nombre"=> "historial","descripcion"=>"es una mapa historial"]);
        $monitoreo= TipoMapa::factory()->create([ "nombre"=> "monitoreo","descripcion"=>"es una mapa monitoreo"]);
        $estudioCultivo= TipoMapa::factory()->create(["nombre"=> "estudioCultivo","descripcion"=>"es una mapa estudioCultivo" ]);
        $bichero= TipoMapa::factory()->create([ "nombre"=> "bichero","descripcion"=>"es una mapa bichero" ]);
        $solicitudEstudio= TipoMapa::factory()->create(["nombre"=> "solicitudEstudio","descripcion"=>"es una mapa solicitudEstudio"  ]);
        $analisisPredio= TipoMapa::factory()->create(["nombre"=> "analisisPredio","descripcion"=>"es una mapa analisisPredio"  ]);
        
        Mapa::factory()->create([]);

    }
}