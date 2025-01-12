<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp ') }}" type="image/x-icon">

    <title>@yield('title', 'Mapterra')</title>

      <!-- Estilos de AdminLTE -->
      <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}"> 
      <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/OverlayScrollbars.min.css') }}">

      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    
    <!-- Otros estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Estilos para el preloader -->
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #preloader.hidden {
            display: none;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div id="preloader">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>

    <div class="wrapper">
        <!-- Barra de navegación superior -->
        @include('layouts.partials.navbar')

        <!-- Barra lateral (sidebar) --> 
        @yield('sidebar')

        <!-- Contenido principal -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    @yield('content-header')
                </div>
            </div>
            
            <!-- Contenido principal de la página -->
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Pie de página -->
        @yield('footer')
    </div>
 
    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/jquery.overlayScrollbars.min.js') }}"></script>

    <script>
        // Preloader
        window.addEventListener('load', function () {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.classList.add('hidden');
            }
        });

        setTimeout(function() {
            // Cierra el mensaje después de 5 segundos
            $('.alert').alert('close');
        }, 3000); // 5000 milisegundos = 5 segundos

    </script>

    <!-- Otros scripts personalizados -->
    @yield('scripts')
</body>
</html>
