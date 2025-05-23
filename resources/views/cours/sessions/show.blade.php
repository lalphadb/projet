@extends('layouts.admin')

@section('title', 'Session : ' . $session->nom)

@push('styles')
<link href="{{ asset('css/cours/sessions-duplication.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header avec actions -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('cours.sessions.index') }}" class="btn btn-secondary-glass me-3">
                    <i class="fas fa-arrow-left"></i> Retour aux sessions
                </a>
                <div>
                    <h1 class="h2 mb-1" style="color: #e2e8f0;">{{ $session->nom }}</h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            @if($session->inscriptions_actives)
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="fas fa-check-circle me-1"></i> Réinscriptions Ouvertes
                </span>
            @else
                <span class="badge bg-secondary fs-6 px-3 py-2">
                    <i class="fas fa-pause-circle me-1"></i> Réinscriptions Fermées
                </span>
            @endif
        </div>
    </div>

    <!-- Statistiques -->
    <div class="session-stats">
        <div class="stat-card">
            <span class="stat-number">{{ $session->cours->count() }}</span>
            <div class="stat-label">Cours Total</div>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $totalInscrits }}</span>
            <div class="stat-label">Membres Inscrits</div>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ $tauxRemplissage }}%</span>
            <div class="stat-label">Taux Remplissage</div>
        </div>
        <div class="stat-card">
            <span class="stat-number" style="color: #667eea;">
                {{ number_format($session->cours->sum('tarif'), 0) }}$
            </span>
            <div class="stat-label">Revenus Potentiels</div>
        </div>
    </div>

    <!-- Actions de la session -->
    <div class="session-actions">
        <button type="button" class="btn-dupliquer" data-bs-toggle="modal" data-bs-target="#modalDuplication" data-session-id="{{ $session->id }}">
            <i class="fas fa-copy"></i>
            Dupliquer la Session
        </button>
        
        @if($session->inscriptions_actives)
            <form method="POST" action="{{ route('cours.sessions.fermer-reinscriptions', $session) }}" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-reinscriptions inactive">
                    <i class="fas fa-pause"></i>
                    Fermer Réinscriptions
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('cours.sessions.activer-reinscriptions', $session) }}" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-reinscriptions">
                    <i class="fas fa-play"></i>
                    Activer Réinscriptions
                </button>
            </form>
        @endif

        <a href="{{ route('cours.sessions.edit', $session) }}" class="btn btn-secondary-glass">
            <i class="fas fa-edit"></i>
            Modifier Session
        </a>
    </div>

    <!-- Liste des cours -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="background: rgba(26, 54, 93, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);">
                <div class="card-header" style="background: rgba(45, 55, 72, 0.6); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                    <h5 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-list me-2"></i>
                        Cours de cette session ({{ $session->cours->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($session->cours->count() > 0)
                        <div class="row">
                            @foreach($session->cours as $cours)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="cours-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="cours-nom">{{ $cours->nom }}</h6>
                                            <span class="badge bg-primary">{{ $cours->inscriptions->count() }}/{{ $cours->places_max }}</span>
                                        </div>
                                        
                                        @if($cours->instructeur)
                                            <p class="cours-detail">
                                                <i class="fas fa-user-tie"></i>
                                                {{ $cours->instructeur }}
                                            </p>
                                        @endif
                                        
                                        @if($cours->tarif)
                                            <p class="cours-detail">
                                                <i class="fas fa-dollar-sign"></i>
                                                {{ number_format($cours->tarif, 0) }}$ / mois
                                            </p>
                                        @endif

                                        <!-- Horaires -->
                                        @if($cours->plages_horaires)
                                            @php
                                                $plages = is_string($cours->plages_horaires) ? json_decode($cours->plages_horaires, true) : $cours->plages_horaires;
                                            @endphp
                                            <div class="mt-2">
                                                @foreach($plages as $plage)
                                                    @foreach($plage['jours'] as $jour)
                                                        <span class="cours-horaire">
                                                            {{ ucfirst($jour) }} {{ $plage['heure_debut'] }}-{{ $plage['heure_fin'] }}
                                                        </span>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <a href="{{ route('cours.show', $cours) }}" class="btn btn-sm btn-outline-primary">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5" style="color: #a0aec0;">
                            <i class="fas fa-inbox fa-3x mb-3" style="color: #4a5568;"></i>
                            <h5>Aucun cours dans cette session</h5>
                            <p>Utilisez la duplication pour copier les cours d'une autre session</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Duplication -->
<div class="modal fade modal-duplication" id="modalDuplication" tabindex="-1" aria-labelledby="modalDuplicationLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDuplicationLabel">
                    <i class="fas fa-copy me-2"></i>
                    Dupliquer la session : {{ $session->nom }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="POST" action="{{ route('cours.sessions.dupliquer', $session) }}" id="formDuplication">
                @csrf
                <div class="form-duplication">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouveau_nom">Nom de la nouvelle session *</label>
                                <input type="text" class="form-control" id="nouveau_nom" name="nouveau_nom" required 
                                       placeholder="Ex: Hiver 2026">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouveau_mois">Période</label>
                                <input type="text" class="form-control" id="nouveau_mois" name="nouveau_mois" 
                                       placeholder="Ex: Jan-Mar">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouvelle_date_debut">Date de début *</label>
                                <input type="date" class="form-control" id="nouvelle_date_debut" name="nouvelle_date_debut" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouvelle_date_fin">Date de fin *</label>
                                <input type="date" class="form-control" id="nouvelle_date_fin" name="nouvelle_date_fin" required>
                            </div>
                        </div>
                    </div>

                    <div class="options-duplication">
                        <h6><i class="fas fa-cog me-2"></i>Options de duplication</h6>
                        
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="copier_cours" name="copier_cours" value="1" checked>
                            <label class="custom-control-label" for="copier_cours">
                                Copier tous les cours avec leurs horaires
                            </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="activer_reinscriptions" name="activer_reinscriptions" value="1">
                            <label class="custom-control-label" for="activer_reinscriptions">
                                Activer immédiatement les réinscriptions
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-glass" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-success-gradient">
                        <i class="fas fa-copy me-2"></i>Dupliquer la Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/cours/sessions-duplication.js') }}"></script>
@endpush
