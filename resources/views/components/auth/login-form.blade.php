<div class="login-box">
    <div class="login-logo">
        <b>Studios</b>UnisDB
    </div>

    <div class="card bg-dark bg-opacity-75 text-white shadow-lg rounded-3">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Connectez-vous</p>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Adresse courriel" required autofocus>
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>

                <div class="row mb-2">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Se souvenir de moi</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Connexion</button>
                    </div>
                </div>
            </form>

            <p class="mb-1">
                <a href="{{ route('password.request') }}" class="text-light">Mot de passe oublié ?</a>
            </p>

            <p class="mb-0">
                <a href="{{ route('activation.index') }}" class="text-info">
                     Activez votre compte ici
                </a>
            </p>
        </div>
    </div>
</div>
