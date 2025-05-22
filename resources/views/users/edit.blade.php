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
                        <i class="fas fa-user-edit"></i>
                        Modifier l'administrateur: {{ $user->prenom }} {{ $user->nom }}
                    </div>
                    <a href="{{ route('users.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Formulaire de modification -->
            <div class="admin-edit-container">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-header">
                        <h5 class="text-white">
                            <i class="fas fa-user-shield me-2"></i>
                            Informations de l'administrateur
                        </h5>
                        <p class="text-muted">Modifiez les informations du compte administrateur</p>
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
                                       value="{{ old('nom', $user->nom) }}" 
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
                                       value="{{ old('prenom', $user->prenom) }}" 
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
                                       value="{{ old('email', $user->email) }}" 
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
                                       value="{{ old('telephone', $user->telephone) }}">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nouveau mot de passe (optionnel) -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-lock"></i>
                            Nouveau mot de passe (facultatif)
                        </h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label text-white">Nouveau mot de passe</label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password">
                                    <span class="password-toggle" onclick="togglePassword('password')">
                                        <i class="far fa-eye"></i>
                                    </span>
                                </div>
                                <small class="text-muted">Laissez vide pour conserver le mot de passe actuel.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label text-white">Confirmer le mot de passe</label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation">
                                    <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                                        <i class="far fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(auth()->user()->role === 'superadmin' && auth()->user()->id !== $user->id)
                    <!-- Rôle et école (admin seulement) -->
                    <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-user-tag"></i>
                            Rôle et école
                        </h5>
                        
                        <div class="mb-4">
                            <label class="form-label text-white">Sélectionner un rôle *</label>
                            <div class="role-selector">
                                <div class="role-card @if(old('role', $user->role) == 'admin') selected @endif" onclick="selectRole('admin')">
                                    <h5><i class="fas fa-user-cog"></i> Administrateur</h5>
                                    <p>Peut gérer une école spécifique : membres, cours, présences</p>
                                </div>
                                
                                <div class="role-card @if(old('role', $user->role) == 'instructor') selected @endif" onclick="selectRole('instructor')">
                                    <h5><i class="fas fa-chalkboard-teacher"></i> Instructeur</h5>
                                    <p>Peut gérer les cours et les présences, mais ne peut pas gérer les membres</p>
                                </div>
                            </div>
                            <input type="hidden" name="role" id="role_input" value="{{ old('role', $user->role) }}">
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
                                @foreach($ecoles as $ecole)
                                    <option value="{{ $ecole->id }}" {{ old('ecole_id', $user->ecole_id) == $ecole->id ? 'selected' : '' }}>
                                        {{ $ecole->nom }}
                                        @if($ecole->ville)
                                            ({{ $ecole->ville }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('ecole_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="active" 
                                   name="active" 
                                   {{ old('active', $user->active) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="active">
                                Compte actif
                            </label>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="d-flex gap-3 justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="btn-secondary">
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function selectRole(role) {
    // Mettre à jour la valeur cachée
    document.getElementById('role_input').value = role;
    
    // Mettre à jour les classes visuelles
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    document.querySelector(`.role-card[onclick="selectRole('${role}')"]`).classList.add('selected');
}
</script>
@endpush
@push('scripts')
<script src="{{ asset('js/users.js') }}"></script>
@endpush
