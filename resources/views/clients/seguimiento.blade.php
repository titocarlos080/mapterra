@extends('layouts.app')
<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">
@section('title', $empresa->nombre . ' - Bichero')
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
                
                {{-- Aumentar otro similar como bicheros --}}
                
                <li class="nav-item">
                    <a href="{{route('cliente-seguimiento', [$tipomapa->id, $empresa->id, $predio->id])}}" class="nav-link">
                        <i class="fas fa-chart-line" style="color: green;"></i>
                        <p>Seguimiento</p>
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

<div class="p-5">
    <div>
        <a href="{{route('cliente-bichero-lista', [$tipomapa->id, $empresa->id, $predio->id])}}" class="nav-link">
            <i class="fas fa-bug" style="color: green;"></i>
            <p>Lista de Seguimiento</p>
        </a>
    </div>    
    <div class="container mt-4">
        <div class="card shadow-lg p-4">
            <h4 class="text-center mb-4"><b>Formulario de Registro de Seguimiento</b></h4>
            <form class="form" id="form" action="/cliente/seguimiento/store" method="POST" enctype="multipart/form-data"> 
                @csrf
                <!-- Puntos de Referencia -->
                <div class="form-group mb-4">
                    <h5><b>Puntos de Referencia</b></h5>

                    <!-- Botón para obtener la ubicación -->
                    <div class="row">
                        <button type="button" class="btn btn-primary" id="getLocationBtn">Obtener Punto</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="latitud" class="form-label">Latitud</label>
                            <input type="number" class="form-control" name="latitud" id="latitud"
                                placeholder="Ingrese la latitud" step="any" required>
                        </div>
                        <div class="col-md-6">
                            <label for="longitud" class="form-label">Longitud</label>
                            <input type="number" class="form-control" name="longitud" id="longitud"
                                placeholder="Ingrese la longitud" step="any" required>
                        </div>
                    </div>

                </div>

                <!-- Tipo de Bichero -->
                <div class="form-group mb-4">
                    <label for="tipo-bichero" class="form-label"><b>Seleccione el Tipo de Bichero</b></label>
                    <select name="tipo-bichero" id="tipo-bichero" class="form-select" required>
                        <option value="" selected>---- Seleccionar ----</option>
                        <option value="1">Plagas</option>
                        <option value="2">Anomalías</option>
                    </select>
                </div>

                <!-- Seleccionar Lote -->
                <div class="form-group mb-4">
                    <label for="loteId" class="form-label"><b>Seleccione el Lote</b></label>
                    <select name="loteId" id="loteId" class="form-select" required>
                        <option value="0" disabled selected>---- Seleccionar ----</option>
                        @foreach ($predio->lotes as $lote)
                            <option value="{{ $lote->id }}">{{ $lote->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Descripción y Solución -->
                <div class="form-group mb-4">
                    <label for="descripcion" class="form-label"><b>Descripción</b></label>
                    <textarea class="form-control" name="descripcion" id="descripcion"
                        placeholder="Ingrese la descripción..." required></textarea>
                </div>

                {{-- <div class="form-group mb-4">
                    <label for="solucion" class="form-label"><b>Solución</b></label>
                    <textarea class="form-control" name="solucion" id="solucion" placeholder="Ingrese la solución..."
                        required></textarea>
                </div> --}}

                <!-- Seleccionar Foto -->
                <div class="form-group mb-4">
                    <label for="photo" class="form-label"><b>Seleccione una Foto o Tome una Fotografía</b></label>
                    <input type="file" class="form-control" name="photos[]" id="photo" accept="image/*"
                       required>
                </div>

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
    function getLocation() {
        // Cambiar el botón a "Cargando..."
        const btn = document.getElementById('getLocationBtn');
        btn.innerHTML = 'Cargando...';
        btn.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                // Obtener latitud y longitud
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                // Llenar los campos de latitud y longitud
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lon;

                // Cambiar el botón a "Ubicación obtenida"
                btn.innerHTML = 'Ubicación Obtenida';
                setTimeout(function() {
                    btn.innerHTML = 'Obtener Punto';
                    btn.disabled = false;
                }, 2000);
            }, function (error) {
                // Manejo de errores específicos de geolocalización
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        alert("El usuario denegó la solicitud de geolocalización.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("La ubicación no está disponible.");
                        break;
                    case error.TIMEOUT:
                        alert("La solicitud de geolocalización expiró.");
                        break;
                    default:
                        alert("Ocurrió un error desconocido.");
                        break;
                }
                btn.innerHTML = 'Obtener Punto';
                btn.disabled = false;
            });
        } else {
            alert("La geolocalización no está soportada por tu navegador.");
            btn.innerHTML = 'Obtener Punto';
            btn.disabled = false;
        }
    }

    // Asignar la función al botón
    document.getElementById('getLocationBtn').addEventListener('click', getLocation);
});

</script>
@stop
