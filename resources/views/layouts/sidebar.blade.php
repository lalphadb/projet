<aside class="main-sidebar sidebar-dark-primary elevation-3">
    <!-- Brand -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text">Studios UnisDB</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Membres -->
                <li class="nav-item">
                    <a href="{{ route('membres.index') }}" class="nav-link {{ request()->is('membres*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Membres</p>
                        @php
                            // Exemple de badge pour nouveaux membres
                            $nouveauxMembres = \App\Models\Membre::where('created_at', '>=', now()->subDays(7))->count();
                        @endphp
                        @if($nouveauxMembres > 0)
                            <span class="nav-badge">{{ $nouveauxMembres }}</span>
                        @endif
                    </a>
                </li>

                <!-- Cours -->
                <li class="nav-item">
                    <a href="{{ route('cours.index') }}" class="nav-link {{ request()->is('cours*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>Cours</p>
                    </a>
                </li>

                <!-- Présences -->
                <li class="nav-item">
                    <a href="{{ route('presences.index') }}" class="nav-link {{ request()->is('presences*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>Présences</p>
                    </a>
                </li>

                <!-- Écoles -->
                <li class="nav-item">
                    <a href="{{ route('ecoles.index') }}" class="nav-link {{ request()->is('ecoles*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>Écoles</p>
                    </a>
                </li>

                <!-- Utilisateurs (Admin/SuperAdmin uniquement) -->
                @if(Auth::user() && in_array(Auth::user()->role, ['admin', 'superadmin']))
                    <!-- Séparateur visuel -->
                    <li class="nav-separator"></li>
                    
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Utilisateurs</p>
                        </a>
                    </li>
                @endif

                <!-- Bouton de déconnexion -->
                <li class="nav-item logout-item">
                    <form method="POST" action="{{ route('logout') }}" class="w-100">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="logout-icon fas fa-sign-out-alt"></i>
                            <p class="logout-text">Se déconnecter</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>
