@extends('layouts.guest')

@section('title', 'Activer mon compte')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>Studios</b> Unis</a>
    </div>

    <div class="card card-outline card-success bg-dark text-white shadow">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Activer votre compte avec votre adresse courriel</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
               <form method="POST" action="{{ route('activation.store') }}">
                @csrf
                <div class="input-group mb-3">
                    <input name="email" type="email" class="form-control" placeholder="Votre courriel" required>
                    <div class="input-group-append">
                        <div class="input-group-text bg-dark"><span class="fas fa-envelope text-white"></span></div>
                    </div>
                </div>
                @error('email') <div class="text-danger mb-2">{{ $message }}</div> @enderror

                <div class="row">
                    <div class="col-8">
                        <a href="{{ route('login') }}" class="text-white">Retour à la connexion</a>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-success btn-block">Activer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
