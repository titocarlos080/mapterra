@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">
<!-- Librerías de Mapbox y Turf.js -->
<link href="https://api.mapbox.com/mapbox-gl-js/v3.8.0/mapbox-gl.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-draw@1.0.4/dist/mapbox-gl-draw.css" />

@section('title', 'Predios')

<style>
    body {
        margin: 0;
        padding: 0;
    }

    #map {
        width: 100%;
        height: 500px;
        margin-bottom: 20px;
    }
    
    .icon {
        margin-right: 10px;
    }
</style>

@section('content_header')
@stop

@section('sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/cliente" class="brand-link">
        <img src="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" alt="Admin Logo"
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
                    <a href="{{route('cliente-solicitud-estudio-predio', [$tipomapa->id, $empresa->id, $predio->id])}}"
                        class="nav-link">
                        <i class="fas fa-clipboard-check" style="color: green;"></i>
                        <p>Solicitud Estudio</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
@endsection

@section('content')
<div>
    <!-- Alertas de éxito o error -->
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

    <!-- Tarjeta principal -->
    <div class="card">
        <div class="card-body d-flex flex-wrap">
            <!-- Tipos de mapas -->
            @foreach($tipomapas as $tipo)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">

                            <a href="{{ route('cliente-mapas', [$tipo->id, $empresa->id, $predio->id]) }}" class="nav-link">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="{{ $tipo->icon }} fa-2x mr-3" style="color: #008000;"></i>
                                    <h5 class="card-title mb-0">{{ $tipo->nombre }}</h5>

                                </div>
                            </a>
                            <p>{{$tipo->descripcion}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1 class="card-title mb-4">Predio: {{ $predio->nombre }}</h1>
        </div>
        <div class="card-body">
            <!-- Información del predio -->
            <div class="card">
                <div class="card-body">
                    <div class="p-5">
                        <!-- Botón de descarga -->
                        <div class="mb-4">
                            <a href="{{ asset('storage/' . $predio->path_kmz) }}" target="_blank"
                                rel="noopener noreferrer" class="btn btn-success d-flex align-items-center text-white">
                                <i class="fas fa-download mr-2 text-warning"></i> Descargar
                            </a>
                        </div>

                        <!-- Contenedor del mapa -->
                        <div id="box" ></div>
                        <div id="map" style="height: 400px; border: 1px solid #ccc;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v3.8.0/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-draw@1.0.4/dist/mapbox-gl-draw.js"></script>
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    // Token de acceso de Mapbox
    mapboxgl.accessToken = '{{ env('KEY_MAPBOX') }}';

    // Inicializar el mapa
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/satellite-v9',
        center: [-63.1800, -17.7845],
        zoom: 12
    });

    // Agregar controles de dibujo
    const draw = new MapboxDraw({
        displayControlsDefault: true,
        controls: {
            polygon: true,
            trash: true
        }
    });
    map.addControl(draw);

    // Agregar control de pantalla completa
    map.addControl(new mapboxgl.FullscreenControl());

    //Cargar y mostrar el archivo JSON
    const jsonUrl = '{{ asset('storage/' . $predio->path_kmz) }}';
    fetch(jsonUrl)
        .then(response => response.json())
        .then(data => {
            map.on('load', () => {
                map.addSource('json-data', {
                    type: 'geojson',
                    data: data
                });
                map.addLayer({
                    id: 'json-layer',
                    type: 'fill',
                    source: 'json-data',
                    paint: {
                        'fill-color': '#088',
                        'fill-opacity': 0.5
                    }
                });
                map.addLayer({
                    id: 'json-outline',
                    type: 'line',
                    source: 'json-data',
                    paint: {
                        'line-color': '#000',
                        'line-width': 2
                    }
                });
                map.addLayer({
                    id: 'labels-layer',
                    type: 'symbol',
                    source: 'json-data',
                    layout: {
                        'text-field': ['get', 'Parcela'], // Muestra la propiedad "nombre"
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

                // Obtener el límite (bounding box) del GeoJSON y ajustar el mapa
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


                // Mover el mapa al área de las geometrías
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
                 
                            // Crear un selector para elegir la propiedad a mostrar
                const propertySelect = document.createElement('select');
                propertySelect.id = 'property-select';
                const properties = Object.keys(data.features[0]?.properties || {});
                properties.forEach(property => {
                    const option = document.createElement('option');
                    option.value = property;
                    option.innerText = property;
                    propertySelect.appendChild(option);
                });

                // Agregar el selector al DOM
               const box  = document.getElementById("box");
               box.appendChild(propertySelect);

                // Actualizar la propiedad mostrada en los polígonos cuando se cambia el selector
                propertySelect.addEventListener('change', (event) => {
                    const selectedProperty = event.target.value;
                    map.setLayoutProperty('labels-layer', 'text-field', ['get', selectedProperty]);
                });
            });


        })
        .catch(error => {
            console.error('Error al cargar el archivo JSON:', error);
            alert('Hubo un problema al cargar los datos del mapa.');
        });
</script>
@endsection