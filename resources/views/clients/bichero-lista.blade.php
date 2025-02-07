<!-- // Solicitud de estudio -->
@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">

@section('title', $empresa->nombre . ' - Bichero-Lista')

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
                    <a href="{{route('cliente-seguimiento', [$tipomapa->id, $empresa->id, $predio->id])}}" class="nav-link">
                        <i class="fas fa-chart-line" style="color: green;"></i>
                        <p>Seguimiento</p>
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
<div class="row">
    @foreach($bicheros as $bichero)
    <div class="col-md-4 mb-4">
        <div class="card shadow-lg">
            <div class="card-body">
                <h5 class="card-title"> {{ $bichero->lote->nombre }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $bichero->hora }} -  {{ $bichero->fecha }}
                    <br>
                    <a 
                        href="https://www.google.com/maps?q={{ $bichero->latitud }},{{ $bichero->longitud }}" 
                        target="_blank" 
                        class="btn btn-sm btn-link text-primary"
                    >
                        Ver en Google Maps
                    </a>
                </h6>
                
                <p class="card-text"><strong>Solución:</strong> {{ $bichero->solucion }}</p>

                @if(count($bichero->imagenes) > 0)
                <p><strong>Imágenes:</strong></p>
                <ul class="list-unstyled">
                    @foreach($bichero->imagenes as $imagen)
                    <li class="mb-2">
                        <img src="{{ asset('storage/'.$imagen['archivo']) }}" alt="Imagen de {{ $bichero->lote->nombre }}" class="img-fluid rounded" />
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-muted">No hay imágenes disponibles.</p>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center">
    {{ $bicheros->onEachSide(5)->links() }}
</div>



@stop