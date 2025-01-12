@extends('layouts.app')

@section('title', 'Usuarios')

 
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
    <div class="btn btn-warning ml-auto" data-toggle="modal" data-target="#agregarUsuario">
        <i class="fas fa-plus-circle text-success"></i> Agregar
    </div>
 
    <table class="table table-striped">
        <thead>
            <tr>
                 <th>Nombre</th>
                 <th>Foto</th>
                  <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                 <td>{{ $user->name }}</td>
                 <td>
                    @if ($user->foto_path)
                        <img src="{{ asset('storage/' . $user->foto_path) }}" alt="Foto de {{ $user->name }}" width="50" height="50">
                    @else
                        Sin foto
                    @endif
                </td>
                 <td>{{ $user->empresa->nombre }}</td>
                <td>
                    <a onclick="verEmpresa('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->empresa_id }}','{{ $user->empresa->nombre}}', '{{ $user->rol->nombre}}',  )" 
                       class="btn btn-primary btn-sm" title="Ver">
                        <i class="fas fa-eye"></i>  
                    </a>  
                
                    <a onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->empresa_id }}')" 
                       class="btn btn-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i> 
                    </a>
                    
                    <a onclick="openPermisosModal('{{ $user->id }}')" 
                        class="btn btn-warning btn-sm" title="Permisos">
                         <i class="fas fa-lock"></i> 
                     </a>
                     
                    <form action="{{ route('admin-usuarios-delete', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este Usuario?');">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>  
                        </button>
                    </form>
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
<!-- Modal para Permisos -->
<div class="modal fade" id="permisosModal" tabindex="-1" aria-labelledby="permisosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="permisosForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permisosModalLabel">Asignar Permisos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="permisosSelect">Permisos</label>
                        <select class="form-control" id="permisosSelect" name="permisos[]" multiple>
                            {{-- @foreach($permisos as $permiso)
                                <option value="{{ $permiso->id }}">{{ $permiso->nombre }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Modal para Ver Usuario -->
<div class="modal fade" id="verUsuarioModal" tabindex="-1" aria-labelledby="verUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verUsuarioLabel">Detalles del Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Nombre:</strong> <span id="verNombre"></span></p>
                <p><strong>Email:</strong> <span id="verEmail"></span></p>
                <p><strong>Empresa:</strong> <span id="verEmpresa"></span></p>
                <p><strong>Rol:</strong> <span id="verRol"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editarUsuarioForm" method="POST" action="">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarUsuarioLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editarNombre">Nombre</label>
                        <input type="text" class="form-control" id="editarNombre" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editarEmail">Email</label>
                        <input type="email" class="form-control" id="editarEmail" name="email" required>
                    </div>
                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
    <script>
 function openPermisosModal(userId) {
        // Establecer la acción del formulario
        document.getElementById('permisosForm').action = '/usuarios/' + userId + '/permisos';
        
        // Limpiar la selección previa de permisos
        $('#permisosSelect').val([]).trigger('change');
        
        // Cargar los permisos asignados al usuario (puedes hacerlo a través de una llamada AJAX)
        $.get('/usuarios/' + userId + '/permisos', function(data) {
            $('#permisosSelect').val(data).trigger('change');
        });

        // Mostrar el modal
        $('#permisosModal').modal('show');
    }




        console.log('Vista cargada correctamente');
    
    function verEmpresa(id, nombre, email,empresa_id, empresa,rol) {
        document.getElementById('verNombre').innerText = nombre;
        document.getElementById('verEmail').innerText = email;
        document.getElementById('verEmpresa').innerText = empresa;
        document.getElementById('verRol').innerText = rol;
        $('#verUsuarioModal').modal('show');
    }

    function openEditModal(id, nombre, email, empresa) {
        document.getElementById('editarNombre').value = nombre;
        document.getElementById('editarEmail').value = email;
 
        // Cambiar la acción del formulario para enviar la solicitud a la ruta correcta
        document.getElementById('editarUsuarioForm').action = '/usuarios/' + id;
        $('#editarUsuarioModal').modal('show');
    }
</script>
@endsection
