@extends('layouts.app')

@section('title', 'Permisos')

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
<div class="container mt-4">
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nro</th>
                <th>Accion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permisos as $index => $permiso)
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $permiso->accion}}</td>
                <td>
                    <!-- Botón Ver con tooltip -->
                    <button class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Ver Permiso" onclick="openModal('verModal{{ $permiso->id }}')">
                        <i class="fas fa-eye"></i>
                    </button>

                    <!-- Botón Ver Roles Asociados con tooltip -->
                    <button class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Ver Roles Asociados" onclick="openModal('rolesModal{{ $permiso->id }}')">
                        <i class="fas fa-users"></i>
                    </button>
                </td>
            </tr>

            <!-- Modal Ver -->
            <div class="modal fade" id="verModal{{ $permiso->id }}" tabindex="-1" role="dialog" aria-labelledby="verModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="verModalLabel">Ver Permiso</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nombre:</strong> {{ $permiso->accion }}</p>
                            <!-- Agregar más detalles del permiso si es necesario -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Roles Asociados -->
            <div class="modal fade" id="rolesModal{{ $permiso->id }}" tabindex="-1" role="dialog" aria-labelledby="rolesModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rolesModalLabel">Roles Asociados a Permiso</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul>
                                @foreach($permiso->roles as $rol)
                                    <li>{{ $rol->nombre }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
     <!-- Paginación -->
     <div class="d-flex justify-content-center">
        {{ $permisos->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Función para abrir cualquier modal
    function openModal(modalId) {
        $('#' + modalId).modal('show');
    }

    $(function () {
        // Habilitar tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
