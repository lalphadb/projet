@extends('layouts.admin')

@section('title', 'Fiche séminaire')

@section('content')
<div class="content-wrapper p-4">
    <div class="container-fluid">

        <div class="card bg-dark text-white glass-card shadow">
            <div class="card-header d-flex justify-content-between align-items-center border-0">
                <h3 class="card-title mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i> {{ $seminaire->titre }}
                </h3>
                <div>
                    <a href="{{ route('seminaires.index') }}" class="btn btn-outline-light me-2">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <a href="{{ route('seminaires.edit', $seminaire) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form action="{{ route('seminaires.destroy', $seminaire) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Supprimer ce séminaire ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body row g-4">
                <div class="col-md-6">
                    <h5><i class="fas fa-info-circle me-2"></i>Informations</h5>
                    <ul class="list-unstyled">
                        <li><strong>Titre :</strong> {{ $seminaire->titre }}</li>
                        <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($seminaire->date)->format('d-m-Y') }}</li>
                        <li><strong>Lieu :</strong> {{ $seminaire->lieu }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5><i class="fas fa-align-left me-2"></i>Description</h5>
                    <p class="text-white-50">
                        {{ $seminaire->description ?? 'Aucune description.' }}
                    </p>
                </div>

                @if($seminaire->membres && $seminaire->membres->count())
                    <div class="col-12 mt-3">
                        <h5><i class="fas fa-users me-2"></i>Membres participants</h5>
                        <ul class="list-group list-group-flush text-white">
                            @foreach($seminaire->membres as $membre)
                                <li class="list-group-item bg-dark">
                                    {{ $membre->prenom }} {{ $membre->nom }}
                                    @if($membre->ecole)
                                        <small class="text-muted">({{ $membre->ecole->nom }})</small>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="col-12">
                        <div class="alert alert-secondary">
                            Aucun membre inscrit à ce séminaire.
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
