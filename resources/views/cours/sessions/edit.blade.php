@extends("layouts.admin")

@section('title', 'Modifier la session : ' . $session->nom)

@push('styles')
<link href="{{ asset('css/cours/sessions-duplication.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('cours.sessions.show', $session) }}" class="btn btn-secondary-glass me-3">
                    <i class="fas fa-arrow-left"></i> Retour à la session
                </a>
                <div>
                    <h1 class="h2 mb-1" style="color: #e2e8f0;">
                        <i class="fas fa-edit me-2"></i>
                        Modifier la session
                    </h1>
                    <p class="text-muted mb-0">{{ $session->nom }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            @if($session->inscriptions_actives)
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="fas fa-check-circle me-1"></i> Inscriptions Ouvertes
                </span>
            @else
                <span class="badge bg-secondary fs-6 px-3 py-2">
                    <i class="fas fa-pause-circle me-1"></i> Inscriptions Fermées
                </span>
            @endif
        </div>
    </div>

    <!-- Alerte si cours existants -->
    @if($session->cours && $session->cours->count() > 0)
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Attention :</strong> Cette session contient {{ $session->cours->count() }} cours. 
        Modifier les dates pourrait affecter les cours existants.
        <a href="{{ route('cours.sessions.show', $session) }}" class="btn btn-sm btn-outline-warning ms-3">
            Voir les cours
        </a>
    </div>
    @endif

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-edit me-2"></i>
                        Informations de la session
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cours.sessions.update', $session) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nom et période -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="nom" class="form-label">Nom de la session *</label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" 
                                           value="{{ old('nom', $session->nom) }}" 
                                           placeholder="Ex: Hiver 2025"
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="mois" class="form-label">Période</label>
                                    <input type="text" 
                                           class="form-control @error('mois') is-invalid @enderror" 
                                           id="mois" name="mois" 
                                           value="{{ old('mois', $session->mois) }}" 
                                           placeholder="Ex: Jan-Mar">
                                    @error('mois')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date_debut" class="form-label">Date de début *</label>
                                    <input type="date" 
                                           class="form-control @error('date_debut') is-invalid @enderror" 
                                           id="date_debut" name="date_debut" 
                                           value="{{ old('date_debut', $session->date_debut ? \Carbon\Carbon::parse($session->date_debut)->format('Y-m-d') : '') }}" 
                                           required>
                                    @error('date_debut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date_fin" class="form-label">Date de fin *</label>
                                    <input type="date" 
                                           class="form-control @error('date_fin') is-invalid @enderror" 
                                           id="date_fin" name="date_fin" 
                                           value="{{ old('date_fin', $session->date_fin ? \Carbon\Carbon::parse($session->date_fin)->format('Y-m-d') : '') }}" 
                                           required>
                                    @error('date_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3"
                                      placeholder="Description de la session, objectifs, informations particulières...">{{ old('description', $session->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date_limite_inscription" class="form-label">Date limite d'inscription</label>
                                    <input type="date" 
                                           class="form-control @error('date_limite_inscription') is-invalid @enderror" 
                                           id="date_limite_inscription" name="date_limite_inscription" 
                                           value="{{ old('date_limite_inscription', $session->date_limite_inscription ? \Carbon\Carbon::parse($session->date_limite_inscription)->format('Y-m-d') : '') }}">
                                    @error('date_limite_inscription')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="couleur" class="form-label">Couleur d'identification</label>
                                    <input type="color" 
                                           class="form-control @error('couleur') is-invalid @enderror" 
                                           id="couleur" name="couleur" 
                                           value="{{ old('couleur', $session->couleur ?? '#48bb78') }}"
                                           style="height: 45px;">
                                    @error('couleur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Cases à cocher -->
                        <div class="form-group mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" 
                                       id="activer_inscriptions" name="activer_inscriptions" value="1"
                                       {{ old('activer_inscriptions', $session->inscriptions_actives) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activer_inscriptions">
                                    <strong>Activer les inscriptions</strong>
                                    <br><small class="text-muted">Les membres pourront s'inscrire aux cours de cette session</small>
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" 
                                       id="visible_public" name="visible_public" value="1"
                                       {{ old('visible_public', $session->visible) ? 'checked' : '' }}>
                                <label class="form-check-label" for="visible_public">
                                    <strong>Visible publiquement</strong>
                                    <br><small class="text-muted">La session apparaîtra dans les listes publiques</small>
                                </label>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <div>
                                @if($session->cours && $session->cours->count() > 0)
                                    <a href="{{ route('cours.sessions.show', $session) }}" class="btn btn-outline-info">
                                        <i class="fas fa-list me-2"></i>Voir les {{ $session->cours->count() }} cours
                                    </a>
                                @endif
                            </div>
                            <div class="d-flex gap-3">
                                <a href="{{ route('cours.sessions.show', $session) }}" class="btn btn-secondary-glass">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-success-gradient">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar avec statistiques -->
        <div class="col-lg-4">
            <!-- Stats -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="session-stats">
                        <div class="stat-card">
                            <span class="stat-number">{{ $session->cours ? $session->cours->count() : 0 }}</span>
                            <div class="stat-label">Cours</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number">
                                @if($session->cours)
                                    {{ $session->cours->sum(function($cours) { return $cours->inscriptions ? $cours->inscriptions->count() : 0; }) }}
                                @else
                                    0
                                @endif
                            </span>
                            <div class="stat-label">Inscrits Total</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number">
                                @if($session->cours)
                                    {{ $session->cours->sum('places_max') }}
                                @else
                                    0
                                @endif
                            </span>
                            <div class="stat-label">Places Total</div>
                        </div>
                        @if($session->cours && $session->cours->sum('tarif') > 0)
                        <div class="stat-card">
                            <span class="stat-number">{{ number_format($session->cours->sum('tarif'), 0) }}$</span>
                            <div class="stat-label">Revenus Potentiels</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="color: #e2e8f0;">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success btn-sm btn-dupliquer" 
                                data-bs-toggle="modal" data-bs-target="#modalDuplication"
                                data-session-id="{{ $session->id }}">
                            <i class="fas fa-copy me-2"></i>Dupliquer cette session
                        </button>
                        
                        @if($session->inscriptions_actives)
                            <form method="POST" action="{{ route('cours.sessions.fermer-reinscriptions', $session) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                                    <i class="fas fa-pause me-2"></i>Fermer les réinscriptions
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('cours.sessions.activer-reinscriptions', $session) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-play me-2"></i>Activer les réinscriptions
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('cours.create') }}?session_id={{ $session->id }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-plus me-2"></i>Ajouter un cours
                        </a>
                        
                        <hr style="border-color: rgba(255, 255, 255, 0.1);">
                        
                        <form method="POST" action="{{ route('cours.sessions.destroy', $session) }}" 
                              onsubmit="return confirm('Supprimer cette session et tous ses cours ?')" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-trash me-2"></i>Supprimer la session
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Duplication (réutilisé) -->
<div class="modal fade modal-duplication" id="modalDuplication" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-copy me-2"></i>Dupliquer la session : {{ $session->nom }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form method="POST" action="{{ route('cours.sessions.dupliquer', $session) }}" id="formDuplication">
                @csrf
                <div class="form-duplication">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouveau_nom">Nom de la nouvelle session *</label>
                                <input type="text" class="form-control" id="nouveau_nom" name="nouveau_nom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nouveau_mois">Période</label>
                                <input type="text" class="form-control" id="nouveau_mois" name="nouveau_mois">
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
                            <label class="custom-control-label" for="copier_cours">Copier tous les cours avec leurs horaires</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="activer_reinscriptions" name="activer_reinscriptions" value="1">
                            <label class="custom-control-label" for="activer_reinscriptions">Activer immédiatement les réinscriptions</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary-glass" data-bs-dismiss="modal">Annuler</button>
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
<script>
// Validation des dates
document.addEventListener('DOMContentLoaded', function() {
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    
    dateDebut.addEventListener('change', function() {
        dateFin.min = this.value;
        if (dateFin.value && dateFin.value < this.value) {
            dateFin.value = '';
        }
    });
    
    // Avertissement si modification des dates avec cours existants
    @if($session->cours && $session->cours->count() > 0)
    [dateDebut, dateFin].forEach(input => {
        input.addEventListener('change', function() {
            const alert = document.createElement('div');
            alert.className = 'alert alert-warning mt-2';
            alert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Attention : Cette modification affectera {{ $session->cours->count() }} cours existants.';
            
            const existing = input.parentNode.querySelector('.alert');
            if (existing) existing.remove();
            input.parentNode.appendChild(alert);
            
            setTimeout(() => alert.remove(), 5000);
        });
    });
    @endif
});
</script>
@endpush
