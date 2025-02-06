@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">

@section('title', $predio->nombre . ' - ' . $tipoMapa->nombre)

@section('content_header')
@stop
@section('sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/cliente" class="brand-link">
        <img src="{{asset('vendor/adminlte/dist/img/mapterralogo.webp')}}" 
             alt="Admin Logo" 
             class="brand-image img-circle elevation-3" 
             style="opacity:.8">
        <span class="brand-text font-weight-light"><b>Mapterra</b>GO</span>
    </a>



    <!-- Sidebar Menu -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
               
                @foreach($tipomapas as $tipomapa)
                <li class="nav-item">
                    <a href="{{ route('cliente-mapas', [$tipomapa->id, $empresa->id, $predio->id]) }}"
                        class="nav-link">
                        <i class="{{ $tipomapa->icon }}" style="color: green;"></i>
                        <p>{{ $tipomapa->nombre }}</p>
                    </a>
                </li>
                @endforeach
                <li class="nav-item">
                    <a href="{{route('cliente-bichero',[$tipomapa->id, $empresa->id, $predio->id])}}" class="nav-link">
                        <i class="fas fa-bug" style="color: green;"></i>
                        <p>Bichero</p>  
                    </a>
                </li>
                 
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
    
 
</aside>
@endsection

@section('content')
 
<div class="card">







    <div class="card-header d-flex justify-content-between">
        <div>
            <h1 class="card-title">{{$tipoMapa->nombre}} :{{ $predio->nombre }}</h1>
        </div>
     
        
    </div>
    
    <div class="card-body">
        <div class="row">
            @foreach($mapas as $mapa)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">{{ $mapa->titulo }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Vista previa del archivo PDF -->
                        <div class="pdf-preview mb-3">
                            <embed src="{{ asset('storage/' . $mapa->path_file) }}" type="application/pdf" width="100%" height="200">
                        </div>
                        <!-- Fecha -->
                        <p><strong>Fecha:</strong> {{ $mapa->fecha }}</p>
                        <!-- Descripción -->
                        <p><strong>Descripción:</strong> {{ $mapa->descripcion }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <!-- Botones de acción -->
                       
                            
                            <a href="{{ asset('storage/' . $mapa->path_file) }}" 
                               download class="btn btn-success btn-sm" data-toggle="tooltip" title="Descargar">
                                <i class="fas fa-download"></i>
                            </a>
                        
                       
                          
                       
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    
        <!-- Paginación -->
        <div class="mt-3">
            {{ $mapas->links() }}
        </div>
    </div>
</div>
 @stop
