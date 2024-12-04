<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi Contraseña</title>
    <link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.js') }}"></script>
</head>

<body class="d-flex justify-content-center align-items-center bg-dark" 
      style="height: 100vh; margin: 0; 
             background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.1)), 
                               url('https://mapterrabo.com/wp-content/uploads/2024/06/c92650f16717eed6cbb1c85f9d6ed5ff-edited.jpg'); 
             background-size: cover; 
             background-position: center;">

    <div class="login-box">
        <!-- Logo -->
        <div class="login-logo text-center">
            <div>
                <img src="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" alt="Mapterra Logo" 
                     style="max-width: 60px; height: auto; margin-bottom: 10px;">
                <b class="text-success">Mapterra</b>
                <span style="color: #f4a424;">GO</span>
            </div>
        </div>

        <!-- Recuperar Contraseña Card -->
        <div class="card-body login-card-body rounded" style="background-color: rgba(255, 255, 255, 0.8);">
            @if (session('success'))
                <!-- Mensaje de Éxito -->
                <p class="text-success text-center font-weight-bold mt-3">
                    {{ session('success') }}
                </p>
                <div class="text-center mt-4">
                    <a href="{{ route('index') }}" class="btn btn-success">Ir a Iniciar Sesión</a>
                </div>
            @else
                <p class="login-box-msg text-success font-weight-bold">Recuperar Contraseña</p>
                <p class="text-muted text-center">Ingresa tu correo electrónico para recibir un enlace de recuperación.</p>
                <form method="POST" action="{{ route('password_email') }}">
                    @csrf
                    <!-- Email -->
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Correo electrónico" 
                               value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope text-success"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mensaje de Error -->
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <!-- Botón Enviar -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-block">Enviar Enlace</button>
                        </div>
                    </div>
                </form>

                <!-- Error de Envío de Correo -->
                @if ($errors->has('error'))
                    <div class="alert alert-danger text-center mt-3">
                        {{ $errors->first('error') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</body>

</html>
