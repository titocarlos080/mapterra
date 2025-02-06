<!-- // Solicitud de estudio -->
@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">

@section('title', $empresa->nombre . ' - Solicitud Estudio')

@section('content_header')
@stop
@section('sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/cliente" class="brand-link">
        <img src="{{asset('vendor/adminlte/dist/img/mapterralogo.webp')}}" alt="Admin Logo"
            class="brand-image img-circle elevation-3" style="opacity:.8">
        <span class="brand-text font-weight-light"><b>Mapterra</b>GO</span>
    </a>



    <!-- Sidebar Menu -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @foreach($tipomapas as $tipomapa)
                    <li class="nav-item">
                        <a href="{{ route('cliente-mapas', [$tipomapa->id, $empresa->id, $predio->id]) }}" class="nav-link">
                            <i class="{{ $tipomapa->icon }}" style="color: green;"></i>
                            <p>{{ $tipomapa->nombre }}</p>
                        </a>
                    </li>
                @endforeach
                <li class="nav-item">
                    <a href="{{route('cliente-bichero', [$tipomapa->id, $empresa->id, $predio->id])}}" class="nav-link">
                        <i class="fas fa-bug" style="color: green;"></i>
                        <p>Bichero</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('cliente-solicitud-estudio-predio',[$tipomapa->id, $empresa->id, $predio->id])}}" class="nav-link">
                        <i class="fas fa-clipboard-check" style="color: green;"></i>
                        <p>Solicitud Estudio</p>  
                    </a>
                </li>

            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->


</aside>
@endsection

@section('content')


@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Éxito!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<style>
    body {
        margin: 0;
        padding: 0;
    }

    #map {
        width: 100%;
        height: 500px;
        position: relative;
    }

    .calculation-box {
        position: absolute;
        top: 20px;
        /* Posición desde la parte superior */
        right: 20px;
        /* Posición desde la derecha */
        background-color: rgba(255, 255, 255, 0.9);
        color: #000;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Arial', sans-serif;
        z-index: 1;
        width: 200px;
    }

    #calculated-area,
    #calculated-hectares {
        font-size: 16px;
        font-weight: bold;
        margin-top: 5px;
    }

    .export-button {
        bottom: 20px;
        /* Posición desde la parte inferior */
        right: 20px;
        /* Posición desde la derecha */
        background-color: #ff9900;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Arial', sans-serif;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1;
    }

    .export-button:hover {
        background-color: #ff9900;
    }
</style>
<div class="p-5">

{{-- 
    <div class="calculation-box">
        <p>Área total:</p>
        <div id="calculated-area">0 m²</div>
        <div id="calculated-hectares">0 ha</div>
    </div> --}}
   
      <div id="map"></div> 
    <div class="container mt-4">
        <div class="card shadow-lg p-4">
             <form class="form" id="form" action="{{route("cliente-solicitud-estudio-store")}}" method="POST" enctype="multipart/form-data">
                @csrf 
                <!-- Descripción y Solución -->
                <div class="form-group mb-4">
                    <label for="descripcion" class="form-label"><b>Descripción</b></label>
                    <textarea class="form-control" name="descripcion" id="descripcion"
                        placeholder="Ingrese la descripción..." required></textarea>
                </div> 
                <input type="hidden" name="empresaId" value="{{$empresa->id}}">
                <input type="hidden" name="predioId" value="{{$predio->id}}">
                <input type="hidden" name="json" id="json">
                <!-- Botón para enviar el formulario -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success px-5 py-2">
                        <i class="bi bi-check-circle"></i> Enviar
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Librerías de Mapbox y Turf.js -->
<link href="https://api.mapbox.com/mapbox-gl-js/v3.8.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.8.0/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-draw@1.0.4/dist/mapbox-gl-draw.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-draw@1.0.4/dist/mapbox-gl-draw.css" />
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

<script>
    // Token de acceso de Mapbox
    mapboxgl.accessToken = '{{ env('KEY_MAPBOX') }}';

    // Inicializar el mapa
    const map = new mapboxgl.Map({
        container: 'map', // ID del contenedor
        style: 'mapbox://styles/mapbox/satellite-v9', // Estilo del mapa
        center: [-63.1800, -17.7845], // Coordenadas iniciales [lng, lat]
        zoom: 12 // Nivel de zoom inicial
    });

    // Agregar controles de dibujo
    const draw = new MapboxDraw({
        displayControlsDefault: true,
        controls: {
            polygon: true, // Activar dibujo de polígonos
            trash: true    // Activar botón para eliminar
        }
    });

    // Agregar control de dibujo al mapa
    map.addControl(draw);

    // Agregar control de pantalla completa al mapa
    map.addControl(new mapboxgl.FullscreenControl());

    // Agregar control para la ubicación actual
    const geolocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true // Usar ubicación precisa
        },
        trackUserLocation: true, // Activar el seguimiento de la ubicación del usuario
        showUserLocation: true // Mostrar el marcador de la ubicación del usuario
    });

    // Agregar el botón de ubicación al mapa
    map.addControl(geolocateControl);

    
 

    // Función para exportar los polígonos a un archivo JSON
    function exportPolygons() {
        const data = draw.getAll(); // Obtener todos los datos dibujados en el mapa
        const json = JSON.stringify(data, null, 2); // Convertir a JSON legible

        // Crear un enlace temporal para descargar el archivo
        const blob = new Blob([json], { type: 'application/json' });
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = 'poligonos.json'; // Nombre del archivo
        a.click();

        // Liberar el objeto URL
        URL.revokeObjectURL(url);
    }

    // Antes de enviar, almacenar los datos del mapa en el campo oculto
    const form = document.getElementById('form');
    form.addEventListener('submit', function (e) {
      
        const data = draw.getAll(); // Obtener todos los datos dibujados en el mapa
        const json = JSON.stringify(data, null, 2); // Convertir a JSON legible
        document.getElementById('json').value = json; // Asignar al campo oculto
    });

</script>
@stop