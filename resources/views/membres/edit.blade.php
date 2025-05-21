@extends('layouts.admin')

@section('title', 'Modifier le membre')

@section('content')
<div class="container-fluid">
    <div class="member-form-container">
        <!-- En-tête du formulaire -->
        <div class="member-form-header">
            <h1 class="form-title">
                <i class="fas fa-user-edit"></i>
                Modifier le membre
            </h1>
            <div class="form-actions">
                <a href="{{ route('membres.index') }}" class="btn btn-form-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
            </div>
        </div>

        <!-- Corps du formulaire -->
        <div class="member-form-body">
            <form action="{{ route('membres.update', $membre) }}" method="POST" id="memberForm">
                @csrf
                @method('PUT')

                <!-- Section: Informations personnelles -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-id-card"></i>
                        Informations personnelles
                    </div>
                    
                    <div class="form-grid-2">
                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced required">Prénom</label>
                            <input type="text" name="prenom" class="form-control-enhanced @error('prenom') is-invalid @enderror" value="{{ old('prenom', $membre->prenom) }}" required>
                            @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced required">Nom</label>
                            <input type="text" name="nom" class="form-control-enhanced @error('nom') is-invalid @enderror" value="{{ old('nom', $membre->nom) }}" required>
                            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Date de naissance</label>
                            <input type="date" name="date_naissance" class="form-control-enhanced @error('date_naissance') is-invalid @enderror" value="{{ old('date_naissance', $membre->date_naissance) }}">
                            @error('date_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Sexe</label>
                            <select name="sexe" class="form-select-enhanced @error('sexe') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                <option value="H" @selected(old('sexe', $membre->sexe) === 'H')>Homme</option>
                                <option value="F" @selected(old('sexe', $membre->sexe) === 'F')>Femme</option>
                            </select>
                            @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control-enhanced @error('telephone') is-invalid @enderror" value="{{ old('telephone', $membre->telephone) }}" placeholder="123-456-7890">
                            @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Contact -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-envelope"></i>
                        Contact
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label class="form-label-enhanced">Adresse courriel</label>
                        <input type="email" name="email" class="form-control-enhanced @error('email') is-invalid @enderror" value="{{ old('email', $membre->email) }}" placeholder="exemple@email.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Section: Adresse -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-map-marker-alt"></i>
                        Adresse
                    </div>
                    
                    <div class="form-grid-mixed">
                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Numéro de rue</label>
                            <input type="text" name="numero_rue" class="form-control-enhanced" value="{{ old('numero_rue', $membre->numero_rue) }}">
                        </div>

                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Nom de rue</label>
                            <input type="text" name="nom_rue" class="form-control-enhanced" value="{{ old('nom_rue', $membre->nom_rue) }}">
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Ville</label>
                            <input type="text" name="ville" class="form-control-enhanced" value="{{ old('ville', $membre->ville) }}">
                        </div>

                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Province</label>
                            <input type="text" name="province" class="form-control-enhanced" value="{{ old('province', $membre->province) }}">
                        </div>

                        <div class="form-group-enhanced">
                            <label class="form-label-enhanced">Code postal</label>
                            <input type="text" name="code_postal" class="form-control-enhanced" value="{{ old('code_postal', $membre->code_postal) }}">
                        </div>
                    </div>
                </div>

                <!-- Section: École (si superadmin) -->
                @if(auth()->user()->role === 'superadmin')
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-school"></i>
                        École
                    </div>
                    
                    <div class="form-group-enhanced">
                        <label class="form-label-enhanced required">École</label>
                        <select name="ecole_id" class="form-select-enhanced @error('ecole_id') is-invalid @enderror" required>
                            <option value="">-- Choisir une école --</option>
                            @foreach($ecoles as $ecole)
                                <option value="{{ $ecole->id }}" @selected(old('ecole_id', $membre->ecole_id) == $ecole->id)>
                                    {{ $ecole->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('ecole_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                @else
                    <input type="hidden" name="ecole_id" value="{{ auth()->user()->ecole_id }}">
                @endif

            </form>
        </div>

        <!-- Pied du formulaire -->
        <div class="form-actions-bottom">
            <div>
                <small class="text-muted">* Champs obligatoires</small>
            </div>
            <div>
                <button type="submit" form="memberForm" class="btn-form-primary" id="submitBtn">
                    <i class="fas fa-save me-1"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
