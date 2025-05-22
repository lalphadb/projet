@extends('layouts.guest')

@section('title', 'Connexion - Studios UnisDB')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <!-- En-tête -->
        <div class="auth-header">
            <div class="auth-logo">🏢</div>
            <h1 class="auth-title">Studios UnisDB</h1>
            <p class="auth-subtitle">Connectez-vous à votre espace de travail</p>
        </div>

        <!-- Alerte de statut -->
        @if (session('status'))
            <div class="alert-modern">
                <i class="fas fa-info-circle mr-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Formulaire de connexion -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email -->
            <div class="form-group-stack">
                <label for="email" class="form-label">Adresse courriel</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control-stack" 
                       placeholder="votre.email@exemple.com"
                       required 
                       autofocus 
                       value="{{ old('email') }}">
                @error('email')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="form-group-stack">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control-stack" 
                       placeholder="Votre mot de passe"
                       required>
                @error('password')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Options de connexion -->
            <div class="form-options">
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="remember" id="remember" class="checkbox-custom">
                    <label for="remember" class="checkbox-label">Se souvenir de moi</label>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-link">
                    <i class="fas fa-key mr-1"></i>Mot de passe oublié ?
                </a>
            </div>

            <!-- Bouton de connexion -->
            <button type="submit" class="btn-primary-stack" id="loginBtn">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Se connecter
            </button>

            <!-- Acceptation de la politique -->
            <div class="policy-wrapper">
                <div class="policy-checkbox">
                    <input type="checkbox" required class="checkbox-custom" id="policy">
                    <label for="policy" class="policy-text">
                        En vous connectant, vous acceptez notre 
                        <a href="{{ route('politique') }}" class="policy-link" target="_blank">
                            Politique de confidentialité
                        </a> 
                        et nos conditions d'utilisation.
                    </label>
                </div>
            </div>

            <!-- Boutons secondaires -->
            <div class="btn-group-stack">
                <a href="{{ route('register') }}" class="btn-secondary-stack">
                    <i class="fas fa-user-plus"></i>
                    Créer un compte
                </a>
                
                <a href="{{ route('activation.index') }}" class="btn-secondary-stack btn-danger-stack">
                    <i class="fas fa-user-check"></i>
                    Déjà inscrit ?
                </a>
            </div>
        </form>

        <!-- Footer -->
        <div class="auth-footer">
            <div>
                <a href="{{ route('politique') }}" class="footer-link">
                    Politique de confidentialité
                </a>
            </div>
            <div class="mt-2">
                &copy; {{ date('Y') }} Studios UnisDB. Tous droits réservés.
            </div>
        </div>
    </div>
</div>
@endsection
