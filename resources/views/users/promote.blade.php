@extends('layouts.app')

@section('title', 'Promouvoir un membre')
@section('content')
<div class="content-wrapper p-4">
    <section class="content-header d-flex justify-content-between align-items-center">
        <h1 class="text-white">Promouvoir un membre</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </section>

    <section class="content mt-3">
        <div class="card bg-dark text-white shadow">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.promote.store') }}">
                    @csrf

                    <div class="form-group">
                        <label>Membre à promouvoir</label>
                        <select name="membre_id" class="form-control" required>
                            <option value="">-- Sélectionner un membre --</option>
                            @foreach($membres as $membre)
                                <option value="{{ $membre->id }}">
                                    {{ $membre->prenom }} {{ $membre->nom }} ({{ $membre->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Rôle</label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="admin">Admin</option>
                                <option value="instructeur">Instructeur</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>École</label>
                            <select name="ecole_id" class="form-control" required>
                                <option value="">-- Sélectionner une école --</option>
                                @foreach($ecoles as $ecole)
                                    <option value="{{ $ecole->id }}">{{ $ecole->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-user-shield"></i> Promouvoir
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
