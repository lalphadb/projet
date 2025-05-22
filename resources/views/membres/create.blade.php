@extends('layouts.admin')

@section('title', 'Ajouter un membre')

@section('content')
<div class="container-fluid">
    <div class="member-form-container">
        <!-- En-tête du formulaire -->
        <div class="member-form-header">
            <h1 class="form-title">
                <i class="fas fa-user-plus"></i>
                Ajouter un nouveau membre
            </h1>
            <div class="form-actions">
                <a href="{{ route('membres.index') }}" class="btn btn-form-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                </a>
            </div>
        </div>

        <!-- Corps du formulaire -->
        <div class="member-form-body">
            <form action="{{ route('membres.store') }}" method="POST" id="memberForm">
                @csrf

                <!-- Section: Informations personnelles -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-id-card"></i>
                        Informations personnelles
                    </div>
                    
                    <div class="form-grid-2">
                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced required">Prénom</label>
                            <input type="text" 
                                   name="prenom" 
                                   class="form-control-enhanced @error('prenom') is-invalid @enderror" 
                                   value="{{ old('prenom') }}"
                                   placeholder="Entrez le prénom"
                                   required>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced required">Nom</label>
                            <input type="text" 
                                   name="nom" 
                                   class="form-control-enhanced @error('nom') is-invalid @enderror" 
                                   value="{{ old('nom') }}"
                                   placeholder="Entrez le nom"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group-enhanced field-short" data-field="date_naissance">
                            <label class="form-label-enhanced">Date de naissance</label>
                            <input type="date" 
                                   name="date_naissance" 
                                   class="form-control-enhanced @error('date_naissance') is-invalid @enderror" 
                                   value="{{ old('date_naissance') }}">
                            <div class="form-help">Optionnel</div>
                            @error('date_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-enhanced field-short" data-field="sexe">
                            <label class="form-label-enhanced">Sexe</label>
                            <select name="sexe" class="form-select-enhanced @error('sexe') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                <option value="H" @selected(old('sexe') == 'H')>Homme</option>
                                <option value="F" @selected(old('sexe') == 'F')>Femme</option>
                            </select>
                            @error('sexe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-enhanced field-medium" data-field="telephone">
                            <label class="form-label-enhanced">Téléphone</label>
                            <input type="tel" 
                                   name="telephone" 
                                   class="form-control-enhanced @error('telephone') is-invalid @enderror" 
                                   value="{{ old('telephone') }}"
                                   placeholder="123-456-7890">
                            <div class="form-help">Format: 123-456-7890</div>
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Contact -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-envelope"></i>
                        Contact
                    </div>
                    
                    <div class="form-group-enhanced field-long" data-field="email">
                        <label class="form-label-enhanced">Adresse courriel</label>
                        <input type="email" 
                               name="email" 
                               class="form-control-enhanced @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               placeholder="exemple@email.com">
                        <div class="form-help">Pour les communications importantes</div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Section: Adresse -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-map-marker-alt"></i>
                        Adresse
                    </div>
                    
                    <div class="form-grid-mixed">
                        <div class="form-group-enhanced field-short">
                            <label class="form-label-enhanced">Numéro de rue</label>
                            <input type="text" 
                                   name="numero_rue" 
                                   class="form-control-enhanced" 
                                   value="{{ old('numero_rue') }}"
                                   placeholder="123">
                        </div>

                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Nom de rue</label>
                            <input type="text" 
                                   name="nom_rue" 
                                   class="form-control-enhanced" 
                                   value="{{ old('nom_rue') }}"
                                   placeholder="Rue de la Paix">
                        </div>
                    </div>

                    <div class="form-grid-address">
                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Ville</label>
                            <input type="text" 
                                   name="ville" 
                                   class="form-control-enhanced" 
                                   value="{{ old('ville') }}"
                                   placeholder="Québec">
                        </div>

                        <div class="form-group-enhanced field-short" data-field="province">
                            <label class="form-label-enhanced">Province</label>
                            <input type="text" 
                                   name="province" 
                                   class="form-control-enhanced" 
                                   value="{{ old('province', 'QC') }}"
                                   placeholder="QC">
                        </div>

                        <div class="form-group-enhanced field-short" data-field="code_postal">
                            <label class="form-label-enhanced">Code postal</label>
                            <input type="text" 
                                   name="code_postal" 
                                   class="form-control-enhanced" 
                                   value="{{ old('code_postal') }}"
                                   placeholder="G1A 1A1">
                            <div class="form-help">Format: A1A 1A1</div>
                        </div>
                    </div>
                </div>

                <!-- Section: École (gestion des rôles) -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-school"></i>
                        École
                    </div>
                    
                    @if(auth()->user()->role === 'superadmin')
                        <!-- Superadmin: Peut choisir n'importe quelle école -->
                        <div class="form-group-enhanced field-long" data-field="ecole">
                            <label class="form-label-enhanced required">École</label>
                            <select name="ecole_id" class="form-select-enhanced @error('ecole_id') is-invalid @enderror" required>
                                <option value="">-- Choisir une école --</option>
                                @foreach($ecoles as $ecole)
                                    <option value="{{ $ecole->id }}" @selected(old('ecole_id') == $ecole->id)>
                                        {{ $ecole->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-help">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Vous pouvez assigner ce membre à n'importe quelle école
                            </div>
                            @error('ecole_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <!-- Admin d'école/Instructeur: École fixe -->
                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">École assignée</label>
                            <div class="school-info-display">
                                <div class="school-card">
                                    <div class="school-icon">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div class="school-details">
                                        <div class="school-name">{{ auth()->user()->ecole->nom }}</div>
                                        <div class="school-location">
                                            @if(auth()->user()->ecole->ville)
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ auth()->user()->ecole->ville }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="school-badge">
                                        <span class="badge bg-info">Votre école</span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="ecole_id" value="{{ auth()->user()->ecole_id }}">
                            <div class="form-help">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Le membre sera automatiquement assigné à votre école
                            </div>
                        </div>
                    @endif
                </div>

            </form>
        </div>

        <!-- Pied du formulaire -->
        <div class="form-actions-bottom">
            <div>
                <small class="text-muted">
                    <i class="fas fa-asterisk text-danger me-1" style="font-size: 0.6rem;"></i>
                    Champs obligatoires
                </small>
            </div>
            <div class="d-flex gap-3">
                <button type="reset" class="btn btn-form-secondary" onclick="resetForm()">
                    <i class="fas fa-undo me-1"></i>
                    Réinitialiser
                </button>
                <button type="submit" form="memberForm" class="btn-form-primary" id="submitBtn">
                    <i class="fas fa-user-plus me-1"></i>
                    Créer le membre
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les données saisies seront perdues.')) {
        document.getElementById('memberForm').reset();
        // Supprime les classes de validation
        document.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
            el.classList.remove('is-invalid', 'is-valid');
            el.style.borderColor = '';
        });
    }
}
</script>
@endsection
