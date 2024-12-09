@extends('layouts.app')

<!-- Agrega el ícono en la pestaña -->
<link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">

@section('title', $predio->nombre . '-Analisis Cultivo' )

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
 

    ANALISIS-CULTIVO
@stop
