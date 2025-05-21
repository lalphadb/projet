@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>Studios</b> Unis</a>
    </div>

    <div class="card card-outline card-warning bg-dark text-white shadow">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Mot de passe oublié ? Entrez votre email</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Votre courriel" value="{{ old('email') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text bg-dark"><span class="fas fa-envelope text-white"></span></div>
                    </div>
                </div>
                @error('email') <div class="text-danger mb-2">{{ $message }}</div> @enderror

                <div class="row">
                    <div class="col-8">
                        <a href="{{ route('login') }}" class="text-white">Retour</a>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-warning btn-block">Envoyer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
