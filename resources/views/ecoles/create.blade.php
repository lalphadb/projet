@extends('layouts.admin')

@section('title', 'Ajouter une école')

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
                        <i class="fas fa-plus-circle"></i>
                        Ajouter une nouvelle école
                    </div>
                    <a href="{{ route('ecoles.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Formulaire de création -->
            <div class="ecole-container fade-in">
                <form action="{{ route('ecoles.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-header">
                        <h5 class="text-white">
                            <i class="fas fa-school me-2"></i>
                            Informations de l'école
                        </h5>
                        <p class="text-muted">Créez une nouvelle école pour votre réseau</p>
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
                                       value="{{ old('nom') }}" 
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
                                       value="{{ old('responsable') }}">
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
                                       value="{{ old('adresse') }}">
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
                                       value="{{ old('ville') }}">
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
                                       value="{{ old('province', 'Québec') }}">
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
                                       value="{{ old('telephone') }}">
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
                            <input type="hidden" name="active" value="0">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="active" 
                                   name="active" 
                                   value="1"
                                   {{ old('active', true) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="active">
                                École active
                            </label>
                            <div class="text-muted small">Une école inactive ne sera pas accessible aux utilisateurs et membres</div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-flex gap-3 justify-content-end mt-4 form-action-buttons">
                        <a href="{{ route('ecoles.index') }}" class="btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Enregistrer l'école
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mb-large"></div>
        </div>
    </div>
</div>
@endsection
