@extends('layouts.app')

@section('title', 'Bitacora')

 
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
<div class="row m-2">
    <h3>Bitácoras</h3>

    <!-- Responsive Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario ID</th>
                    <th>Acción</th>
                    <th>Tabla Afectada</th>
                    <th>Descripción</th>
                    <th>Empresa ID</th>
                    <th>Fecha</th> <!-- Add any additional columns if needed -->
                </tr>
            </thead>
            <tbody>
                @foreach($bitacoras as $bitacora)
                <tr>
                    <td>{{ $bitacora->id }}</td>
                    <td>{{ $bitacora->usuario_id }}</td>
                    <td>{{ $bitacora->accion }}</td>
                    <td>{{ $bitacora->tabla_afectada }}</td>
                    <td>{{ $bitacora->descripcion }}</td>
                    <td>{{ $bitacora->empresa_id }}</td>
                    <td>{{ $bitacora->created_at->format('Y-m-d H:i:s') }}</td> <!-- Assuming created_at field exists -->
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center ">
            {{ $bitacoras->onEachSide(5)->links() }}
        </div>
    </div>

    
</div>
@endsection

@section('scripts')
<script>
    console.log('Vista cargada correctamente');
</script>
@endsection