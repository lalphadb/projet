@extends('adminlte::page')

@section('title', 'Détails de la Présence')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-eye text-info"></i> 
            Détails de la Présence
        </h1>
        <div>
            <a href="{{ route('presences.edit', $presence) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('presences.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        {{-- Informations principales --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Informations de présence
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $presence->status_class }} badge-lg">
                            {{ $presence->status_label }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Date de présence</span>
                                    <span class="info-box-number">
                                        {{ $presence->date_presence->format('d/m/Y') }}
                                    </span>
                                    <small class="text-muted">
                                        {{ $presence->date_presence->isoFormat('dddd D MMMM YYYY') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-{{ $presence->status_class }}">
                                    <i class="{{ $presence->status_icon }}"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Statut</span>
                                    <span class="info-box-number">{{ $presence->status_label }}</span>
                                    <small class="text-muted">
                                        Enregistré le {{ $presence->created_at->format('d/m/Y à H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Commentaire --}}
                    @if($presence->commentaire)
                        <div class="mt-3">
                            <h5><i class="fas fa-comment text-muted"></i> Commentaire</h5>
                            <div class="alert alert-light">
                                <p class="mb-0">{{ $presence->commentaire }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Informations de modification --}}
                    @if($presence->updated_at != $presence->created_at)
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-edit"></i> 
                                Dernière modification : {{ $presence->updated_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informations du membre et cours --}}
        <div class="col-md-4">
            {{-- Membre --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"></i> Membre
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="user-avatar bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <span class="text-white font-weight-bold" style="font-size: 1.2rem;">
                                {{ strtoupper(substr($presence->membre->prenom, 0, 1) . substr($presence->membre->nom, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    
                    <h5 class="text-center">{{ $presence->membre->nom }} {{ $presence->membre->prenom }}</h5>
                    
                    @if($presence->membre->email)
                        <p class="text-center text-muted">
                            <i class="fas fa-envelope"></i> {{ $presence->membre->email }}
                        </p>
                    @endif
                    
                    @if($presence->membre->telephone)
                        <p class="text-center text-muted">
                            <i class="fas fa-phone"></i> {{ $presence->membre->telephone }}
                        </p>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('membres.show', $presence->membre) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user-circle"></i> Voir le profil
                        </a>
                    </div>
                </div>
            </div>

            {{-- Cours --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher"></i> Cours
                    </h3>
                </div>
                <div class="card-body">
                    <h5>{{ $presence->cours->nom }}</h5>
                    
                    @if($presence->cours->description)
                        <p class="text-muted">{{ $presence->cours->description }}</p>
                    @endif

                    @if($presence->cours->ecole)
                        <p>
                            <i class="fas fa-school text-muted"></i>
                            <strong>École :</strong> {{ $presence->cours->ecole->nom }}
                        </p>
                    @endif

                    @if($presence->cours->heure_debut && $presence->cours->heure_fin)
                        <p>
                            <i class="fas fa-clock text-muted"></i>
                            <strong>Horaire :</strong> {{ $presence->cours->heure_debut }} - {{ $presence->cours->heure_fin }}
                        </p>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('cours.show', $presence->cours) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-chalkboard-teacher"></i> Voir le cours
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs"></i> Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="btn-group">
                        <a href="{{ route('presences.edit', $presence) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier cette présence
                        </a>
                        <button type="button" class="btn btn-info" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                        <form action="{{ route('presences.destroy', $presence) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette présence ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}
.user-avatar {
    font-weight: bold;
}
@media print {
    .content-header, .main-sidebar, .main-footer, .btn, .card-header .card-tools {
        display: none !important;
    }
}
</style>
@stop
