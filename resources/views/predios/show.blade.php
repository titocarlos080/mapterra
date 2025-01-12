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
        <img src="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" 
             alt="Admin Logo" 
             class="brand-image img-circle elevation-3" 
             style="opacity: .8">
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
    #map { width: 100%; height: 500px; position: relative; margin-bottom: 20px; }
    .calculation-box {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        color: #000;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        font-family: 'Arial', sans-serif;
        z-index: 1;
        width: 200px;
    }
    .form{
        display: flex;
        flex-direction: row;
    }
    .export-button, .download-button {
        bottom: 20px;
        right: 20px;
         color: white;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Arial', sans-serif;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1;
        text-align: center;
    }
    .download-button {
        bottom: 60px; /* Espacio entre los botones */
    }
  
    .icon {
        margin-right: 10px;
    }
</style>
<div class="p-5">
      <!-- Botón de descarga -->
      <div class="form">
        <div class="download-button bg-success">
            <a href="{{ asset('storage/' . $predio->path_kmz) }}" 
               target="_blank" 
               rel="noopener noreferrer" 
               style="text-decoration: none; color: white; display: flex; align-items: center;">
                <i class="fas fa-download icon text-warning"></i> Descargar
            </a>
           </div> 
             <!-- Botón de exportar -->
         <div class="export-button bg-success ml-2" onclick="exportPolygons()">
            <i class="fas fa-file-export icon text-warning"></i> Exportar
        </div>
      </div>
     
    <!-- Contenedor del mapa -->
    <div id="map"></div>

  

   
</div>

<!-- Librerías de Mapbox y Turf.js -->
<link href="https://api.mapbox.com/mapbox-gl-js/v3.8.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.8.0/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-draw@1.0.4/dist/mapbox-gl-draw.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-draw@1.0.4/dist/mapbox-gl-draw.css" />
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

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

    // Cargar y mostrar el archivo JSON
    const jsonUrl = '{{ asset('storage/' . $predio->path_kmz) }}'; // Ruta accesible

    fetch(jsonUrl)
    .then(response => response.json())
    .then(data => {
        map.on('load', () => {
            // Agregar los datos JSON al mapa
            map.addSource('json-data', {
                type: 'geojson',
                data: data
            });

            // Capa de polígonos
            map.addLayer({
                id: 'json-layer',
                type: 'fill',
                source: 'json-data',
                paint: {
                    'fill-color': '#088', // Color del polígono
                    'fill-opacity': 0.5
                }
            });

            // Capa para mostrar los nombres de los polígonos
            map.addLayer({
                id: 'labels-layer',
                type: 'symbol',
                source: 'json-data',
                layout: {
                    'text-field': ['get','lote'], // Muestra la propiedad "nombre"
                    'text-size': 12,
                    'text-offset': [0, 1], // Ajusta la posición del texto
                    'text-anchor': 'top',
                    'text-allow-overlap': true, // Permite que el texto se muestre incluso si hay superposición
                    'icon-image': 'marker-15', // Icono de predio o marcador (puedes usar cualquier icono disponible)
                    'icon-size': 1.5, // Tamaño del icono (ajustable)
                    'icon-allow-overlap': true // Permite la superposición de iconos
                },
                paint: {
                    'text-color': 'orange', // Color del texto
                    'text-halo-color': '#32CD32', // Halo del texto (verde medio)
                    'text-halo-width': 1
                }
            });

            // Ajustar el mapa para mostrar los datos
            const bounds = new mapboxgl.LngLatBounds();
            data.features.forEach(feature => {
                const geometryType = feature.geometry.type;
                const coordinates = feature.geometry.coordinates;

                if (geometryType === "Polygon") {
                    coordinates[0].forEach(coord => bounds.extend(coord));
                } else if (geometryType === "MultiPolygon") {
                    coordinates.forEach(polygon => {
                        polygon[0].forEach(coord => bounds.extend(coord));
                    });
                }
            });

            map.fitBounds(bounds, { padding: 20 });

            // Popup para mostrar propiedades completas al hacer clic
            const popup = new mapboxgl.Popup({
                closeButton: true,
                closeOnClick: true
            });

            map.on('click', 'json-layer', (e) => {
                const properties = e.features[0]?.properties || {}; // Obtiene las propiedades del polígono
                const coordinates = e.lngLat; // Coordenadas del clic

                // Convierte las propiedades en una lista HTML
                const propertiesHTML = Object.entries(properties)
                    .map(([key, value]) => `<strong>${key}:</strong> ${value}`)
                    .join('<br>');

                // Configurar el contenido del popup
                popup.setLngLat(coordinates)
                    .setHTML(`
                        <div style="
                            background-color: #FFF; /* Blanco */
                            color: orange; /* Texto naranja */
                            font-size: 14px;
                            font-family: Arial, sans-serif;
                            padding: 5px;
                            border-radius: 5px;">
                            ${propertiesHTML}
                        </div>
                    `)
                    .addTo(map);
            });
        });
    })
    .catch(error => {
        console.error('Error al cargar el JSON:', error);
    });




   
   
        // Exportar polígonos a JSON
    function exportPolygons() {
        const data = draw.getAll();
        if (data.features.length === 0) {
            alert('No hay polígonos dibujados para exportar.');
            return;
        }
        const blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'polygons.json';
        a.click();
        URL.revokeObjectURL(url);
    }
</script>
@endsection
