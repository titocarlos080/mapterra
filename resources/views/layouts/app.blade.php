<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mapterra')</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    
    <!-- Otros estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Barra de navegación superior -->
        @include('layouts.partials.navbar')

        <!-- Barra lateral (sidebar) -->
        @include('layouts.partials.sidebar')

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
        @include('layouts.partials.footer')
    </div>

    <!-- Scripts de AdminLTE -->
    <script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

    <!-- Otros scripts personalizados -->
    @yield('scripts')
</body>
</html>
