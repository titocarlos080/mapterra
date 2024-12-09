<?php

namespace App\Http\Controllers;
use App\Models\Mapa;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

use App\Models\Empresa;
use App\Models\Predio;
use Illuminate\Support\Facades\Event; // Use the Event facade
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class PredioController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index($nombre, $id)
    {
        // Obtener la empresa
        $empresa = Empresa::findOrFail($id);

        // Obtener los predios de la empresa
        $predios = Predio::where('empresa_id', $id)->paginate( 5); // Paginación de 10 predios por página

        return view('empresa.predios.index', compact('empresa', 'predios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $empresaId)
    {
        // Validación de los datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'archivo_kmz' => 'nullable|file|max:10240', // Validación del archivo KMZ
        ]);

        try {
            //code...
            $empresa = Empresa::findOrFail($empresaId);
            // Crear un nuevo predio

            $predio = new Predio();
            $predio->nombre = $validated['nombre'];
            $predio->municipio = $validated['municipio'];
            $predio->provincia = $validated['provincia'];
            $predio->departamento = $validated['departamento'];
            $predio->empresa_id = $empresa->id;
            // Manejo del archivo KMZ
            if ($request->hasFile('archivo_kmz')) {
                $archivo = $request->file('archivo_kmz');

                // Normalizar los nombres para evitar problemas con espacios u otros caracteres
                $nombreEmpresa = Str::slug($empresa->nombre, '_'); // Reemplaza espacios con guiones bajos
                $nombrePredio = Str::slug($predio->nombre, '_');   // Reemplaza espacios con guiones bajos

                // Generar la ruta de almacenamiento
                $rutaArchivo = $archivo->storeAs(
                    "{$nombreEmpresa}/predios", // Carpeta de destino
                    "{$nombrePredio}.kmz",      // Nombre del archivo
                    'public'
                );

                // Guardar la ruta en el modelo del predio
                $predio->path_kmz = $rutaArchivo;
                $predio->save();
            }



            // Redirigir con un mensaje de éxito
            return redirect()->route('empresa.predios', [$empresa->nombre, $empresaId])->with('success', 'Predio creado exitosamente');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }




    }

    /**
     * Display the specified resource.
     */
    public function show($empresaId, $id)
    {
        // Buscar la empresa y el predio por sus IDs
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($id);
    
          // Retornar la vista pasando las variables necesarias
        return view('empresa.predios.show', compact('empresa', 'predio'));
    }
    
  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Predio $predio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Predio $predio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Predio $predio)
    {
        //
    }

// funciones para redirigir los predios por tipo de mapas
    public function cartografia($empresaId,$predioId){

           $empresa = Empresa::findOrFail($empresaId);
           $predio = Predio::findOrFail($predioId);
          $mapas = $predio->mapas;
          dd($mapas);
           return view('empresa.predios.agricultura.cartografia.index', compact('empresa','predio'));

    }
    public function historicos($empresaId,$predioId){
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        return view('empresa.predios.agricultura.historico.index', compact('empresa','predio'));


    }
    public function analisisPredio($empresaId,$predioId){
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        return view('empresa.predios.agricultura.analisis-predio.index', compact('empresa','predio'));
    }
    public function analisisCultivo($empresaId,$predioId){
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        return view('empresa.predios.agricultura.analisis-cultivo.index', compact('empresa','predio'));
    } 
    
    public function monitoreo($empresaId,$predioId){
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        return view('empresa.predios.agricultura.monitoreo.index', compact('empresa','predio')) ;
        
    }


}
