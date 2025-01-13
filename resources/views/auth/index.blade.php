<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapterraGO</title>
    <link rel="icon" href="{{ asset('vendor/adminlte/dist/img/mapterralogo.webp') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
     <script src="{{ asset('vendor/bootstrap/js/bootstrap.js') }}"></script>
     <script src="{{ asset('vendor/adminlte/dist/js/adminlte.js') }}"></script>

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

        <!-- Login Card -->
        <div class="card-body login-card-body rounded" style="background-color: rgba(255, 255, 255, 0.6);">
            <p class="login-box-msg text-success font-weight-bold">Iniciar Sesión</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email -->
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="email" 
                           value="{{ old('email') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope text-success"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Contraseña -->
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control"  required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock text-success"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Mensaje de error -->
                @error('error')
                <span class="text-danger">{{ $message }}</span>
                @enderror

                <!-- Opciones y Botón -->
                <div class="row align-items-center">
                    <!-- Checkbox -->
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Recordarme</label>
                        </div>
                    </div>

                    <!-- Botón Iniciar -->
                    <div class="col-4 text-right">
                        <button type="submit" class="btn btn-success btn-block">Iniciar</button>
                    </div>
                </div>
            </form>

            <!-- Olvidé mi contraseña -->
            <p class="mb-1 text-center">
                <a href="{{ route('olvide_password') }}" style="color: #d87b1f; text-decoration: none;" 
                   onmouseover="this.style.color='#b35e18'" 
                   onmouseout="this.style.color='#d87b1f'">
                   Olvidé mi contraseña
                </a>
            </p>
        </div>
    </div>
   
</body>

</html>
