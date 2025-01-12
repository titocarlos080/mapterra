<?php

namespace App\Http\Controllers;
use App\Models\Mapa;
use App\Models\SolicitudesDeEstudio;
use App\Models\TipoMapa;
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
    public function index( $empresaId)
    {
         // Obtener la empresa
        $empresa = Empresa::findOrFail($empresaId);

        // Obtener los predios de la empresa
        $predios = Predio::where('empresa_id', $empresaId)->paginate(5); // Paginación de 10 predios por página

        return view('empresa.predios', compact('empresa', 'predios'));
    }

   
    public function store(Request $request)
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
            $empresa = Empresa::findOrFail($request->empresaId);
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
                    "{$nombrePredio}." . $archivo->getClientOriginalExtension(),      // Nombre del archivo
                    'public'
                );

                // Guardar la ruta en el modelo del predio
                $predio->path_kmz = $rutaArchivo;
                $predio->save();
            }



            // Redirigir con un mensaje de éxito
            return back()->with('success', 'Predio creado exitosamente');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', $th->getMessage());
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



     
    public function update(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'empresa_id' => 'required|integer|exists:empresas,id',
            'predio_id' => 'required|integer|exists:predios,id',
            'nombre' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre del predio es obligatorio.',
            'municipio.required' => 'El municipio es obligatorio.',
            'provincia.required' => 'La provincia es obligatoria.',
            'departamento.required' => 'El departamento es obligatorio.',
        ]);
    
        try {
            // Obtener el predio por ID
            $predio = Predio::findOrFail($request->predio_id);
    
            // Actualizar los datos del predio
            $predio->nombre = $request->nombre;
            $predio->municipio = $request->municipio;
            $predio->provincia = $request->provincia;
            $predio->departamento = $request->departamento;
            $predio->empresa_id = $request->empresa_id;
    
            // Guardar los cambios
            $predio->save();
    
            // Redirigir con mensaje de éxito
            return back()->with('success', 'Predio actualizado correctamente.');
        } catch (\Exception $e) {
            // Manejar cualquier excepción y redirigir con un mensaje de error
            return back()->with('error', 'Ocurrió un error al actualizar el predio: ' . $e->getMessage());
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function delete($predioId)
    {
        // 
         try {
            //code...
            $predio=Predio::findOrFail($predioId);

            $predio->delete();
    
    
            return back()->with('success','Elimininado correctamente'.$th);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','Error al intertar eliminar'.$th);
           
        }
    
    }

    // funciones para redirigir los predios por tipo de mapas
    public function cartografia($empresaId, $predioId)
    {
        // Fetch the empresa and predio
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);

        // Fetch the mapas with pagination
        $mapas = Mapa::where('predio_id', $predio->id)
            ->where('tipomapa_id', '1')
            ->paginate(5);  // You can adjust the number 10 as per your requirement

        // Return the view with the data
        return view('empresa.predios.agricultura.cartografia.index', data: compact('empresa', 'predio', 'mapas'));
    }

    public function historicos($empresaId, $predioId)
    {
        // Fetch the empresa and predio
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);

        // Fetch the mapas with pagination
        $mapas = Mapa::where('predio_id', $predio->id)
            ->where('tipomapa_id', '2')
            ->paginate(5);  // You can adjust the number 10 as per your requirement

        // Return the view with the data
        return view('empresa.predios.agricultura.historico.index', data: compact('empresa', 'predio', 'mapas'));

    }

    public function monitoreo($empresaId, $predioId)
    {
        // Fetch the empresa and predio
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);

        // Fetch the mapas with pagination
        $mapas = Mapa::where('predio_id', $predio->id)
            ->where('tipomapa_id', '3')
            ->paginate(5);  // You can adjust the number 10 as per your requirement

        // Return the view with the data
        return view('empresa.predios.agricultura.monitoreo.index', data: compact('empresa', 'predio', 'mapas'));

    }
    public function analisisCultivo($empresaId, $predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        // Fetch the mapas with pagination
        $mapas = Mapa::where('predio_id', $predio->id)
            ->where('tipomapa_id', '4')
            ->paginate(5);  // You can adjust the number 10 as per your requirement

        return view('empresa.predios.agricultura.analisis-cultivo.index', compact('empresa', 'predio', 'mapas'));
    }

    public function solicitudEstudio($empresaId, $predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        // Fetch the mapas with pagination
        $mapas = Mapa::where('predio_id', $predio->id)
            ->where('tipomapa_id', '6')
            ->paginate(5);  // You can adjust the number 10 as per your requirement

        return view('empresa.predios.agricultura.solicitud-estudio.index', compact('empresa', 'predio', 'mapas'));
    }

    public function analisisPredio($empresaId, $predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        // Fetch the mapas with pagination
        $mapas = Mapa::where('predio_id', $predio->id)
            ->where('tipomapa_id', '7')
            ->paginate(5);  
        return view('empresa.predios.agricultura.analisis-predio.index', compact('empresa', 'predio', 'mapas'));
    }


    //PARTE DE ACCESO A CLIENTES A SUS RESPECTIVOS PREDIOS

    public function getPredio($empresaId,$predioId)
    { 

        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $mapas = Mapa::where('predio_id', $predio->id)
        ->where('tipomapa_id', '1')
        ->paginate(5); 
        $tipomapas= TipoMapa::all(); 
    // Return the view with the data
    return view('clients.predios', data: compact('empresa', 'predio', 'mapas','tipomapas'));
    
}

    public function clienteCartografia($empresaId,$predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $mapas = Mapa::where('predio_id', $predio->id)
        ->where('tipomapa_id', '1')
        ->paginate(5);  
    // Return the view with the data
    return view('clients.predios.cartografias', data: compact('empresa', 'predio', 'mapas'));
    }


    public function clienteHistorico($empresaId,$predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $mapas = Mapa::where('predio_id', $predio->id)
        ->where('tipomapa_id', '2')
        ->paginate(5);  
    // Return the view with the data
    return view('clients.predios.historicos', data: compact('empresa', 'predio', 'mapas'));
    

    }
 
   
    public function clienteMonitoreo($empresaId,$predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $mapas = Mapa::where('predio_id', $predio->id)
        ->where('tipomapa_id', '3') //cambiar 
        ->paginate(5);  
    // Return the view with the data
    return view('clients.predios.monitoreos', data: compact('empresa', 'predio', 'mapas'));
    

    }

    public function clienteAnalisisCultivo($empresaId,$predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $mapas = Mapa::where('predio_id', $predio->id)
        ->where('tipomapa_id', '4')
        ->paginate(5);  
    // Return the view with the data
    return view('clients.predios.analisis-cultivo', data: compact('empresa', 'predio', 'mapas'));
    

    }
    public function clienteAnalisisPredio($empresaId,$predioId)
    {
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
        $mapas = Mapa::where('predio_id', $predio->id)
        ->where('tipomapa_id', '7')
        ->paginate(5);  
    // Return the view with the data
    return view('clients.predios.analisis-predio', data: compact('empresa', 'predio', 'mapas'));
    }

    public function clienteSolicitudTrabajo($empresaId,$predioId)
    {
       
        $empresa = Empresa::findOrFail($empresaId);
        $predio = Predio::findOrFail($predioId);
      
        return view('clients.predios.solicitud-trabajo', data: compact('empresa', 'predio'));

    }
    
    public function clienteSolicitudTrabajoStore(Request $request)
    {
        // Verifica los datos recibidos
        $descripcion = $request->input('descripcion');
        $json = $request->input('json');
    
        
        // Valida los datos
        if (!$descripcion || !$json) {
            return response()->json(['message' => 'Faltan datos'], 400);
        }
    
        try {
            //code...
            $solicitud= new SolicitudesDeEstudio();
            $solicitud->descripcion = $descripcion;
            $solicitud->json = $json;
            $solicitud->fecha = now()->toDateString(); // Fecha actual sin hora (YYYY-MM-DD)
            $solicitud->hora = now()->format('H:i:s'); // Hora actual Hms
            $solicitud->save();
            return response()->json(['message'=> 'Datos recibidos correctamente'],200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message'=>  $th->getMessage()],500);
        }
         
    }
    
}
