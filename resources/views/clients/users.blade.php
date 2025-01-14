  <!-- // Solicitud de estudio -->
  @extends('layouts.app')

  <!-- Agrega el ícono en la pestaña -->
  <link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">
  
  @section('title', $empresa->nombre . ' - Usuarios'  )
  
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
                    <a href="#" class="nav-link">
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
  <div class="container">
    <div class="card-header d-flex justify-content-between">
        
        <div class="btn btn-warning ml-auto" data-toggle="modal" data-target="#agregarUsuario">
            <i class="fas fa-plus-circle text-success"></i> Agregar
        </div>
        
    </div>
    <p>Total de usuarios: {{ $users->total() }}</p>

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
                        
                    {{-- <a onclick="openPermisosModal('{{ $user->id }}')" 
                        class="btn btn-warning btn-sm" title="Permisos">
                         <i class="fas fa-lock"></i> 
                     </a> --}}
                     
                   
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

<!-- Modal para Permisos -->
<div class="modal fade" id="permisosModal" tabindex="-1" aria-labelledby="permisosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="permisosForm" method="POST" action="">
            @csrf
            @method('POST')
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
 <!-- Modal para Agregar Usuarios -->
<div class="modal fade" id="agregarUsuario" tabindex="-1" aria-labelledby="agregarUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="agregarUsuariosForm" method="POST" action="{{ route('cliente-usuarios-store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarUsuarioModalLabel">Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="empresaId" id="empresaId" value="{{ $empresa->id }}">
                         <div class="form-check">
                            <input class="form-check-input" type="radio" name="rolId" id="permisoAdmin" value="2" required>
                            <label class="form-check-label" for="permisoAdmin">
                                Administrador
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rolId" id="permisoTecnico" value="3">
                            <label class="form-check-label" for="permisoTecnico">
                                Técnico
                            </label>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="nombreUsuario">Nombre</label>
                        <input type="text" id="nombreUsuario" name="nombre" class="form-control" placeholder="Ingrese el nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="emailUsuario">Correo Electrónico</label>
                        <input type="email" id="emailUsuario" name="email" class="form-control" placeholder="Ingrese el correo electrónico" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordUsuario">Contraseña</label>
                        <input type="password" id="passwordUsuario" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar  </button>
                </div>
            </div>
        </form>
    </div>
</div>


@stop
@section('scripts')
<script>

function verEmpresa(id, nombre, email,empresa_id, empresa,rol) {
        document.getElementById('verNombre').innerText = nombre;
        document.getElementById('verEmail').innerText = email;
        document.getElementById('verEmpresa').innerText = empresa;
        document.getElementById('verRol').innerText = rol;
        $('#verUsuarioModal').modal('show');
    }
    
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
 
</script>
@endsection
