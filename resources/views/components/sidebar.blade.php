<!-- Sidebar complète AdminLTE -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text">StudiosUnisDB</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('membres.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Membres</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cours.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Cours</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('presences.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-check"></i>
                        <p>Présences</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('journees-portes-ouvertes.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>Portes Ouvertes</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
