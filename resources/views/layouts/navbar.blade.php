<nav class="main-header navbar navbar-expand navbar-dark elevation-3">
    <!-- Brand/Logo -->
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <span>Studios Unis</span>
    </a>

    <!-- Bouton toggle sidebar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Toggle Navigation">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Navigation utilisateur -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications (optionnel) -->
        <li class="nav-item navbar-notification">
            <a class="nav-link" href="#">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </a>
        </li>

        <!-- Menu utilisateur -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- Avatar utilisateur -->
                <span class="user-avatar">
                    {{ substr(Auth::user()->prenom ?? 'A', 0, 1) }}{{ substr(Auth::user()->nom ?? 'U', 0, 1) }}
                </span>
                {{ Auth::user()->prenom ?? 'Admin' }}
            </a>
            
            <div class="dropdown-menu dropdown-menu-right">
                <!-- Profil utilisateur -->
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-circle"></i>
                    Mon profil
                </a>
                
                <!-- Séparateur -->
                <div class="dropdown-divider"></div>
                
                <!-- Paramètres -->
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cog"></i>
                    Paramètres
                </a>
                
                <!-- Aide -->
                <a class="dropdown-item" href="#">
                    <i class="fas fa-question-circle"></i>
                    Aide
                </a>
                
                <!-- Séparateur -->
                <div class="dropdown-divider"></div>
                
                <!-- Déconnexion -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
