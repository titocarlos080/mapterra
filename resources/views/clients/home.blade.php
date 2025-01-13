@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">

@section('title', 'clientes' )

@section('content_header')
@stop
@section('sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
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
                            <a href="{{route('cliente-predio',[$empresa->id,$predio->id])}}" class="nav-link">
                                <i class="fas fa-map-marked-alt nav-icon" style="color: green;"></i>
                                <p>{{$predio->nombre}}</p>  
                            </a>
                        </li>
                        @endforeach
                    </ul>
                     
                </li>
                <li class="nav-item">
                    <a href="{{route('cliente-users')}}" class="nav-link">
                        <i class="fas fa-users nav-icon" style="color: green;"></i>
                        <p>Usuarios</p>  
                    </a>
                </li>
              
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
    <div class="col-xl-4 col-md-6">
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
    </div>

    <div class="col-xl-4 col-md-6">
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
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card shadow mb-4"> 
            <div class="card-body rounded" style="background: #148519">
                <div class="row align-items-center">
                    <div class="col-2">
                        <i class="fas fa-tasks fa-2x text-primary"></i> <!-- Icono representativo de "Trabajos Solicitados" -->
                    </div>
                    <div class="col-10">
                        <h5 class="font-weight-bold">Trabajos Solicitados</h5>
                        <p class="h3">20</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 @stop
