@extends('layouts.app')

@section('title', 'Home')

{{-- @section('content-header')
    <h1>Detalles del Predio</h1>
@endsection --}}

{{-- MENU DE NAVEGACION DE LADO IZQUIERDO --}}
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
                <!-- CLIENTES Section -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users" style="color: green;"></i>
                        <p>
                            CLIENTES
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="admin/pages/empresas" class="nav-link">
                                <i class="fas fa-list nav-icon" style="color: blue;"></i>
                                <p>Lista de Clientes</p>
                            </a>
                        </li>
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
<div class="row m-2">
    <!-- Tarjetas con estadísticas -->
    <div class="col-xl-4 col-md-6">
        <div class="card shadow mb-4">
            <div class="card-body rounded" style="background: #d4760a">
                <div class="row align-items-center">
                    <div class="col-2">
                        <i class="fas fa-users fa-2x text-success"></i> <!-- Icono representativo de "Clientes" -->
                    </div>
                    <div class="col-10">
                        <h5 class="font-weight-bold">Clientes</h5>
                        <p class="h3">150</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card shadow mb-4">
            <div class="card-body rounded" style="background: #c4b406">
                <div class="row align-items-center">
                    <div class="col-2">
                        <i class="fas fa-users-cog fa-2x text-warning"></i> <!-- Icono representativo de "Grupos" -->
                    </div>
                    <div class="col-10">
                        <h5 class="font-weight-bold">Grupos</h5>
                        <p class="h3">$5,000</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card shadow mb-4"> 
            <div class="card-body rounded" style="background: #148519">
                <div class="row align-items-center">
                    <div class="col-2">
                        <i class="fas fa-tasks fa-2x text-primary"></i> <!-- Icono representativo de "Trabajos Solicitados" -->
                    </div>
                    <div class="col-10">
                        <h5 class="font-weight-bold">Trabajos Solicitados</h5>
                        <p class="h3">20</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Gráficos -->
<div class="row m-2">
     <div class="col-xl-4 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Visitantes por Semana</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="visitantesChart" style="height: 200px;"></canvas> <!-- Ajustado -->
                </div>
            </div>
        </div>
    </div>

     <div class="col-xl-4 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Visitas por Dispositivo</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="dispositivosChart" style="height: 200px;"></canvas> <!-- Ajustado -->
                </div>
            </div>
        </div>
    </div>

     <div class="col-xl-4 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Ingresos Mensuales</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="ingresosChart" style="height: 200px;"></canvas> <!-- Ajustado -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        console.log('Vista cargada correctamente');
    </script>
@endsection
