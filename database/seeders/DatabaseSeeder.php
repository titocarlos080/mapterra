<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Estado;
use App\Models\Mapa;
use App\Models\Permiso;
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

     public function run(){
        Permiso::factory()->create(["accion"=>"todos los permisos"]);
     }


    public function sdf(): void
    {
        // User::factory(10)->create();
       $rol1 = Rol::factory()->create(['nombre' => 'Administrador'] );
       $rol2 = Rol::factory()->create(['nombre' => 'Cliente'] );
       $rol3 = Rol::factory()->create(['nombre' => 'Personal'] );
       $empresa= Empresa::factory()->create([
        'nombre'=> 'MapTerra',
        'direccion'=>'C-8 Santa Cruz',
        'telefono'=>'123456',
        'ganaderia'=>true,
        'agricultura'=>true
       ]);

        User::factory()->create([
            'name' => 'Tito Carlos',
            'email' => 'titocarlos080@gmail.com',
            'password' => bcrypt('123'),
            'rol_id'=> $rol1->id,
            'empresa_id'=>$empresa->id
        ]);
      

 
        $cartografica = TipoMapa::factory()->create([
            "nombre" => "cartografica",
            "descripcion" => "es una mapa cartografica",
            "icon" => "fas fa-map"
        ]);
        
        $historial = TipoMapa::factory()->create([
            "nombre" => "historial",
            "descripcion" => "es una mapa historial",
            "icon" => "fas fa-history"
        ]);
        
        $monitoreo = TipoMapa::factory()->create([
            "nombre" => "monitoreo",
            "descripcion" => "es una mapa monitoreo",
            "icon" => "fas fa-eye"
        ]);
        
        $estudioCultivo = TipoMapa::factory()->create([
            "nombre" => "estudio Cultivo",
            "descripcion" => "es una mapa estudioCultivo",
            "icon" => "fas fa-seedling"
        ]);
        
        $bichero = TipoMapa::factory()->create([
            "nombre" => "bichero",
            "descripcion" => "es una mapa bichero",
            "icon" => "fas fa-bug"
        ]);
        
        $solicitudEstudio = TipoMapa::factory()->create([
            "nombre" => "solicitud Estudio",
            "descripcion" => "es una mapa solicitudEstudio",
            "icon" => "fas fa-clipboard-check"
        ]);
        
        $analisisPredio = TipoMapa::factory()->create([
            "nombre" => "analisis Predio",
            "descripcion" => "es una mapa analisisPredio",
            "icon" => "fas fa-chart-pie"
        ]);
        
        
        //estados

        Estado::factory()->create(["nombre"=>"recibido"]);
        Estado::factory()->create(["nombre"=>"realizado"]);
        Estado::factory()->create(["nombre"=>"entregado"]);
 
    }
}