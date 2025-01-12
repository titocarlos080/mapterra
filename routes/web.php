<?php

use App\Http\Controllers\BicheroController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PredioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SolicitudesDeEstudioController;
use App\Http\Controllers\TipoBicheroController;
use App\Http\Controllers\TipoMapaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;


// SIN AUTENTICACON
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/olvide_password', [AuthController::class, 'olvide_password'])->name('olvide_password');

//SECCION DE RECUPERACION DE PASSWORD
Route::post('/password_email', [AuthController::class, 'password_email'])->name('password_email');









// CON AUTENTICACION

Route::middleware(['auth.cliente'])->group(function () {
    // HOME
    Route::get('/cliente', [HomeController::class, 'cliente'])->name('cliente');
    // PREDIOS
    Route::get('/cliente/predio/{empresaId}/{predioId}', [PredioController::class, 'getPredio'])->name('cliente-predio');
   
    //MAPAS
    Route::get('/cliente/mapas/{TipoMapaId}/{empresaId}/{predioId}', [MapaController::class, 'getMapas'])->name('cliente-mapas');
   
    // USUARIOS
    Route::get('/cliente/users', [UserController::class, 'usuariosPorEmpresa'])->name('cliente-users');
    Route::post('cliente/users/store', [UserController::class, 'store'])->name('cliente-usuarios-store'); // Guardar usuario
    Route::post('cliente/users/update', [UserController::class, 'update'])->name('cliente-usuarios-update'); // Actualizar usuario
    Route::post('cliente/users/delete/{id}', [UserController::class, 'delete'])->name('cliente-usuarios-delete'); // Eliminar usuario
    
    //PERMISOS
    Route::post('/cliente/users/permisos', [PermisoController::class, 'permisosPorUsuario'])->name('cliente-users-permisos-store');

    // SOLICITUD DE ESTUDIO
    Route::get('/cliente/solicitud-estudio', [SolicitudesDeEstudioController::class, 'clienteSolicitudEstudio'])->name('cliente-solicitud-estudio');
    Route::post('/cliente/solicitud-estudio/store', [SolicitudesDeEstudioController::class, 'store'])->name('cliente-solicitud-estudio-store');


});


Route::middleware(['auth.mapterra'])->group(function () {

    //PARTE ADMINISTRATIVA
    Route::get('/home', [HomeController::class, 'home'])->name('home');

    Route::get('admin/users', [UserController::class, 'index'])->name('admin-usuarios'); // Listar usuarios
    Route::post('admin/users/store', [UserController::class, 'store'])->name('admin-usuarios-store'); // Guardar usuario
    Route::get('admin/users/edit/{id}', [UserController::class, 'edit'])->name('admin-usuarios-edit'); // Editar usuario
    Route::post('admin/users/update', [UserController::class, 'update'])->name('admin-usuarios-update'); // Actualizar usuario
    Route::post('admin/users/delete/{id}', [UserController::class, 'delete'])->name('admin-usuarios-delete'); // Eliminar usuario

    // ROLES
    Route::get('admin/roles', [RolController::class, 'index'])->name('admin-roles'); // Listar roles
    Route::post('admin/roles/store', [RolController::class, 'store'])->name('admin-roles-store'); // Guardar rol
    Route::get('admin/roles/edit/{id}', [RolController::class, 'edit'])->name('admin-roles-edit'); // Editar rol
    Route::post('admin/roles/update', [RolController::class, 'update'])->name('admin-roles-update'); // Actualizar rol
    Route::post('admin/roles/delete/{id}', [RolController::class, 'delete'])->name('admin-roles-delete'); // Eliminar rol

    // PERMISOS
    Route::get('admin/permisos', [PermisoController::class, 'index'])->name('admin-permisos'); // Listar permisos
    Route::post('admin/permisos/store', [PermisoController::class, 'store'])->name('admin-permisos-store'); // Guardar permiso
    Route::get('admin/permisos/edit/{id}', [PermisoController::class, 'edit'])->name('admin-permisos-edit'); // Editar permiso
    Route::post('admin/permisos/update', [PermisoController::class, 'update'])->name('admin-permisos-update'); // Actualizar permiso
    Route::post('admin/permisos/delete/{id}', [PermisoController::class, 'delete'])->name('admin-permisos-delete'); // Eliminar permiso


    //BITACORA
    Route::get('admin/bitacora', [BitacoraController::class, 'index'])->name('admin-bitacora');



    //EMPRESAS
    Route::get('admin/empresas', [EmpresaController::class, 'empresas'])->name('admin-empresas');
    Route::get('admin/empresas/edit/{id}', [EmpresaController::class, 'edit'])->name('admin-empresas-edit');
    Route::post('admin/empresas/update', [EmpresaController::class, 'update'])->name('admin-empresas-update');
    Route::post('admin/empresas/store', [EmpresaController::class, 'store'])->name('admin-empresas-store');
    Route::post('admin/empresas/delete/{id}', [EmpresaController::class, 'delete'])->name('admin-empresas-delete');

    //PREDIOS
    Route::get('admin/predios', [PredioController::class, 'index'])->name('admin-predios');
    Route::get('admin/predios/empresa/{empresaId}', [PredioController::class, 'index'])->name('admin-predios-empresa');
    Route::get('admin/predios/edit/{id}', [PredioController::class, 'edit'])->name('admin-predios-edit');
    Route::post('admin/predios/update', [PredioController::class, 'update'])->name('admin-predios-update');
    Route::post('admin/predios/store', [PredioController::class, 'store'])->name('admin-predios-store');
    Route::post('admin/predios/delete/{id}', [PredioController::class, 'delete'])->name('admin-predios-delete');

    // TIPOS DE MAPAS
    Route::get('admin/tiposmapas', [TipoMapaController::class, 'index'])->name('admin-tiposmapas');
    Route::get('admin/tiposmapas/edit/{id}', [TipoMapaController::class, 'edit'])->name('admin-tiposmapas-edit');
    Route::post('admin/tiposmapas/update/{id}', [TipoMapaController::class, 'update'])->name('admin-tiposmapas-update');
    Route::post('admin/tiposmapas/store', [TipoMapaController::class, 'store'])->name('admin-tiposmapas-store');
    Route::post('admin/tiposmapas/delete/{id}', [TipoMapaController::class, 'delete'])->name('admin-tiposmapas-delete');


    //MAPAS
    Route::get('admin/mapas/predio/{empresaId}/{predioId}', [MapaController::class, 'mapas'])->name('admin-mapas-predio');
    Route::get('admin/mapas/tipompa/{tipoMapa}/{empresaId}/{predioId}', [MapaController::class, 'index'])->name('admin-mapas');
    Route::get('admin/mapas/edit/{id}', [MapaController::class, 'edit'])->name('admin-mapas-edit');
    Route::post('admin/mapas/update', [MapaController::class, 'update'])->name('admin-mapas-update');
    Route::post('admin/mapas/store', [MapaController::class, 'store'])->name('admin-mapas-store');
    Route::post('admin/mapas/delete/{id}', [MapaController::class, 'delete'])->name('admin-mapas-delete');


    // LOTES
    Route::get('admin/lotes/{empresaId}/{predioId}', [LoteController::class, 'index'])->name('admin-lotes'); // Listar lotes
    Route::post('admin/lotes/store', [LoteController::class, 'store'])->name('admin-lotes-store'); // Guardar lote
    Route::post('admin/lotes/store/masiva', [LoteController::class, 'cargaMasiva'])->name('admin-lotes-import');
    Route::get('admin/lotes/edit/{id}', [LoteController::class, 'edit'])->name('admin-lotes-edit'); // Editar lote
    Route::post('admin/lotes/update', [LoteController::class, 'update'])->name('admin-lotes-update'); // Actualizar lote
    Route::post('admin/lotes/delete/{id}', [LoteController::class, 'delete'])->name('admin-lotes-delete'); // Eliminar lote

    // TIPO DE BICHERO
    Route::get('admin/tipobicheros', [TipoBicheroController::class, 'index'])->name('empresa-tipobicheros'); // Listar tipo de bichero
    Route::post('admin/tipobicheros/store', [TipoBicheroController::class, 'store'])->name('empresa-tipobicheros-store'); // Guardar tipo de bichero
    Route::get('admin/tipobicheros/edit/{id}', [TipoBicheroController::class, 'edit'])->name('empresa-tipobicheros-edit'); // Editar tipo de bichero
    Route::post('admin/tipobicheros/update', [TipoBicheroController::class, 'update'])->name('empresa-tipobicheros-update'); // Actualizar tipo de bichero
    Route::post('admin/tipobicheros/delete/{id}', [TipoBicheroController::class, 'delete'])->name('empresa-tipobicheros-delete'); // Eliminar tipo de bichero

    // BICHERO
    Route::get('admin/bichero', [BicheroController::class, 'index'])->name('empresa-bichero'); // Listar bichero
    Route::post('admin/bichero/store', [BicheroController::class, 'store'])->name('empresa-bichero-store'); // Guardar bichero
    Route::get('admin/bichero/edit/{id}', [BicheroController::class, 'edit'])->name('empresa-bichero-edit'); // Editar bichero
    Route::post('admin/bichero/update', [BicheroController::class, 'update'])->name('empresa-bichero-update'); // Actualizar bichero
    Route::post('admin/bichero/delete/{id}', [BicheroController::class, 'delete'])->name('empresa-bichero-delete'); // Eliminar bichero

    //SOLICITUDE DE ESTUDIO
    Route::get('admin/pages/solicitudes', [SolicitudesDeEstudioController::class, 'index'])->name('solicitud-estudio');
    Route::get('admin/pages/solicitudes/edit/{id}', [SolicitudesDeEstudioController::class, 'edit'])->name('solicitud-estudio-edit');
    Route::get('admin/pages/solicitudes/update', [SolicitudesDeEstudioController::class, 'update'])->name('solicitud-estudio-update');
    Route::post('admin/pages/solicitudes/delete/{id}', [SolicitudesDeEstudioController::class, 'delete'])->name('solicitud-estudio-delete');




    Route::post('empresa/predios/mapas/{predio}/{tipomapa}', [MapaController::class, 'mapaStore'])->name('empresa.predios.mapa.store');
    // Editar un mapa específico
    Route::get('empresa/predios/mapas/edit/{predio}/{mapa}', [MapaController::class, 'edit'])->name('empresa.predio.mapa.edit');
    // Actualizar un mapa específico
    Route::put('empresa/predios/mapas/update/{predioId}/{mapaId}', [MapaController::class, 'update'])->name('empresa.predio.mapa.update');
    // Eliminar un mapa específico por el Id
    Route::post('empresa/predios/mapas/delete/{predioId}/{mapaId}', [MapaController::class, 'delete'])->name('empresa.predio.mapa.destroy');


    /// RUTAS DE PREDIOS GANADERIA
    Route::get('admin/pages/potrero/{empresa}/{predio}', [PredioController::class, 'potrero'])->name('potrero.index');
    Route::get('admin/pages/hatoGanadero/{empresa}/{predio}', [PredioController::class, 'hatoGanadero'])->name('hato-ganadero.index');




});



