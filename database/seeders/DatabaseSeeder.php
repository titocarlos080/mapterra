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

     


    public function run(): void
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
      

        $historial = TipoMapa::factory()->create([
            "nombre" => "historial",
            "descripcion" => "es una mapa historial",
            "icon" => "fas fa-history"
        ]);

        $cartografica = TipoMapa::factory()->create([
            "nombre" => "cartografica",
            "descripcion" => "es una mapa cartografica",
            "icon" => "fas fa-map"
        ]);
        $analisisPredio = TipoMapa::factory()->create([
            "nombre" => "Analisis Predio",
            "descripcion" => "es una mapa analisisPredio",
            "icon" => "fas fa-chart-pie"
        ]);
        $estudioCultivo = TipoMapa::factory()->create([
            "nombre" => "Analisis Cultivo",
            "descripcion" => "es una mapa estudioCultivo",
            "icon" => "fas fa-seedling"
        ]);
        
        $monitoreo = TipoMapa::factory()->create([
            "nombre" => "monitoreo",
            "descripcion" => "es una mapa monitoreo",
            "icon" => "fas fa-eye"
        ]); 
        
        
        
        
        
        
        //estados

        Estado::factory()->create(["nombre"=>"recibido"]);
        Estado::factory()->create(["nombre"=>"realizado"]);
        Estado::factory()->create(["nombre"=>"entregado"]);
        
         

    
        $permisos = [
            // Permisos para Empresas
            ['accion' => 'ver_lista_empresas'],
            ['accion' => 'crear_empresa'],
            ['accion' => 'editar_empresa'],
            ['accion' => 'eliminar_empresa'],

            // Permisos para Usuarios
            ['accion' => 'ver_lista_usuarios'],
            ['accion' => 'crear_usuario'],
            ['accion' => 'editar_usuario'],
            ['accion' => 'eliminar_usuario'],

            // Permisos para Roles
            ['accion' => 'ver_lista_roles'],
            ['accion' => 'crear_rol'],
            ['accion' => 'editar_rol'],
            ['accion' => 'eliminar_rol'],

            // Permisos para Permisos
            ['accion' => 'ver_lista_permisos'],
            ['accion' => 'crear_permiso'],
            ['accion' => 'editar_permiso'],
            ['accion' => 'eliminar_permiso'],

            ['accion' => 'asginar_permisos'],
            ['accion' => 'desasginar_permisos'],


            // // Permiso para todos los permisos
            // ['accion' => 'todos los permisos'],

            // Permisos relacionados con Mapas
            ['accion' => 'ver_lista_mapas'],
            ['accion' => 'crear_mapa'],
            ['accion' => 'editar_mapa'],
            ['accion' => 'eliminar_mapa'],

            // Permisos para Solicitudes de Estudio
            ['accion' => 'ver_lista_solicitudes_estudio'],
            ['accion' => 'crear_solicitud_estudio'],
            ['accion' => 'editar_solicitud_estudio'],
            ['accion' => 'eliminar_solicitud_estudio'],

            // Permisos para Bitácora
            ['accion' => 'ver_lista_bitacoras'],
            ['accion' => 'crear_bitacora'],
            ['accion' => 'editar_bitacora'],
            ['accion' => 'eliminar_bitacora'],

            // Permisos para Lotes
            ['accion' => 'ver_lista_lotes'],
            ['accion' => 'crear_lote'],
            ['accion' => 'editar_lote'],
            ['accion' => 'eliminar_lote'],
            ['accion' => 'cargar_lotes_masivamente'],

            // Permisos para Predios
            ['accion' => 'ver_lista_predios'],
            ['accion' => 'crear_predio'],
            ['accion' => 'editar_predio'],
            ['accion' => 'eliminar_predio'],

           ];

        // Insertar todos los permisos en un solo lote
        Permiso::insert($permisos);
        $rol1->permisos()->attach(Permiso::all());   
    
    }
}