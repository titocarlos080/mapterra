@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">
  <!-- CSS -->
  <style>
    .progress {
        width: 100%;
        height: 20px;
        background-color: #f3f3f3;
        border-radius: 5px;
    }

    .progress-bar {
        height: 100%;
        text-align: center;
        line-height: 20px;
        color: white;
        border-radius: 5px;
    }

    body { margin: 0; padding: 0; }
                   #map { width: 100%; height: 500px; position: relative; margin-bottom: 20px; }
                   .calculation-box {
                       position: absolute;
                       top: 20px;
                       right: 20px;
                       background-color: rgba(255, 255, 255, 0.9);
                       color: #000;
                       padding: 15px;
                       border-radius: 5px;
                       box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                       font-family: 'Arial', sans-serif;
                       z-index: 1;
                       width: 200px;
                   }
                   .form{
                       display: flex;
                       flex-direction: row;
                   }
                   .export-button, .download-button {
                       bottom: 20px;
                       right: 20px;
                        color: white;
                       padding: 10px 20px;
                       border-radius: 5px;
                       cursor: pointer;
                       font-family: 'Arial', sans-serif;
                       box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                       z-index: 1;
                       text-align: center;
                   }
                   .download-button {
                       bottom: 60px; /* Espacio entre los botones */
                   }
                 
                   .icon {
                       margin-right: 10px;
                   }
</style>
@section('title', $predio->nombre . ' - lotes')

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
                
                      @foreach($tipomapas as $tipolote)
                        <li class="nav-item">
                            <a href="{{ route('admin-mapas',[$tipolote->id,$empresa->id,$predio->id]) }}" class="nav-link">
                                <i class="{{$tipolote->icon}}" style="color: green;"></i>
                                <p>{{$tipolote->nombre}}</p>
                            </a>
                        </li>
                        @endforeach  
                        <li class="nav-item">
                            <a href="{{ route('admin-lotes',[$empresa->id,$predio->id]) }}" class="nav-link">
                                <i class="fas fa-map-marked-alt" style="color: green;"></i>
                                   <p>Lotes</p>
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
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="btn btn-warning ml-auto" data-toggle="modal" data-target="#importarExcelModal">
                <i class="fas fa-file-upload text-success"></i> Importar Excel
            </div>
            
            
            <div class="btn btn-warning ml-auto" data-toggle="modal" data-target="#agregarLoteModal">
                <i class="fas fa-plus-circle text-success"></i> Agregar
            </div>
            
        </div>
        
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Hectáreas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lotes as $lote)
                    <tr>
                        <td>{{ $lote->codigo }}</td>
                        <td>{{ $lote->nombre }}</td>
                        <td>{{ $lote->hectareas }}</td>
                        <td>
                            <!-- Botones de acción --> 
                            <a href="#" class="btn btn-warning btn-sm" 
                            onclick="abrirModalEditarLote('{{ $lote->id }}', '{{ $lote->codigo }}', '{{ $lote->nombre }}', '{{ $lote->hectareas }}')"
                            title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>


                         
                         
                             
                            <form action="{{ route('admin-lotes-delete', $lote->id) }}" 
                                  method="POST" style="display:inline;" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este lote?');">
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
                <!-- Pagination Links -->
        <div class="d-flex justify-content-center ">
            {{ $lotes->onEachSide(5)->links() }}
        </div>
        </div>
        
        
           
        
    </div>

<!-- Modal para agregar una lote -->
<div class="modal fade" id="agregarLoteModal" tabindex="-1" role="dialog" aria-labelledby="agregarLoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarLoteModalLabel">Agregar Lote <i class="fas fa-map-marked nav-icon" style="color: green;"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin-lotes-store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="modal-body">
                    <!-- Hidden Inputs -->
                    <input type="hidden" name="empresa_id" value="{{$empresa->id}}">
                    <input type="hidden" name="predio_id" value="{{$predio->id}}">

                   

                    <div class="form-group">
                        <label for="codigo">Código:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="hectareas">Hectáreas:</label>
                        <input type="number" name="hectareas" id="hectareas" class="form-control" step="0.01" min="0" max="9999999" placeholder="Introduce las hectáreas" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
 <!-- Modal para Editar Lote -->
<div class="modal fade" id="editarLoteModal" tabindex="-1" role="dialog" aria-labelledby="editarLoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarLoteModalLabel">Editar Lote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editarLoteForm" action="{{ route('admin-lotes-update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="lote_id" id="edit_lote_id">
                    <input type="hidden" name="predio_id" id="predio_id" value="{{$predio->id}}">

                    <div class="form-group">
                        <label for="edit_codigo">Código:</label>
                        <input type="text" name="codigo" id="edit_codigo" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_nombre">Nombre:</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_hectareas">Hectáreas:</label>
                        <input type="number" name="hectareas" id="edit_hectareas" class="form-control" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Importar Excel -->
<div class="modal fade" id="importarExcelModal" tabindex="-1" role="dialog" aria-labelledby="importarExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importarExcelModalLabel">Importar Lotes desde Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin-lotes-import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="archivoExcel">Seleccionar archivo Excel:</label>
                        <input type="file" name="archivoExcel" id="archivoExcel" class="form-control" accept=".xls, .xlsx" required>
                        <input type="hidden" name="predio_id" value="{{$predio->id}}">
                    </div>
                    <p class="text-muted">
                        Asegúrate de que el archivo tenga el siguiente formato antes de importarlo
                    </p>
                    
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>codigo</strong></td>
                            <td><strong>nombre</strong></td>
                            <td><strong>hectareas</strong></td>
                        </tr>
                        <tr>
                            <td>L1</td>
                            <td>lote1</td>
                            <td>10.25</td>
                        </tr>
                        <tr>
                            <td>L2</td>
                            <td>lote2</td>
                            <td>1500.01</td>
                        </tr>
                        <tr>
                            <td>L3</td>
                            <td>lote3</td>
                            <td>2025.21</td>
                        </tr>
                    </table>
                    
                    
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Importar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
 function abrirModalEditarLote(id, codigo, nombre, hectareas) {
    // Asignar valores usando getElementById
    document.getElementById('edit_lote_id').value = id;
    document.getElementById('edit_codigo').value = codigo;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_hectareas').value = hectareas;

    // Abrir el modal
    $('#editarLoteModal').modal('show');
}




</script>
 
@stop
