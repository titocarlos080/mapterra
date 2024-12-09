@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">

@section('title', $predio->nombre . ' -Cartografia')

@section('content_header')
    <h1>{{ $predio->nombre }}</h1>
@stop
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
 @include('empresa.predios.sidebar')
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

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>
                <h1 class="card-title">Cartografía: {{ $predio->nombre }}</h1>
            </div>
            <div class="btn btn-warning ml-auto"  data-toggle="modal" data-target="#agregarPredioModal">
                <i class="fas fa-plus-circle text-success"></i> Agregar
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Municipio</th>
                            <th>Provincia</th>
                            <th>Departamento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empresa->predios as $predio)
                        <tr>
                            <td>{{ $predio->nombre }}</td>
                            <td>{{ $predio->municipio }}</td>
                            <td>{{ $predio->provincia }}</td>
                            <td>{{ $predio->departamento }}</td>
                            <td>
                                <!-- Botones de acciones -->
                                <a href="{{ route('empresa.predio.show', [$predio->empresa_id,$predio->id]) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Ver">
                                    <i class="fas fa-eye"></i>  
                                </a>
                                
                                <a href="{{ route('empresa.predio.edit',  [$predio->empresa_id,$predio->id]) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>  
                                </a>
                                
                                <!-- Formulario para eliminar predio -->
                                <form action="{{ route('empresa.predio.destroy',[$predio->empresa_id,$predio->id]) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este predio?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>  
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- <!-- Paginación -->
            <div class="mt-3">
                {{ $predios->links() }}
            </div> --}}


        </div>
    </div>

    <!-- Modal para agregar un predio -->
    <div class="modal fade" id="agregarPredioModal" tabindex="-1" role="dialog" aria-labelledby="agregarPredioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarPredioModalLabel">Agregar Predio   <i class="fas fa-map-pin nav-icon" style="color: green;"></i> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_predio" action="{{ route('empresa.predios.store', $empresa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Nombre del Predio -->
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                
                        <!-- Municipio -->
                        <div class="form-group">
                            <label for="municipio">Municipio</label>
                            <input type="text" class="form-control" id="municipio" name="municipio" required>
                        </div>
                
                        <!-- Provincia -->
                        <div class="form-group">
                            <label for="provincia">Provincia</label>
                            <input type="text" class="form-control" id="provincia" name="provincia" required>
                        </div>
                
                        <!-- Departamento -->
                        <div class="form-group">
                            <label for="departamento">Departamento</label>
                            <input type="text" class="form-control" id="departamento" name="departamento" required>
                        </div>
                
                        <!-- Archivo KMZ -->
                        <div class="form-group">
                            <label for="archivo_kmz">Archivo KMZ</label>
                            <div class="border p-3 text-center" id="drop-area" style="cursor: pointer; position: relative;">
                                <p>Arrastra y suelta un archivo o haz clic para seleccionar un archivo</p>
                         <input type="file" class="form-control-file" id="archivo_kmz" name="archivo_kmz" accept=".kmz" style="display: none;" onchange="handleFileSelect(event)">
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
                    var inputFile = document.getElementById('archivo_kmz');
                
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
                        formData.append("archivo_kmz", file);
                
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

    <script>
        setTimeout(function() {
            // Cierra el mensaje después de 5 segundos
            $('.alert').alert('close');
        }, 5000); // 5000 milisegundos = 5 segundos
    </script>
@stop
