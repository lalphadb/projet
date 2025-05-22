@extends('layouts.guest')

@section('title', 'Créer un compte')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-gradient-dark">
    <div class="card glass-effect p-4" style="width: 480px;">
        <h2 class="text-center mb-3">📅 Créer un compte</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-control bg-light" value="{{ old('prenom') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control bg-light" value="{{ old('nom') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Adresse courriel</label>
                <input type="email" id="email" name="email" class="form-control bg-light" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control bg-light" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control bg-light" required>
            </div>

            <div class="form-check mt-3 mb-3">
                <input type="checkbox" class="form-check-input" required>
                <label class="form-check-label">J’accepte la <a href="{{ route('politique') }}" class="text-info">Politique de confidentialité</a></label>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('login') }}" class="btn btn-outline-light">Connexion</a>
                <button type="submit" class="btn btn-success">Créer un compte</button>
            </div>
        </form>
    </div>
</div>
@endsection
