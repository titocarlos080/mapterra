<!-- resources/views/layouts/partials/sidebar.blade.php -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="/" class="brand-link">
      <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light">Dashboard</span>
  </a>
  
  <div class="sidebar">
      <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
              <li class="nav-item">
                  <a href="{{ route('home') }}" class="nav-link">
                      <i class="nav-icon fas fa-home"></i>
                      <p>Inicio</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-user"></i>
                      <p>Perfil</p>
                  </a>
              </li>
          </ul>
      </nav>
  </div>
</aside>
