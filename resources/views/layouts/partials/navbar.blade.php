<!-- resources/views/layouts/partials/navbar.blade.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
      <!-- Botón para alternar el sidebar -->
      <li class="nav-item">
          <a class="nav-link" href="#" data-widget="pushmenu">
              <i class="fas fa-bars"></i>
          </a>
      </li>
     
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
        <!-- Formulario de logout -->
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="nav-link btn btn-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </li>
</ul>

</nav>
