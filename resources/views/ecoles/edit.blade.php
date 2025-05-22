@extends('layouts.admin')

@section('title', 'Modifier une école')

@push('styles')
<link href="{{ asset('css/ecoles.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="ecole-header mb-4">
                <div class="ecole-title">
                    <div class="title-content">
                        <i class="fas fa-edit"></i>
                        Modifier l'école: {{ $ecole->nom }}
                    </div>
                    <a href="{{ route('ecoles.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Formulaire d'édition -->
            <div class="ecole-container fade-in">
                <form action="{{ route('ecoles.update', $ecole->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-header">
                        <h5 class="text-white">
                            <i class="fas fa-school me-2"></i>
                            Informations de l'école
                        </h5>
                        <p class="text-muted">Modifier les informations de cette école</p>
                    </div>
                    
                    <!-- Informations de base -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informations de base
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label text-white">Nom de l'école *</label>
                                <input type="text" 
                                       class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom', $ecole->nom) }}" 
                                       required>
                                <small class="text-muted">Nom complet de l'établissement</small>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="responsable" class="form-label text-white">Responsable</label>
                                <input type="text" 
                                       class="form-control @error('responsable') is-invalid @enderror" 
                                       id="responsable" 
                                       name="responsable" 
                                       value="{{ old('responsable', $ecole->responsable) }}">
                                <small class="text-muted">Nom du directeur ou gestionnaire principal</small>
                                @error('responsable')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Coordonnées -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-map-marked-alt"></i>
                            Coordonnées
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="adresse" class="form-label text-white">Adresse</label>
                                <input type="text" 
                                       class="form-control @error('adresse') is-invalid @enderror" 
                                       id="adresse" 
                                       name="adresse" 
                                       value="{{ old('adresse', $ecole->adresse) }}">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="ville" class="form-label text-white">Ville</label>
                                <input type="text" 
                                       class="form-control @error('ville') is-invalid @enderror" 
                                       id="ville" 
                                       name="ville" 
                                       value="{{ old('ville', $ecole->ville) }}">
                                @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="province" class="form-label text-white">Province</label>
                                <input type="text" 
                                       class="form-control @error('province') is-invalid @enderror" 
                                       id="province" 
                                       name="province" 
                                       value="{{ old('province', $ecole->province) }}">
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="telephone" class="form-label text-white">Téléphone</label>
                                <input type="tel" 
                                       class="form-control @error('telephone') is-invalid @enderror" 
                                       id="telephone" 
                                       name="telephone" 
                                       value="{{ old('telephone', $ecole->telephone) }}">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Options -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-cog"></i>
                            Options
                        </h5>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="active" name="active" 
                                  {{ old('active', $ecole->active) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="active">
                                École active
                            </label>
                            <div class="text-muted small">Une école inactive ne sera pas accessible aux utilisateurs et membres</div>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-chart-bar"></i>
                            Statistiques de l'école
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="ecole-stat">
                                    <div class="stat-value">{{ $ecole->membres_count ?? $ecole->membres()->count() }}</div>
                                    <div class="stat-label">Membres</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="ecole-stat">
                                    <div class="stat-value">{{ $ecole->cours_count ?? $ecole->cours()->count() }}</div>
                                    <div class="stat-label">Cours</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="ecole-stat">
                                    <div class="stat-value">{{ $ecole->users_count ?? $ecole->users()->count() }}</div>
                                    <div class="stat-label">Administrateurs</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-flex gap-3 justify-content-end mt-4 form-action-buttons">
                        <a href="{{ route('ecoles.show', $ecole->id) }}" class="btn-secondary">
                            <i class="fas fa-eye me-2"></i>
                            Voir les détails
                        </a>
                        <a href="{{ route('ecoles.index') }}" class="btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Zone de danger -->
            @if(auth()->user()->isSuperAdmin())
            <div class="ecole-container mt-4 fade-in">
                <div class="form-header">
                    <h5 class="text-white">
                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                        Zone de danger
                    </h5>
                    <p class="text-muted">Actions irréversibles sur cette école</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="danger-card p-3 mb-3" style="background-color: rgba(220, 53, 69, 0.1); border-radius: 10px; border: 1px solid rgba(220, 53, 69, 0.2);">
                            <h6 class="text-danger mb-3">Supprimer cette école</h6>
                            <p class="text-muted mb-3">
                                Cette action supprimera définitivement l'école "{{ $ecole->nom }}" et toutes ses relations. 
                                Cette opération est irréversible.
                            </p>
                            <form action="{{ route('ecoles.destroy', $ecole->id) }}" method="POST" class="d-inline" 
                                  data-confirm="ATTENTION : Vous êtes sur le point de supprimer l'école '{{ $ecole->nom }}' et potentiellement toutes ses données associées (membres, cours, sessions, etc.). Cette action est IRRÉVERSIBLE. Êtes-vous absolument certain de vouloir procéder ?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt me-2"></i>
                                    Supprimer définitivement
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="danger-card p-3 mb-3" style="background-color: rgba(255, 193, 7, 0.1); border-radius: 10px; border: 1px solid rgba(255, 193, 7, 0.2);">
                            <h6 class="text-warning mb-3">Désactiver cette école</h6>
                            <p class="text-muted mb-3">
                                Au lieu de supprimer l'école, vous pouvez simplement la désactiver. 
                                Les données seront conservées mais l'école ne sera plus accessible.
                            </p>
                            @if($ecole->active)
                            <form action="{{ route('ecoles.toggle-status', $ecole->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-ban me-2"></i>
                                    Désactiver l'école
                                </button>
                            </form>
                            @else
                            <form action="{{ route('ecoles.toggle-status', $ecole->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Réactiver l'école
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Espace supplémentaire pour éviter que le footer chevauche le contenu -->
            <div class="mb-large"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation pour les suppressions et actions dangereuses
    $('form[data-confirm]').on('submit', function(e) {
        const message = $(this).data('confirm') || 'Êtes-vous sûr de vouloir effectuer cette action ?';
        if (!confirm(message)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
