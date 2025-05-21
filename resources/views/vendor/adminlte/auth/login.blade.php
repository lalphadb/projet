@extends('layouts.login')

@section('title', 'Connexion')

@section('content')
<div class="login-box glass-card text-white">
    <div class="text-center mb-3">
        <h2><b>Studios</b> UnisDB</h2>
    </div>

    <div class="login-card-body">
        <p class="login-box-msg">🔐 Connectez-vous à votre compte</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-3 text-success" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Adresse courriel</label>
                <div class="input-group">
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="ex: nom@exemple.com">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger" />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required placeholder="********">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger" />
            </div>

            <!-- Remember -->
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                @if (Route::has('password.request'))
                    <a class="text-info fw-bold" href="{{ route('password.request') }}">
                        🔑 Mot de passe oublié ?
                    </a>
                @endif
                <button type="submit" class="btn btn-gradient">Connexion</button>
            </div>

            <!-- Politique de confidentialité -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="accept_policy" id="accept_policy" required>
                <label class="form-check-label" for="accept_policy">
                    ✅ J’ai lu et j’accepte la <a href="{{ route('politique') }}" class="text-warning" target="_blank">Politique de confidentialité</a>
                </label>
            </div>

            <!-- Boutons secondaires -->
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('register') }}" class="btn btn-outline-light">
                    Créer un compte
                </a>
                <a href="{{ route('activation.index') }}" class="btn btn-danger">
                    Déjà inscrit ?
                </a>
            </div>
        </form>

        <footer class="text-center small mt-4 text-muted">
            <a href="{{ route('politique') }}" class="text-muted">Politique de confidentialité</a>
        </footer>
    </div>
</div>
@endsection
