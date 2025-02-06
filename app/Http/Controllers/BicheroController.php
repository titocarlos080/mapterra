<?php

namespace App\Http\Controllers;

use App\Models\Bichero;
use App\Models\Empresa;
use App\Models\ImagenesBichero;
use App\Models\Mapa;
use App\Models\Predio;
use App\Models\TipoMapa;
use Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Str;

class BicheroController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function lista(){
      
        $bicheros = Bichero::paginate(10);
        return view('empresa.bichero', compact('bicheros'));
    }

    public function index($tipoMapa, $empresaId, $predioId)
    {
        //
        try {
            $empresa = Empresa::findOrFail($empresaId);
            $predio = Predio::where('empresa_id', $empresaId)->where('id', $predioId)->firstOrFail();
            $tipomapas = TipoMapa::all();
            $mapas = Mapa::where('tipomapa_id', $tipoMapa)->where('predio_id', $predioId)->paginate(5);
            $tipoMapa = TipoMapa::findOrFail($tipoMapa);

            BitacoraController::store("visualizó mapas para cliente", 'mapas', "Se visualizaron los mapas del tipo {$tipoMapa->nombre} para el cliente");

            return view('clients.bichero', compact('empresa', 'predio', 'tipomapas', 'mapas', 'tipoMapa'));
        } catch (\Exception $e) {
            BitacoraController::store("error al visualizar mapas para cliente", 'mapas', $e->getMessage());
            return back()->with('error', 'Error al cargar los mapas para el cliente.');
        }


    }

    public function listaBichero($tipoMapa, $empresaId, $predioId)
    {
        //
        try {
            $empresa = Empresa::findOrFail($empresaId);
            $predio = Predio::where('empresa_id', $empresaId)->where('id', $predioId)->firstOrFail();
            $tipomapas = TipoMapa::all();
            $mapas = Mapa::where('tipomapa_id', $tipoMapa)->where('predio_id', $predioId)->paginate(5);
            $tipoMapa = TipoMapa::findOrFail($tipoMapa);
            $bicheros= Bichero::where('empresa_id',$empresaId)->paginate(5);

            BitacoraController::store("visualizó bicheros para cliente", 'bichero', "Se visualizaron los mapas del tipo {$tipoMapa->nombre} para el cliente");

            return view('clients.bichero-lista', compact('empresa', 'predio', 'tipomapas', 'mapas', 'tipoMapa','bicheros'));

        } catch (\Exception $e) {
            BitacoraController::store("error al visualizar mapas para cliente", 'bichero', $e->getMessage());
            return back()->with('error', 'Error al cargar los mapas para el cliente.');
        }


    }







    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            // Obtener el ID de la empresa desde el usuario autenticado
            $empresaId = Auth::user()->empresa->id;

            // Crear una nueva instancia del modelo Bichero
            $bichero = new Bichero();
            $bichero->tipo_bichero_id = $request->input('tipo-bichero'); // Asignar el tipo de bichero
            $bichero->descripcion = $request->input('descripcion'); // Asignar la descripción
            $bichero->solucion = $request->input('solucion'); // Asignar la descripción
            $bichero->json = $request->input('map-data'); // Asignar el JSON de datos del mapa
            $bichero->hora = now()->format('H:i:s'); // Hora actual en formato H:i:s
            $bichero->fecha = now()->toDateString(); // Fecha actual en formato YYYY-MM-DD
            $bichero->latitud = $request->input("latitud");
            $bichero->longitud = $request->input("longitud");
            $bichero->lote_id = $request->input("loteId");
            $bichero->empresa_id = $empresaId; // Asociar con la empresa

            // Guardar en la base de datos
            $bichero->save();
            if ($request->hasFile('photos')) {
                $empresa = Auth::user()->empresa;
                $nombreEmpresa = Str::slug($empresa->nombre, '_');
                $contador = 1;
    
                foreach ($request->file('photos') as $archivo) {
                    // Generar la ruta y guardar el archivo
                    $rutaArchivo = $archivo->storeAs(
                        "{$nombreEmpresa}/fotos/bicheros",
                        sprintf('%s_%s_%s.%s.%s', 
                            $bichero->lote_id, 
                            now()->toDateString(), 
                            now()->format('H-i-s'), 
                            $contador,
                            $archivo->getClientOriginalExtension()
                        ),
                        'public'
                    );
    
                    // Crear un nuevo registro de imagen asociada
                    $imagen = new ImagenesBichero();
                    $imagen->archivo = $rutaArchivo;
                    $imagen->bichero_id = $bichero->id;
                    $imagen->save();
    
                    $contador++;
                }
            }
            DB::commit();
            return back()->with('success', 'Datos enviados correctamente.');
        } catch (\Exception $e) {
           DB::rollBack();
           dd($e);
            // Manejar errores y redirigir con un mensaje de error
            return back()->with('error', 'No se pudo enviar correctamente. Intente nuevamente.' . $e);
        }
    }
}
