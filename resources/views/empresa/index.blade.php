
@extends('layouts.app')

@section('title', 'Empresa')

{{-- @section('content-header')
    <h1>Detalles del Predio</h1>
@endsection --}}

{{-- MENU DE NAVEGACION DE LADO IZQUIERDO --}}
@section('sidebar')
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/home" class="brand-link ">
        <img src="{{asset('vendor/adminlte/dist/img/mapterralogo.webp')}}" alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
        
        <span class="brand-text font-weight-light ">        <b>Mapterra</b>GO        </span>
    </a>
  <!-- Sidebar Menu -->
  <div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Empresa -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users" style="color: green;"></i>
                    <p>
                        Empresas
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                {{-- //  FOR PARA LISTAR EMPRESA --}}
                <ul class="nav nav-treeview">
                    @foreach($empresas as $empresa)

                    <li class="nav-item">
                        <a href="{{ route('admin-predios-empresa', $empresa->id) }}"  class="nav-link">
                            <i class="fas fa-list nav-icon" style="color: green;"></i>
                            <p>{{$empresa->nombre}}</p>
                        </a>
                    </li>
                   
                    @endforeach
                </ul>
            </li>
        </ul>
    </nav>
</div>
<!-- /.sidebar -->

</aside>


@endsection
{{-- PAGINA PRINCIPAL --}}
@section('content')
  
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

 
<div class="card-header d-flex justify-content-between">
   <div>
     </div> 
    <div class="btn btn-warning ml-auto"  data-toggle="modal" data-target="#agregarModal">
        <i class="fas fa-plus-circle text-success"></i> Agregar
    </div>
</div>

<!-- /.card-header -->
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empresas as $empresa)
                <tr>
                  <td>{{ $empresa->nombre }}</td>
                  <td>{{ $empresa->direccion }}</td>
                  <td>{{ $empresa->telefono }}</td>
                  <td>    
                    <!-- Botón para ver detalles -->
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#verEmpresaModal" 
                            onclick="setEmpresaDetails('{{ $empresa->nombre }}', '{{ $empresa->direccion }}', '{{ $empresa->telefono }}', '{{ $empresa->ganaderia }}', '{{ $empresa->agricultura }}')">
                      <i class="fas fa-eye"></i>
                    </button>
                
                    <!-- Botón para editar empresa -->
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editarEmpresaModal" 
                            onclick="setEditEmpresaDetails('{{ $empresa->id }}', '{{ $empresa->nombre }}', '{{ $empresa->direccion }}', '{{ $empresa->telefono }}', '{{ $empresa->ganaderia }}', '{{ $empresa->agricultura }}')">
                      <i class="fas fa-edit"></i>
                    </button>
                
                    <!-- Eliminar empresa -->
                    <form action="{{ route('admin-empresas-delete', $empresa->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta empresa?');">
                      @csrf
                      @method('POST')
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>
                
                    <!-- Botón para ver predios -->
                    <a href="{{ route('admin-predios-empresa', $empresa->id) }}" class="btn btn-success btn-sm">
                      <i class="fas fa-map-marked-alt"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
                
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="mt-3">
        {{ $empresas->links() }}
    </div>
</div>
<!-- /.card-body -->



<!-- Modal para ver detalles de la empresa -->
    <div class="modal fade" id="verEmpresaModal" tabindex="-1" role="dialog" aria-labelledby="verEmpresaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="verEmpresaModalLabel">Detalles de la Empresa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <p><strong>Nombre:</strong> <span id="modalNombre"></span></p>
            <p><strong>Dirección:</strong> <span id="modalDireccion"></span></p>
            <p><strong>Teléfono:</strong> <span id="modalTelefono"></span></p>
            <p><strong>Ganadería:</strong> <span id="modalGanaderia"></span></p>
            <p><strong>Agricultura:</strong> <span id="modalAgricultura"></span></p>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        </div>
    </div>

<!-- Modal para editar la empresa -->
<div class="modal fade" id="editarEmpresaModal" tabindex="-1" role="dialog" aria-labelledby="editarEmpresaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editarEmpresaModalLabel">Editar Empresa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('admin-empresas-update') }}" id="editEmpresaForm">
          @csrf
          @method('POST')
          <div class="modal-body">
            <input type="hidden" id="editEmpresaId" name="id">
            <div class="form-group">
              <label for="editNombre">Nombre</label>
              <input type="text" class="form-control" id="editNombre" name="nombre" required>
            </div>
            <div class="form-group">
              <label for="editDireccion">Dirección</label>
              <input type="text" class="form-control" id="editDireccion" name="direccion" required>
            </div>
            <div class="form-group">
              <label for="editTelefono">Teléfono</label>
              <input type="text" class="form-control" id="editTelefono" name="telefono" required>
            </div>
            <div class="form-group">
              <label for="editGanaderia">Ganadería</label>
              <select class="form-control" id="editGanaderia" name="ganaderia">
                <option value="1">Activo</option>
                <option value="0">No activo</option>
              </select>
            </div>
            <div class="form-group">
              <label for="editAgricultura">Agricultura</label>
              <select class="form-control" id="editAgricultura" name="agricultura">
                <option value="1">Activo</option>
                <option value="0">No activo</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
</div>








<script>
  function setEmpresaDetails(nombre, direccion, telefono, ganaderia, agricultura) {
    document.getElementById('modalNombre').innerText = nombre;
    document.getElementById('modalDireccion').innerText = direccion;
    document.getElementById('modalTelefono').innerText = telefono;
    document.getElementById('modalGanaderia').innerText = ganaderia == 1 ? 'Activo' : 'No activo';
    document.getElementById('modalAgricultura').innerText = agricultura == 1 ? 'Activo' : 'No activo';
  }

  function setEditEmpresaDetails(id, nombre, direccion, telefono, ganaderia, agricultura) {
    document.getElementById('editEmpresaId').value = id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editDireccion').value = direccion;
    document.getElementById('editTelefono').value = telefono;
    document.getElementById('editGanaderia').value = ganaderia;
    document.getElementById('editAgricultura').value = agricultura;
  }
 

    setTimeout(function() {
    // Cierra el mensaje después de 5 segundos
    $('.alert').alert('close');
}, 3000); // 5000 milisegundos = 5 segundos
</script>
<!-- Modal -->
 
  
  

  {{-- /////////////////////////////////// AGREGAR EMPRESA///// --}}

  <!-- Modal para agregar una empresa -->
<div class="modal fade" id="agregarModal" tabindex="-1" role="dialog" aria-labelledby="agregarModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="agregarModalLabel">Agregar Empresa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="{{route("admin-empresas-store")}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
    
            <!-- Campo Dirección -->
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
    
            <!-- Campo Teléfono -->
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
        <!-- Campo Teléfono -->
        <div class="form-group">
            <label for="agricultura">Agricultura</label>
            <input type="checkbox"  id="agricultura" name="agricultura" required>
            <label for="ganaderia">Ganaderia</label>
            <input type="checkbox"  id="ganaderia" name="ganaderia" required>
        </div>
            <!-- Campo Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
    
            <!-- Campo Contraseña -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
    
            <!-- Campo Foto (Drag and Drop o Selección) -->
            <div class="form-group">
                <label for="foto">Foto</label>
                <div class="border p-3 text-center" id="drop-area" style="cursor: pointer; position: relative;">
                    <p>Arrastra y suelta una imagen o haz clic para seleccionar una</p>
                    <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" style="display: none;" onchange="handleFileSelect(event)">
                    <div id="img-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: none;">
                        <img id="preview" src="#" alt="Vista previa" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.6;">
                    </div>
                </div>
                
                <!-- Barra de progreso -->
                <div id="progress-container" class="mt-3" style="display: none;">
                    <div class="progress">
                        <div id="progress-bar" class="progress-bar" style="width: 0%; background-color: green;"></div>
                    </div>
                    <p id="progress-text" class="text-center">0%</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
    
    <script>
        // Función para mostrar la imagen seleccionada
        function previewImage(file) {
            var reader = new FileReader();
            reader.onload = function() {
                var preview = document.getElementById('preview');
                var overlay = document.getElementById('img-overlay');
                preview.style.display = 'block'; // Muestra la vista previa
                preview.src = reader.result; // Asigna el resultado de la imagen al src
                overlay.style.display = 'block'; // Muestra el contenedor de la imagen superpuesta
            };
            reader.readAsDataURL(file); // Lee la imagen
        }
    
        // Función para manejar la selección de archivo
        function handleFileSelect(event) {
            var file = event.target.files[0];
            if (file) {
                previewImage(file); // Mostrar la vista previa de la imagen
                uploadFile(file); // Iniciar la carga
            }
        }
    
        // Permitir el drag and drop
        var dropArea = document.getElementById('drop-area');
        var inputFile = document.getElementById('foto');
    
        // Abrir el selector de archivos al hacer clic en el área
        dropArea.addEventListener('click', function() {
            inputFile.click(); // Abre el selector de archivo
        });
    
        // Permitir que se pueda arrastrar el archivo
        dropArea.addEventListener('dragover', function(event) {
            event.preventDefault(); // Permite que se pueda soltar el archivo
            dropArea.style.backgroundColor = '#f0f0f0'; // Cambia el fondo cuando se arrastra algo
        });
    
        dropArea.addEventListener('dragleave', function() {
            dropArea.style.backgroundColor = ''; // Restaura el fondo cuando el archivo se sale
        });
    
        // Manejar el evento de soltar el archivo
        dropArea.addEventListener('drop', function(event) {
            event.preventDefault(); // Previene la acción predeterminada
            var files = event.dataTransfer.files;
            inputFile.files = files; // Asigna el archivo a la entrada de file
            previewImage(event.dataTransfer.files[0]); // Muestra la vista previa de la imagen
            uploadFile(event.dataTransfer.files[0]); // Inicia la carga del archivo
        });
    
        // Función para manejar el progreso de carga
        function uploadFile(file) {
            var xhr = new XMLHttpRequest();
            var formData = new FormData();
            formData.append("foto", file);
    
            // Mostrar la barra de progreso
            var progressContainer = document.getElementById('progress-container');
            var progressBar = document.getElementById('progress-bar');
            var progressText = document.getElementById('progress-text');
            progressContainer.style.display = 'block'; // Muestra el contenedor de progreso
    
            // Establecer la URL de destino para la carga
            xhr.open("POST", "/admin/pages/empresas/store", true); // Cambia la URL a la ruta de tu controlador
    
            // Progreso de la carga
            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    var percent = (event.loaded / event.total) * 100;
                    progressBar.style.width = percent + "%";
                    progressText.textContent = Math.round(percent) + "%";
                }
            };
    
            
    
            // Enviar la solicitud
            xhr.send(formData);
        }
    </script>
    
    
</div>
</div>
</div>


@stop


@endsection

@section('scripts')
    <script>
        console.log('Vista cargada correctamente');
    </script>
@endsection

 


 