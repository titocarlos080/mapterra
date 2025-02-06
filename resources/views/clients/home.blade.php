@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">
<style>
    #map {
        width: 100%;
        height: 500px;
        position: relative;
    }
</style>
@section('title', 'clientes')

@section('content_header')
@stop
@section('sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{asset('vendor/adminlte/dist/img/mapterralogo.webp')}}" alt="Admin Logo"
            class="brand-image img-circle elevation-3" style="opacity:.8">
        <span class="brand-text font-weight-light"><b>Mapterra</b>GO</span>
    </a>



    <!-- Sidebar Menu -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tractor" style="color: green;"></i>
                        <p>
                            PREDIOS
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @foreach($empresa->predios as $predio)    
                            <li class="nav-item">
                                <a href="{{route('cliente-predio', [$empresa->id, $predio->id])}}" class="nav-link">
                                    <i class="fas fa-map-marked-alt nav-icon" style="color: green;"></i>
                                    <p>{{$predio->nombre}}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </li>
                {{-- <li class="nav-item">
                    <a href="{{route('cliente-users')}}" class="nav-link">
                        <i class="fas fa-users nav-icon" style="color: green;"></i>
                        <p>Usuarios</p>
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a href="{{route('cliente-solicitud-estudio')}}" class="nav-link">
                        <i class="fas fa-clipboard-check" style="color: green;"></i>
                        <p>Sol. Estudio</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->


</aside>
@endsection

@php
$user= Auth::user();

@endphp
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
<div class="row m-2">
    <!-- Tarjetas con estadísticas -->
    {{-- <div class="col-xl-4 col-md-6">
        <div class="card shadow mb-4">
            <div class="card-body rounded" style="background: #d4760a">
                <div class="row align-items-center">
                    <div class="col-2">
                        <i class="fas fa-users fa-2x text-success"></i> <!-- Icono representativo de "Clientes" -->
                    </div>
                    <div class="col-10">
                        <h5 class="font-weight-bold">Predios</h5>
                        <p class="h3">{{$empresa->predios->count()}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="col-xl-4 col-md-6">
        <div class="card shadow mb-4">
            <div class="card-body rounded" style="background: #c4b406">
                <div class="row align-items-center">
                    <div class="col-2">
                        <i class="fas fa-users-cog fa-2x text-warning"></i> <!-- Icono representativo de "Grupos" -->
                    </div>
                    <div class="col-10">
                        <h5 class="font-weight-bold">Usuarios</h5>
                        <p class="h3">{{$empresa->users->count()}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="col-xl-4 col-md-6">
        <div class="card shadow mb-4">
            <div class="card-body rounded" style="background: #148519">
                <div class="row align-items-center">
                    <div class="col-2">
                        <i class="fas fa-tasks fa-2x text-primary"></i>
                        <!-- Icono representativo de "Trabajos Solicitados" -->
                    </div>
                    <div class="col-10">
                        <h5 class="font-weight-bold">Trabajos Solicitados</h5>
                        <p class="h3">2</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


</div>

<div class="row m-2">
     
    <div id="box"></div>
    <div id="map"></div>



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
        displayControlsDefault: false,
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
    const jsonUrl = '{{ asset('storage/' . $user->zona_trabajo) }}'; // Ruta accesible

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
                        'fill-opacity': 0.5,
                        'fill-outline-color': '#000' // Color del borde del polígono
                    }
                });

                // Capa para mostrar los nombres de los polígonos
                map.addLayer({
                    id: 'labels-layer',
                    type: 'symbol',
                    source: 'json-data',
                    layout: {
                        'text-field': ['get', 'lote'], // Muestra la propiedad "lote" por defecto
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
            console.error('Error al cargar el JSON:', error);
        });
</script>
 

@stop