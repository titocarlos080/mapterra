<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" data-no-transition-after-reload="false">
              <i class="fas fa-bars"></i>
              <span class="sr-only">Alternar barra de navegación</span>
          </a>
      </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
      <!-- Dark Mode Toggle -->
      <li class="nav-item adminlte-darkmode-widget">
          <a class="nav-link" href="#" role="button">
              <i class="fas fa-moon"></i>
          </a>
      </li>

      <!-- Fullscreen Button -->
      <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
          </a>
      </li>

      <!-- User Dropdown Menu -->
      <li class="nav-item dropdown user-menu ">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
              <img src="{{ Auth::user()->profile_image ?? asset('vendor/adminlte/dist/img/default-user.png') }}" 
                   class="user-image img-circle elevation-2" 
                   alt="{{ Auth::user()->name }}">
              <span class="d-none d-md-inline">
                  {{ Auth::user()->name }}
              </span>
          </a>

          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <!-- User Header -->
              <li class="user-header bg-success">
                  <img src="{{ Auth::user()->profile_image ?? asset('vendor/adminlte/dist/img/default-user.png') }}" 
                       class="img-circle elevation-2" 
                       alt="{{ Auth::user()->name }}">
                  <p>{{ Auth::user()->name }}</p>
              </li>

              <!-- User Footer -->
              <li class="user-footer">
                  <a class="btn btn-default btn-flat float-right btn-block" href="#" 
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      <i class="fa fa-fw fa-power-off text-red"></i>
                      Salir
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
              </li>
          </ul>
      </li>
  </ul>
</nav>
