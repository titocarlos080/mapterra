@extends('layouts.app')

@section('title', 'Bicheros')



@section('sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
        <img src="{{asset('vendor/adminlte/dist/img/mapterralogo.webp')}}" alt="Admin Logo"
            class="brand-image img-circle elevation-3" style="opacity:.8">
        <span class="brand-text font-weight-light"><b>Mapterra</b>GO</span>
    </a>

    <!-- Sidebar Menu -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{route('admin-empresas')}}" class="nav-link">
                        <i class="fas fa-list nav-icon" style="color: #008000;"></i>
                        <p>Empresas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('solicitud-estudio')}}" class="nav-link">
                        <i class="fas fa-file-alt nav-icon" style="color: #008000;"></i>
                        <p>Sol. estudios</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin-usuarios')}}" class="nav-link">
                        <i class="fas fa-users nav-icon" style="color: #008000;"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin-roles')}}" class="nav-link">
                        <i class="fas fa-user-shield nav-icon" style="color: #008000;"></i>
                        <p>Roles</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin-permisos') }}" class="nav-link">
                        <i class="fas fa-shield-alt nav-icon" style="color: #008000;"></i>
                        <p>Permisos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('admin-tiposmapas')}}" class="nav-link">
                        <i class="fas fa-map" style="color: #008000;"></i>
                        <p>Tipo Mapas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('empresa-bichero')}}" class="nav-link">
                        <i class="fas fa-bug" style="color: green;"></i>
                        <p>Bichero</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin-bitacora') }}" class="nav-link">
                        <i class="fas fa-book nav-icon" style="color: #008000;"></i>
                        <p>Bitácora</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>
@endsection



@section('content')
<div class="p-5">
    <div class="col">
        <div class="row">
            {{-- Itera sobre cada trabajo y crea una tarjeta --}}
            @foreach($bicheros as $bichero)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm @if($bichero->tipo_bichero_id == 1) bg-danger @else bg-info @endif">
                        <div class="card-body text-white">
                            <h5 class="card-title">
                                <i
                                    class="fas @if($bichero->tipo_bichero_id == 1) fa-bug @else fa-exclamation-triangle @endif"></i>
                                {{ $bichero->descripcion }}
                            </h5>
                            <p class="card-text">
                                <strong>Solución:</strong> {{ $bichero->solucion }}<br>
                                <strong>Tipo:</strong> {{ $bichero->tipo_bichero_id == 1 ? 'Plaga' : 'Anomalías' }}<br>
                                <strong>Latitud:</strong> {{ $bichero->latitud }}<br>
                                <strong>Longitud:</strong> {{ $bichero->longitud }}<br>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="https://www.google.com/maps?q={{ $bichero->latitud }},{{ $bichero->longitud }}"
                                    target="_blank" class="btn btn-light btn-sm">
                                    <i class="fas fa-map-marker-alt"></i> Ver en mapa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
        <div class="d-flex justify-content-center ">
            {{ $bicheros->onEachSide(5)->links() }}
        </div>
    </div>
</div>

<script>
    /**
     * Descarga el contenido JSON como un archivo GeoJSON
     * @param {Object} data - El contenido del campo json del trabajo
     * @param {string} filename - El nombre del archivo a descargar
     */
    function descargarGeoJSON(data, filename) {



        // Convertir el objeto a una cadena JSON
        const jsonString = JSON.stringify(data, null, 2);

        // Crear un blob y enlace para descargar el archivo
        const blob = new Blob([jsonString], { type: "application/json" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `${filename}.geojson`;

        // Disparar la descarga
        link.click();
        URL.revokeObjectURL(link.href);
    }
</script>
@stop