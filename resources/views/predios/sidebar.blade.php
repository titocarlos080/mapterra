<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- AGRICULTURA Section -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-tractor" style="color: green;"></i>
                    <p>
                        AGRICULTURA
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('cartografia.index',[$empresa->id,$predio->id]) }}" class="nav-link">
                            <i class="fas fa-map-marked-alt nav-icon" style="color: blue;"></i>
                            <p>Cartografía</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('historico.index',[$empresa->id,$predio->id]) }}" class="nav-link">
                            <i class="fas fa-history nav-icon" style="color: cyan;"></i>
                            <p>Históricos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('analisis.predio.index',[$empresa->id,$predio->id]) }}" class="nav-link">
                            <i class="fas fa-chart-bar nav-icon" style="color: orange;"></i>
                            <p>Análisis Predio</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('analisis.predio.cultivo.index',[$empresa->id,$predio->id]) }}" class="nav-link">
                            <i class="fas fa-seedling nav-icon" style="color: green;"></i>
                            <p>Análisis Cultivo</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('monitoreo.index',[$empresa->id,$predio->id]) }}" class="nav-link">
                            <i class="fas fa-desktop nav-icon" style="color: red;"></i>
                            <p>Monitoreo</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- GANADERIA Section -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-horse-head" style="color: yellow;"></i>
                    <p>
                        GANADERIA
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('potrero.index',[$empresa->id,$predio->id]) }}" class="nav-link">
                            <i class="fas fa-tree nav-icon" style="color: green;"></i>
                            <p>Potreros</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('hato-ganadero.index',[$empresa->id,$predio->id]) }}" class="nav-link">
                            <i class="fas fa-paw nav-icon" style="color: orange;"></i>
                            <p>Hato Ganadero</p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>