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
@section('title', $predio->nombre . ' - ' . $tipoMapa->nombre)

@section('content_header')
    <h1>{{ $predio->nombre }}- {{$tipoMapa->nombre}}</h1>
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
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                 
                      @foreach($tipomapas as $tipomapa)
                        <li class="nav-item">
                            <a href="{{ route('admin-mapas',[$tipomapa->id,$empresa->id,$predio->id]) }}" class="nav-link">
                                <i class="{{$tipomapa->icon}}" style="color: green;"></i>
                                <p>{{$tipomapa->nombre}}</p>
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
            <div>
                <h1 class="card-title"> {{$tipoMapa->nombre}}: {{ $predio->nombre }}</h1>
            </div>
            <div class="btn btn-warning ml-auto" data-toggle="modal" data-target="#agregarMapaModal">
                <i class="fas fa-plus-circle text-success"></i> Agregar
            </div>
            
        </div>
        
        <div class="card-body">
            <div class="row">
                @foreach($mapas as $mapa)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">{{ $mapa->titulo }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Vista previa del archivo PDF -->
                            <div class="pdf-preview mb-3">
                                <embed src="{{ asset('storage/' . $mapa->path_file) }}" type="application/pdf" width="100%" height="200">
                            </div>
                            <!-- Fecha -->
                            <p><strong>Fecha:</strong> {{ $mapa->fecha }}</p>
                            <!-- Descripción -->
                            <p><strong>Descripción:</strong> {{ $mapa->descripcion }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <!-- Botones de acción -->
                            <div>
                                <a href="{{ route('empresa.predio.mapa.edit', [$predio->id, $mapa->id]) }}" 
                                   class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ asset('storage/' . $mapa->path_file) }}" 
                                   download class="btn btn-success btn-sm" data-toggle="tooltip" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                            <div>
                                <form action="{{ route('admin-mapas-delete', $mapa->id) }}" 
                                      method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este mapa?');">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        
           
        </div>
            
        
           
        
    </div>

<!-- Modal para agregar una Mapa -->
<div class="modal fade" id="agregarMapaModal" tabindex="-1" role="dialog" aria-labelledby="agregarMapaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarMapaModalLabel">Agregar Mapa <i class="fas fa-map-marked nav-icon" style="color: green;"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" action="{{ route('admin-mapas-store') }}" method="post" enctype="multipart/form-data" id="uploadForm">
              @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="tipomapa_id" value="{{$tipoMapa->id}}">
                        <input type="hidden" name="empresa_id" value="{{$empresa->id}}">
                        <input type="hidden" name="predio_id" value="{{$predio->id}}">
                        <label for="archivo_pdf">
                            <i class="fas fa-file-upload pointer-hand"></i> Subir PDF:
                        </label>
                    
                        <!-- Área de Carga (Click y Drag and Drop) -->
                        <div id="drop-area" class="drop-area border border-primary p-4 rounded text-center bg-light" 
                            ondrop="handleDrop(event)" ondragover="allowDrop(event)" onclick="triggerFileInput()">
                            <p class="text-muted">Arrastra y suelta tu archivo PDF aquí o haz clic para seleccionar.</p>
                            <input type="file" name="archivo_pdf" id="archivo_pdf" class="form-control" onchange="previewFile(event)" style="display: none;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="titulo">Titulo:</label>
                            <input type="text" name="titulo" id="titulo" class="form-control" >    
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" class="form-control"  ></textarea>
                    </div>

                    <!-- PDF Preview -->
                    <div id="pdf-preview-container" style="display: none; margin-top: 20px;">
                        <embed id="pdf-preview" width="100%" height="400px" type="application/pdf">
                    </div>
            
                    <!-- Progress Bar -->
                    <div id="progress-container" style="display: none; margin-top: 20px;">
                        <div class="progress">
                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar" style="width: 0%;"></div>
                        </div>
                        <p id="progress-text" class="text-center">0%</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cancelUpload()">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>



     // Permitir que el archivo se pueda soltar en el área
     function allowDrop(event) {
        event.preventDefault(); // Prevenir el comportamiento por defecto (abrir archivo en algunas navegadores)
        document.getElementById('drop-area').classList.add('bg-info'); // Cambiar color de fondo cuando se arrastra
    }

    // Manejar el evento cuando se suelta el archivo
    function handleDrop(event) {
        event.preventDefault();
        document.getElementById('drop-area').classList.remove('bg-info'); // Remover el color de fondo
        const file = event.dataTransfer.files[0];
        if (file) {
            // Establecer el archivo en el campo de entrada de archivo
            const fileInput = document.getElementById('archivo_pdf');
            fileInput.files = event.dataTransfer.files;
            previewFile({ target: fileInput });
        }
    }

    // Simula un clic en el input de archivo cuando se hace clic en el área de carga
    function triggerFileInput() {
        document.getElementById('archivo_pdf').click();
    }
    // File preview and upload progress
    function previewFile(event) {
        var file = event.target.files[0];
        if (file) {
            // Preview PDF
            var reader = new FileReader();
            reader.onload = function(e) {
                var pdfPreview = document.getElementById('pdf-preview');
                pdfPreview.src = e.target.result;
                document.getElementById('pdf-preview-container').style.display = 'block';
            };
            reader.readAsDataURL(file);

            // Start file upload with progress bar
            var formData = new FormData();
            formData.append('archivo_pdf', file);

            // Show progress bar
            document.getElementById('progress-container').style.display = 'block';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/your-upload-endpoint', true);

            // Update progress bar as upload progresses
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    var percent = (e.loaded / e.total) * 100;
                    document.getElementById('progress-bar').style.width = percent + '%';
                    document.getElementById('progress-text').textContent = Math.round(percent) + '%';
                }
            };

            xhr.onload = function() {
               
                    
               
            };

            xhr.send(formData);
        }
    }

    function cancelUpload() {
        // Reset the form and progress bar
        document.getElementById('uploadForm').reset();
        document.getElementById('pdf-preview-container').style.display = 'none';
        document.getElementById('progress-container').style.display = 'none';
    }

    setTimeout(function() {
            // Cierra el mensaje después de 5 segundos
            $('.alert').alert('close');
        }, 3000);
</script>
 
@stop
