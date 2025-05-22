@extends('layouts.admin')

@section('title', 'Créer un administrateur d\'école')

@push('styles')
<link href="{{ asset('css/users.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="cours-header mb-4">
                <div class="cours-title">
                    <div class="title-content">
                        <i class="fas fa-user-plus"></i>
                        Créer un nouvel administrateur d'école
                    </div>
                    <a href="{{ route('users.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Formulaire de création -->
            <div class="admin-container fade-in">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-header">
                        <h5 class="text-white">
                            <i class="fas fa-user-shield me-2"></i>
                            Informations de l'administrateur
                        </h5>
                        <p class="text-muted">Créez un nouveau compte administrateur pour gérer une école</p>
                    </div>
                    
                    <!-- Informations personnelles -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-user"></i>
                            Informations personnelles
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label text-white">Nom *</label>
                                <input type="text" 
                                       class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom') }}" 
                                       required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="prenom" class="form-label text-white">Prénom *</label>
                                <input type="text" 
                                       class="form-control @error('prenom') is-invalid @enderror" 
                                       id="prenom" 
                                       name="prenom" 
                                       value="{{ old('prenom') }}" 
                                       required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label text-white">Adresse email *</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
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
                    
                    <!-- Informations de connexion -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-lock"></i>
                            Informations de connexion
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label text-white">Nom d'utilisateur *</label>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username') }}" 
                                       required>
                                <small class="text-muted">Utilisé pour la connexion (pas d'espaces ou caractères spéciaux)</small>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password" class="form-label text-white">Mot de passe *</label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <span class="password-toggle" onclick="togglePassword('password')">
                                        <i class="far fa-eye"></i>
                                    </span>
                                </div>
                                <small class="text-muted">8 caractères minimum, avec majuscules et chiffres</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label text-white">Confirmer le mot de passe *</label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                                        <i class="far fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rôle et école -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-user-tag"></i>
                            Rôle et école
                        </h5>
                        
                        <div class="mb-4">
                            <label class="form-label text-white">Sélectionner un rôle *</label>
                            <div class="role-selector">
                                <div class="role-card @if(old('role') == 'admin' || old('role') == '') selected @endif" onclick="selectRole('admin')">
                                    <h5><i class="fas fa-user-cog"></i> Administrateur</h5>
                                    <p>Peut gérer une école spécifique : membres, cours, présences</p>
                                </div>
                                
                                <div class="role-card @if(old('role') == 'instructor') selected @endif" onclick="selectRole('instructor')">
                                    <h5><i class="fas fa-chalkboard-teacher"></i> Instructeur</h5>
                                    <p>Peut gérer les cours et les présences, mais ne peut pas gérer les membres</p>
                                </div>
                            </div>
                            <input type="hidden" name="role" id="role_input" value="{{ old('role', 'admin') }}">
                            @error('role')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="ecole_id" class="form-label text-white">École assignée *</label>
                            <select class="form-select @error('ecole_id') is-invalid @enderror" 
                                    id="ecole_id" 
                                    name="ecole_id" 
                                    required>
                                <option value="">Sélectionner une école</option>
                                @if(isset($ecoles))
                                    @foreach($ecoles as $ecole)
                                        <option value="{{ $ecole->id }}" {{ old('ecole_id') == $ecole->id ? 'selected' : '' }}>
                                            {{ $ecole->nom }}
                                            @if(isset($ecole->ville) && $ecole->ville)
                                                ({{ $ecole->ville }})
                                            @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('ecole_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Options supplémentaires -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-cog"></i>
                            Options supplémentaires
                        </h5>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                            <label class="form-check-label text-white" for="active">
                                Compte actif immédiatement
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="send_credentials" name="send_credentials" checked>
                            <label class="form-check-label text-white" for="send_credentials">
                                Envoyer les informations de connexion par email
                            </label>
                        </div>
                    </div>
                    
                    <!-- Actions avec classe pour éviter le recouvrement par le footer -->
                    <div class="d-flex gap-3 justify-content-end mt-4 form-action-buttons">
                        <a href="{{ route('users.index') }}" class="btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Créer l'administrateur
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Espace supplémentaire pour éviter que le footer chevauche le contenu -->
            <div class="mb-large"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/users.js') }}"></script>
<script>
// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // S'assurer que le rôle par défaut est sélectionné
    const roleInput = document.getElementById('role_input');
    if (roleInput.value) {
        selectRole(roleInput.value);
    } else {
        selectRole('admin'); // Rôle par défaut
    }
});
</script>
@endpush
