@extends('layouts.guest')

@section('title', 'Activer mon compte')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-gradient-dark">
    <div class="card glass-effect p-4" style="width: 440px;">
        <h2 class="text-center mb-3">🚀 Activation</h2>
        <p class="text-center text-muted">Si vous êtes déjà inscrit à une journée portes ouvertes, activez votre compte ci-dessous.</p>

        <form method="POST" action="{{ route('activation.store') }}">
            @csrf

            <div class="form-group">
                <label for="email">Adresse courriel</label>
                <input type="email" id="email" name="email" class="form-control bg-light" required>
            </div>

            <div class="form-group">
                <label for="code">Code d'activation</label>
                <input type="text" id="code" name="code" class="form-control bg-light" required>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('login') }}" class="btn btn-outline-light">Retour</a>
                <button type="submit" class="btn btn-warning">Activer mon compte</button>
            </div>
        </form>
    </div>
</div>
@endsection
