@extends('layouts.app')

@section('title', 'Tipo Mapas')

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
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Menu Items -->
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

<div class="container mt-4">
    <div class="card-header d-flex justify-content-between">
        <div class="btn btn-warning ml-auto" data-toggle="modal" data-target="#crearTipoMapaModal">
            <i class="fas fa-plus-circle text-success"></i> Agregar
        </div>
    </div>

    <!-- Grid de tarjetas -->
    <div class="row">
        @foreach($tipoMapas as $tipo)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Icono y Nombre del tipo de mapa -->
                    <div class="d-flex align-items-center">
                        <i class="{{ $tipo->icon }} fa-2x mr-3" style="color: #008000;"></i>
                        <h5 class="card-title mb-0">{{ $tipo->nombre }}</h5>
                    </div>
                    <!-- Descripción -->
                    <p class="mt-3">{{ $tipo->descripcion }}</p>
                </div>
                <!-- Footer de la tarjeta -->
                <div class="card-footer text-right">
                    <!-- Botón Editar con icono y tooltip -->
                    <a href="#" 
                       class="btn btn-sm btn-primary" 
                       data-toggle="modal" 
                       data-target="#editModal-{{ $tipo->id }}" 
                       data-toggle="tooltip" 
                       title="Editar este tipo de mapa">
                        <i class="fas fa-edit"></i>  
                    </a>
                
                    <!-- Botón Eliminar con icono y tooltip -->
                    <form action="{{ route('admin-tiposmapas-delete', $tipo->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" 
                                class="btn btn-sm btn-danger" 
                                onclick="return confirm('¿Estás seguro de eliminar este tipo de mapa?')" 
                                data-toggle="tooltip" 
                                title="Eliminar este tipo de mapa">
                            <i class="fas fa-trash-alt"></i>  
                        </button>
                    </form>
                </div>
                
            </div>
        </div>

        <!-- Modal de Editar -->
        <div class="modal fade" id="editModal-{{ $tipo->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $tipo->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel-{{ $tipo->id }}">Editar Tipo de Mapa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin-tiposmapas-update', $tipo->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" value="{{$tipo->id}}">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $tipo->nombre }}" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3" required>{{ $tipo->descripcion }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="icon">Ícono</label>
                                <input type="text" name="icon" class="form-control" value="{{ $tipo->icon }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal para Crear -->
<div class="modal fade" id="crearTipoMapaModal" tabindex="-1" aria-labelledby="crearTipoMapaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearTipoMapaModalLabel">Agregar Nuevo Tipo de Mapa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin-tiposmapas-store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="icon">Ícono</label>
                        <input type="text" name="icon" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log('Vista cargada correctamente');
</script>
@endsection
