<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-text font-weight-light">StudiosUnisDB</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <a href="{{ route('mon-compte') }}" class="nav-link {{ request()->is('mon-compte') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>Mon compte</p>
                        </a>
                    </li>

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Utilisateurs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sessions.index') }}" class="nav-link {{ request()->is('sessions') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Sessions</p>
                            </a>
                        </li>
                    @endif
                @endauth

                <li class="nav-item">
                    <a href="{{ route('membres.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Membres</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cours.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
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
                    <a href="{{ route('portes-ouvertes.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>Portes Ouvertes</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
