@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">

@section('title', $empresa->nombre . '-' . $predio->nombre)

@section('content_header')
    <h1>{{ $predio->nombre }}</h1>
@stop
@section('sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
        <img src="{{asset('vendor/adminlte/dist/img/mapterralogo.webp')}}" 
             alt="Admin Logo" 
             class="brand-image img-circle elevation-3" 
             style="opacity:.8">
        <span class="brand-text font-weight-light"><b>Mapterra</b>GO</span>
    </a>


    <!-- Sidebar Menu -->
 @include('empresa.predios.sidebar')
<!-- /.sidebar -->


</aside>
@endsection

@section('content')
<style>
    body { margin: 0; padding: 0; }
    #map { width: 100%; height: 500px; position: relative; }
    .calculation-box {
        position: absolute;
        top: 20px; /* Posición desde la parte superior */
        right: 20px; /* Posición desde la derecha */
        background-color: rgba(255, 255, 255, 0.9);
        color: #000;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Arial', sans-serif;
        z-index: 1;
        width: 200px;
    }
    #calculated-area, #calculated-hectares {
        font-size: 16px;
        font-weight: bold;
        margin-top: 5px;
    }
    .export-button {
        position: absolute;
        bottom: 20px; /* Posición desde la parte inferior */
        right: 20px; /* Posición desde la derecha */
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Arial', sans-serif;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1;
    }
    .export-button:hover {
        background-color: #0056b3;
    }
</style>

<div class="p-5">
    <!-- Contenedor del mapa -->
    <div class="calculation-box">
        <p>Área total:</p>
        <div id="calculated-area">0 m²</div>
        <div id="calculated-hectares">0 ha</div>
    </div>
    <div id="map"></div>
    <div class="export-button" onclick="exportPolygons()">Exportar a JSON</div>
</div>

<!-- Librerías de Mapbox y Turf.js -->
<link href="https://api.mapbox.com/mapbox-gl-js/v3.8.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.8.0/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-draw@1.0.4/dist/mapbox-gl-draw.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-draw@1.0.4/dist/mapbox-gl-draw.css" />
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

<script>
    // Token de acceso de Mapbox
    mapboxgl.accessToken = 'pk.eyJ1IjoidGl0b2NhcmxvcyIsImEiOiJjbTRicjhsb2kwMzV5Mm1wcmkyY3lzNTAwIn0.E_fPz3LdK1B8F-YHTvbSpA';

    // Inicializar el mapa
    const map = new mapboxgl.Map({
        container: 'map', // ID del contenedor
        style: 'mapbox://styles/mapbox/satellite-v9', // Estilo del mapa
        center: [-63.1800, -17.7845], // Coordenadas iniciales [lng, lat]
        zoom: 12 // Nivel de zoom inicial
    });

    // Agregar controles de dibujo
    const draw = new MapboxDraw({
        displayControlsDefault: false,
        controls: {
            polygon: true, // Activar dibujo de polígonos
            trash: true    // Activar botón para eliminar
        }
    });

    map.addControl(draw);

    // Actualizar el área total
    function updateTotalArea() {
        const data = draw.getAll(); // Obtener todos los polígonos dibujados
        const calculatedArea = document.getElementById('calculated-area');
        const calculatedHectares = document.getElementById('calculated-hectares');
        let totalArea = 0;

        data.features.forEach((feature) => {
            totalArea += turf.area(feature); // Calcular el área de cada polígono
        });

        const roundedArea = (totalArea > 0) ? totalArea.toFixed(2) : 0; // Área en m²
        const roundedHectares = (totalArea > 0) ? (totalArea / 10000).toFixed(2) : 0; // Área en ha

        calculatedArea.textContent = `${roundedArea} m²`;
        calculatedHectares.textContent = `${roundedHectares} ha`;
    }

    // Eventos de dibujo
    map.on('draw.create', updateTotalArea);
    map.on('draw.update', updateTotalArea);
    map.on('draw.delete', updateTotalArea);

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
</script>
@stop
