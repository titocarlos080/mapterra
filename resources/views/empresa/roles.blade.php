@extends('layouts.app')

@section('title', 'Roles')
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

<div class="container">
    <!-- Botón para abrir el modal de crear -->
    <div class="card-header d-flex justify-content-between">
       
        <div class="btn btn-warning ml-auto" data-toggle="modal" data-target="#crearRolModal">
            <i class="fas fa-plus-circle text-success"></i> Agregar
        </div>
    </div>

    <div class="row">
        @foreach($roles as $role)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-id-badge mr-2" style="color: #008000;"></i>{{ $role->nombre }}
                    </h5>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <!-- Botón de Editar con icono y tooltip -->
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editarRolModal" 
                            onclick="editarRol({{ $role->id }}, '{{ $role->nombre }}')" 
                            data-toggle="tooltip" data-placement="top" title="Editar Rol">
                        <i class="fas fa-edit"></i>  
                    </button>
                 
                    <a onclick="openPermisosModal('{{ $role->id }}')" 
                       class="btn btn-warning btn-sm" title="Permisos">
                        <i class="fas fa-lock"></i> 
                    </a>
    
                    <!-- Botón de Eliminar con icono y tooltip -->
                    <form action="{{ route('admin-roles-delete', $role->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('¿Estás seguro de eliminar este rol?')" 
                                data-toggle="tooltip" data-placement="top" title="Eliminar Rol">
                            <i class="fas fa-trash"></i>  
                        </button>
                    </form>
                </div>
            </div>
        </div>
    
        <!-- Modal para Permisos (con ID único) -->
        <div class="modal fade" id="permisosModal{{ $role->id }}" tabindex="-1" aria-labelledby="permisosModalLabel{{ $role->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div id="permisosForm{{ $role->id }}" method="POST" action="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="permisosModalLabel{{ $role->id }}">Asignar Permisos - {{ $role->nombre }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="permisosSelectAsignados">Permisos Asignados</label>
                                <div id="permisosSelectAsignados{{ $role->id }}">
                                    @foreach($role->permisos as $permisoAsignado)
                                    <div>
                                        <form class="permisos-form" data-permiso-id="{{ $permisoAsignado->id }}" method="POST" action="{{ route('admin-permisos-desasignar', ['rolId' => $role->id,'permisoId'=>$permisoAsignado->id]) }}">
                                            @csrf
                                            <!-- Asegúrate de asignar un id único al input -->
                                            <input type="checkbox" name="permisos_asignado" value="{{ $permisoAsignado->id }}" checked class="permiso-checkbox asignado-checkbox" id="permiso_{{ $permisoAsignado->id }}">
                                            <label for="permiso_{{ $permisoAsignado->id }}">{{ $permisoAsignado->accion }}</label>
                                        </form>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <script>
                                  document.querySelectorAll('.asignado-checkbox').forEach(function(checkbox) {
                                        checkbox.addEventListener('change', function() {
                                            const permisoId = this.value;  // Obtener el ID del permiso
                                            const permisoAccion = this.nextElementSibling.textContent.trim();  // Obtener la acción del permiso

                                            // Identificar el formulario relacionado con el checkbox
                                            const form = this.closest('form');

                                             

                                            // Enviar el formulario automáticamente
                                            form.submit();
                                        });
                                    });

                            </script>
                            
                            
                            
                        
                            <div class="form-group">
                                <label for="permisosSelectNoAsignados">Permisos No Asignados</label>
                                <div id="permisosSelectNoAsignados{{ $role->id }}">
                                    @foreach($permisos as $permiso)
                                        @if(!$role->permisos->contains('id', $permiso->id))
                                            <div>
                                                <form class="permisos-form" data-permiso-id="{{ $permiso->id }}" method="POST" action="{{ route('admin-permisos-asignar', ['rolId' => $role->id]) }}">
                                                    @csrf
                                                    <input type="checkbox" name="permisos_no_asignado" value="{{ $permiso->id }}" class="permiso-checkbox no-asignado-checkbox">
                                                    <label>{{ $permiso->accion }}</label>
                                                </form>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        @endforeach
    </div>
    
</div>

<!-- Modal Crear Rol -->
<div class="modal fade" id="crearRolModal" tabindex="-1" role="dialog" aria-labelledby="crearRolModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearRolModalLabel">Crear Nuevo Rol</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin-roles-store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nombre">Nombre del Rol</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Rol -->
<div class="modal fade" id="editarRolModal" tabindex="-1" role="dialog" aria-labelledby="editarRolModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarRolModalLabel">Editar Rol</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="{{ route('admin-roles-update') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <input type="hidden" name="rol_id" id="editRolId">
                        <label for="nombre">Nombre del Rol</label>
                        <input type="text" name="nombre" id="editNombre" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>





@endsection

@section('scripts')
<script>




 function openPermisosModal(roleId) {
        // Mostrar el modal correspondiente con el ID único
        $('#permisosModal' + roleId).modal('show');
    }

    

    // Añadir un evento de escucha para los cambios en los checkboxes de "permisos no asignados"
    document.querySelectorAll('.no-asignado-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var form = this.closest('form');
            if (this.checked) { // Si el checkbox se selecciona
                form.submit(); // Enviar formulario para asignar el permiso
            }
        });
    });


    function editarRol(id, nombre) {
        document.getElementById('editRolId').value = id;
        document.getElementById('editNombre').value = nombre;
    }
</script>
@endsection
